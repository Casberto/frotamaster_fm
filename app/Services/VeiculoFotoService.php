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
        
        // Store file
        $file->storeAs($path, $filename);

        // Create record
        return VeiculoFoto::create([
            'vef_vei_id' => $veiculoId,
            'arquivo' => "{$path}/{$filename}",
            'vef_criado_em' => now(),
        ]);
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
