<?php

namespace App\Http\Controllers;

use App\Models\SeguroCobertura;
use Illuminate\Http\Request;

class SeguroCoberturaController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sco_seg_id' => 'required|exists:seguros_apolice,seg_id',
            'sco_titulo' => 'required|string|max:255',
            'sco_descricao' => 'nullable|string',
            'sco_valor' => 'nullable|numeric',
        ]);

        SeguroCobertura::create($validated);

        return back()->with('success', 'Cobertura adicionada com sucesso!');
    }

    public function destroy($id)
    {
        $cobertura = SeguroCobertura::findOrFail($id);
        $cobertura->delete();

        return back()->with('success', 'Cobertura removida.');
    }
}
