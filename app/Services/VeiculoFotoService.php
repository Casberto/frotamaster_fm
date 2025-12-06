<?php

namespace App\Services;

use App\Models\VeiculoFoto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class VeiculoFotoService
{
    public function uploadPhoto(UploadedFile $file, int $veiculoId): VeiculoFoto
    {
        $empresaId = Auth::user()->id_empresa;
        $path = "uploads/{$empresaId}/veiculos/{$veiculoId}";
        
        // Ensure directory exists
        if (!Storage::exists($path)) {
            Storage::makeDirectory($path);
        }

        // Generate unique name
        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid() . '.' . $extension;
        
        // Max size allowed without compression (5MB)
        $maxSize = 5 * 1024 * 1024;

        if ($file->getSize() > $maxSize) {
            // Compress image
            $compressedImage = $this->compressImage($file, $maxSize);
            
            // Store compressed file
            Storage::put("{$path}/{$filename}", $compressedImage);
        } else {
            // Store original file
            $file->storeAs($path, $filename);
        }

        // Create record
        return VeiculoFoto::create([
            'vef_vei_id' => $veiculoId,
            'arquivo' => "{$path}/{$filename}",
            'vef_tipo' => 'Geral',
            'vef_criado_em' => now(),
        ]);
    }

    private function compressImage(UploadedFile $file, int $maxSize)
    {
        $imagePath = $file->getRealPath();
        $mime = $file->getMimeType();
        $quality = 80;

        // Load image based on mime type
        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($imagePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($imagePath);
                // For PNG, we need to handle transparency and conversion if we want to compress heavily, 
                // but usually simple quality reduction isn't as effective as JPEG. 
                // Let's try to convert/save or just keep it. 
                // Actually, imagejpeg supports quality, imagepng uses compression level (0-9).
                // To guarantee size reduction, best to convert to JPEG or resize.
                // For simplicity and effectiveness, let's treat it as generic handling or convert to JPEG if massive?
                // For now, let's just support JPEG/PNG native.
                break;
            default:
                // Try guessing from extension if mime fails or is octet-stream
                $ext = strtolower($file->getClientOriginalExtension());
                if ($ext == 'jpg' || $ext == 'jpeg') {
                    $image = @imagecreatefromjpeg($imagePath);
                } elseif ($ext == 'png') {
                    $image = @imagecreatefrompng($imagePath);
                } else {
                    throw new \Exception("Formato de imagem não suportado para compressão.");
                }
        }

        if (!$image) {
            throw new \Exception("Falha ao carregar imagem para compressão.");
        }

        // Get original dimensions
        $width = imagesx($image);
        $height = imagesy($image);

        // Max dimensions (e.g. Full HD is usually enough for vehicle photos)
        $maxWidth = 1920;
        $maxHeight = 1080;

        // Resize if needed
        if ($width > $maxWidth || $height > $maxHeight) {
            $ratio = $width / $height;
            if ($maxWidth / $maxHeight > $ratio) {
                $newWidth = $maxHeight * $ratio;
                $newHeight = $maxHeight;
            } else {
                $newHeight = $maxWidth / $ratio;
                $newWidth = $maxWidth;
            }

            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            
            // Preserve transparency for PNG
            if ($mime == 'image/png' || $file->getClientOriginalExtension() == 'png') {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
            }

            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $newImage;
        }

        // Capture output buffer
        ob_start();
        
        // Save to buffer
        if ($mime == 'image/png' || $file->getClientOriginalExtension() == 'png') {
            // PNG compression 0-9. 9 is max compression.
            imagepng($image, null, 9);
        } else {
            // JPEG quality 0-100.
            imagejpeg($image, null, $quality); 
        }
        
        $content = ob_get_clean();
        imagedestroy($image);

        // Check size
        if (strlen($content) > $maxSize) {
            // Second attempt with lower quality for JPEG
            if ($mime == 'image/jpeg' || $file->getClientOriginalExtension() == 'jpg' || $file->getClientOriginalExtension() == 'jpeg') {
                 $image = imagecreatefromstring($content);
                 ob_start();
                 imagejpeg($image, null, 60); // Drastic reduction
                 $content = ob_get_clean();
                 imagedestroy($image);
            }
            
            if (strlen($content) > $maxSize) {
                throw new \Exception("A imagem é muito grande. Mesmo após compressão, excede o limite de 5MB.");
            }
        }

        return $content;
    }

    public function getPhotos(int $veiculoId)
    {
        return VeiculoFoto::where('vef_vei_id', $veiculoId)
            ->orderBy('vef_criado_em', 'desc')
            ->get()
            ->map(function ($foto) {
                return [
                    'id' => $foto->vef_id,
                    'url' => route('veiculos.fotos.show', ['filename' => basename($foto->arquivo)]),
                    'created_at' => $foto->vef_criado_em->format('d/m/Y H:i'),
                ];
            });
    }

    public function deletePhoto(int $fotoId): void
    {
        $foto = VeiculoFoto::findOrFail($fotoId);
        
        // Verify ownership via vehicle
        if ($foto->veiculo->vei_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }

        if (Storage::exists($foto->arquivo)) {
            Storage::delete($foto->arquivo);
        }

        $foto->delete();
    }

    public function deletePhotosByVehicle(int $veiculoId): void
    {
        $empresaId = Auth::user()->id_empresa;
        $path = "uploads/{$empresaId}/veiculos/{$veiculoId}";

        if (Storage::exists($path)) {
            Storage::deleteDirectory($path);
        }
        
        // Database records will be deleted via cascade, but good to be explicit or rely on cascade
        // Since we have cascade on delete in migration, we don't strictly need to delete rows here if the vehicle is deleted.
        // But deleting the directory is important.
    }
}
