<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use App\Services\VeiculoFotoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VeiculoFotoController extends Controller
{
    protected $veiculoFotoService;

    public function __construct(VeiculoFotoService $veiculoFotoService)
    {
        $this->veiculoFotoService = $veiculoFotoService;
    }

    public function index($veiculoId)
    {
        $veiculo = Veiculo::findOrFail($veiculoId);
        if ($veiculo->vei_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }

        return response()->json($this->veiculoFotoService->getPhotos($veiculoId));
    }

    public function store(Request $request, $veiculoId)
    {
        $veiculo = Veiculo::findOrFail($veiculoId);
        if ($veiculo->vei_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }

        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg|max:15360', // 15MB Max allowed to reach server
        ]);

        try {
            $this->veiculoFotoService->uploadPhoto($request->file('file'), $veiculoId);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Check if it's our custom file size exception or generic
            $message = $e->getMessage();
            if (str_contains($message, 'imagem é muito grande')) {
                return response()->json(['error' => $message], 422);
            }
            return response()->json(['error' => 'Erro ao processar imagem: ' . $message], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->veiculoFotoService->deletePhoto($id);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($filename)
    {
        // Security check: Ensure user belongs to the company that owns the photo
        // This is a bit tricky because we only have filename. 
        // We can look up the photo record first.
        
        $foto = \App\Models\VeiculoFoto::where('arquivo', 'like', "%/{$filename}")->firstOrFail();
        
        if ($foto->veiculo->vei_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }

        if (!Storage::exists($foto->arquivo)) {
            abort(404);
        }

        return response()->file(Storage::path($foto->arquivo));
    }
}
