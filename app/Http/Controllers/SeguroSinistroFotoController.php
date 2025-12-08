<?php

namespace App\Http\Controllers;

use App\Models\SeguroSinistro;
use App\Models\SeguroSinistroFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SeguroSinistroFotoController extends Controller
{
    public function index($sinistroId)
    {
        $sinistro = SeguroSinistro::findOrFail($sinistroId);
        // Check permission if needed (e.g., via policy's company)
        if ($sinistro->apolice->seg_emp_id !== Auth::user()->id_empresa) {
             abort(403);
        }

        $fotos = $sinistro->fotos()->orderBy('ssf_criado_em', 'desc')->get()->map(function ($foto) {
            $extension = strtolower(pathinfo($foto->arquivo, PATHINFO_EXTENSION));
            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
            
            return [
                'id' => $foto->ssf_id,
                'url' => route('seguros.sinistros.fotos.show', ['filename' => basename($foto->arquivo)]),
                'tipo' => $foto->ssf_tipo,
                'extension' => $extension,
                'is_image' => $isImage,
                'original_name' => basename($foto->arquivo) // Simplified for now
            ];
        })->values();

        return response()->json($fotos);
    }

    public function store(Request $request, $sinistroId)
    {
        $sinistro = SeguroSinistro::findOrFail($sinistroId);
        if ($sinistro->apolice->seg_emp_id !== Auth::user()->id_empresa) {
             abort(403);
        }

        $request->validate([
            'file' => 'required|file|mimes:jpeg,png,jpg,pdf|max:15360',
        ]);

        try {
            $file = $request->file('file');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('sinistros_fotos/' . $sinistroId, $filename);

            SeguroSinistroFoto::create([
                'ssf_ssi_id' => $sinistroId,
                'arquivo' => $path,
                'ssf_tipo' => 'Geral'
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao processar arquivo: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $foto = SeguroSinistroFoto::findOrFail($id);
            if ($foto->sinistro->apolice->seg_emp_id !== Auth::user()->id_empresa) {
                abort(403);
            }

            if (Storage::exists($foto->arquivo)) {
                Storage::delete($foto->arquivo);
            }
            
            $foto->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($filename)
    {
        // Simple search by filename. In production, might want strictly ID or cleaner path handling.
        $foto = SeguroSinistroFoto::where('arquivo', 'like', "%/{$filename}")->firstOrFail();
        
        if ($foto->sinistro->apolice->seg_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }

        if (!Storage::exists($foto->arquivo)) {
            abort(404);
        }

        return response()->file(Storage::path($foto->arquivo));
    }
}
