<?php

namespace App\Http\Controllers;

use App\Models\SeguroSinistro;
use App\Models\SeguroSinistroFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SeguroSinistroController extends Controller
{
    public function store(Request $request)
    {
        if (!\Illuminate\Support\Facades\Auth::user()->temPermissao('SEG008')) {
             return back()->with('error', 'O usuário não possuí permissão para registrar sinistros.');
        }

        $validated = $request->validate([
            'ssi_seg_id' => 'required|exists:seguros_apolice,seg_id',
            'ssi_data' => 'required|date',
            'ssi_tipo' => 'required|string',
            'ssi_valor_prejuizo' => 'nullable|numeric',
            'ssi_valor_coberto' => 'nullable|numeric',
            'ssi_status' => 'required|string',
            'ssi_obs' => 'nullable|string',
            'anexos.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // Remove anexos from validated data as we will handle manually
        unset($validated['anexos']);
        
        $sinistro = SeguroSinistro::create($validated);

        // Handle file uploads
        if ($request->hasFile('anexos')) {
            foreach ($request->file('anexos') as $file) {
                 // Replicate logic from SeguroSinistroFotoController
                 // Assuming we want to store it the same way (as "Geral" type)
                 $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                 $path = $file->storeAs('sinistros_fotos/' . $sinistro->ssi_id, $filename);
                 
                 SeguroSinistroFoto::create([
                    'ssf_ssi_id' => $sinistro->ssi_id,
                    'arquivo' => $path,
                    'ssf_tipo' => 'Geral'
                ]);
            }
        }

        return back()->with('success', 'Sinistro registrado com sucesso!');
    }

    public function update(Request $request, $id)
    {
        if (!\Illuminate\Support\Facades\Auth::user()->temPermissao('SEG009')) {
             return back()->with('error', 'O usuário não possuí permissão para editar sinistros.');
        }

        $sinistro = SeguroSinistro::findOrFail($id);
        
        $validated = $request->validate([
            'ssi_data' => 'required|date',
            'ssi_tipo' => 'required|string',
            'ssi_valor_prejuizo' => 'nullable|numeric',
            'ssi_valor_coberto' => 'nullable|numeric',
            'ssi_status' => 'required|string',
            'ssi_obs' => 'nullable|string',
        ]);

        $sinistro->update($validated);
        
        return back()->with('success', 'Sinistro atualizado.');
    }

    public function destroy($id)
    {
        if (!\Illuminate\Support\Facades\Auth::user()->temPermissao('SEG010')) {
             return back()->with('error', 'O usuário não possuí permissão para excluir sinistros.');
        }
        $sinistro = SeguroSinistro::findOrFail($id);
        // Delete files
        // Delete files
        // Handled by Cascade Delete on DB for records, but we need to delete physical files
        foreach ($sinistro->fotos as $foto) {
             if (Storage::exists($foto->arquivo)) {
                Storage::delete($foto->arquivo);
            }
        }
        // Also legacy files if any
        if ($sinistro->ssi_anexos) {
            foreach ($sinistro->ssi_anexos as $file) {
                Storage::disk('public')->delete($file);
            }
        }
        $sinistro->delete();

        return back()->with('success', 'Sinistro excluído.');
    }
}
