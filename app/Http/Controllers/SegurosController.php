<?php

namespace App\Http\Controllers;

use App\Models\SeguroApolice;
use App\Models\SeguroCobertura;
use App\Models\SeguroSinistro;
use App\Models\Veiculo;
use App\Models\Fornecedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SegurosController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user()->temPermissao('SEG001')) {
            return redirect()->route('dashboard')->with('error', 'O usuário não possuí permissão à essa tela');
        }

        $query = SeguroApolice::with(['veiculo', 'fornecedor']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('seg_numero', 'like', "%{$search}%")
                  ->orWhereHas('veiculo', function($v) use ($search) {
                      $v->where('vei_placa', 'like', "%{$search}%")
                        ->orWhere('vei_modelo', 'like', "%{$search}%");
                  })
                  ->orWhereHas('fornecedor', function($f) use ($search) {
                      $f->where('for_nome_fantasia', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('status')) {
            $query->where('seg_status', $request->status);
        }

        $apolices = $query->orderBy('seg_id', 'desc')->paginate(10);

        // Logic for Master-Detail view
        $selectedApolice = null;
        if ($request->has('selected_id')) {
            $selectedApolice = SeguroApolice::with(['coberturas', 'sinistros', 'veiculo', 'fornecedor'])
                ->find($request->selected_id);
        }

        return view('seguros.index', compact('apolices', 'selectedApolice'));
    }

    public function create()
    {
        if (!Auth::user()->temPermissao('SEG002')) {
            return redirect()->route('seguros.index')->with('error', 'O usuário não possuí permissão para criar apólices');
        }

        $idEmpresa = Auth::user()->id_empresa;
        $veiculos = Veiculo::where('vei_emp_id', $idEmpresa)->orderBy('vei_placa')->get(); 
        $seguradoras = Fornecedor::where('for_emp_id', $idEmpresa)
            ->where('for_status', 1)
            ->where('for_tipo', 'seguradora')
            ->orderBy('for_nome_fantasia')
            ->get(); 
        
        return view('seguros.create', compact('veiculos', 'seguradoras'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->temPermissao('SEG002')) {
            return redirect()->route('seguros.index')->with('error', 'O usuário não possuí permissão para criar apólices');
        }

        $validated = $request->validate([
            'seg_vei_id' => 'nullable|exists:veiculos,vei_id',
            'seg_for_id' => 'nullable|exists:fornecedores,for_id',
            'seg_numero' => 'required|string|max:255',
            'seg_inicio' => 'nullable|date',
            'seg_fim' => 'nullable|date',
            'seg_valor_total' => 'nullable|numeric',
            'seg_parcelas' => 'nullable|integer',
            'seg_franquia' => 'nullable|numeric',
            'seg_tipo' => 'nullable|string',
            'seg_obs' => 'nullable|string',
            'seg_arquivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('seg_arquivo')) {
            if (!Auth::user()->temPermissao('SEG007')) {
                 return back()->withInput()->with('error', 'Você não tem permissão para incluir imagens/arquivos na apólice.');
            }
        }

        $validated['seg_emp_id'] = Auth::user()->emp_id ?? 1; 
        $validated['seg_status'] = 'Ativo'; 

        if ($request->hasFile('seg_arquivo')) {
            $file = $request->file('seg_arquivo');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('seguros_docs', $filename);
            $validated['seg_arquivo'] = $path;
        }

        $apolice = SeguroApolice::create($validated);

        return redirect()->route('seguros.show', $apolice->seg_id)->with('success', 'Apólice criada com sucesso!');
    }

    public function show($id)
    {
        if (!Auth::user()->temPermissao('SEG001')) {
            return redirect()->route('dashboard')->with('error', 'O usuário não possuí permissão à essa tela');
        }

        $apolice = SeguroApolice::with(['coberturas', 'sinistros', 'veiculo', 'fornecedor'])->findOrFail($id);
        
        // Load data needed for sub-forms
        $idEmpresa = Auth::user()->id_empresa;
        $veiculos = Veiculo::where('vei_emp_id', $idEmpresa)->orderBy('vei_placa')->get(); 
        $seguradoras = Fornecedor::where('for_emp_id', $idEmpresa)
            ->where('for_status', 1)
            ->where('for_tipo', 'seguradora')
            ->orderBy('for_nome_fantasia')
            ->get();
        
        return view('seguros.show', compact('apolice', 'veiculos', 'seguradoras'));
    }

    public function edit($id)
    {
        if (!Auth::user()->temPermissao('SEG003')) {
            return redirect()->route('seguros.index')->with('error', 'O usuário não possuí permissão para editar apólices');
        }

        $apolice = SeguroApolice::findOrFail($id);
        $idEmpresa = Auth::user()->id_empresa;
        $veiculos = Veiculo::where('vei_emp_id', $idEmpresa)->orderBy('vei_placa')->get();
        $seguradoras = Fornecedor::where('for_emp_id', $idEmpresa)
            ->where('for_status', 1)
            ->where('for_tipo', 'seguradora')
            ->orderBy('for_nome_fantasia')
            ->get(); 
        
        return view('seguros.edit', compact('apolice', 'veiculos', 'seguradoras'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->temPermissao('SEG003')) {
            return redirect()->route('seguros.index')->with('error', 'O usuário não possuí permissão para editar apólices');
        }

        $apolice = SeguroApolice::findOrFail($id);
        
        $validated = $request->validate([
            'seg_vei_id' => 'nullable|exists:veiculos,vei_id',
            'seg_for_id' => 'nullable|exists:fornecedores,for_id',
            'seg_numero' => 'required|string|max:255',
            'seg_inicio' => 'nullable|date',
            'seg_fim' => 'nullable|date',
            'seg_valor_total' => 'nullable|numeric',
            'seg_parcelas' => 'nullable|integer',
            'seg_franquia' => 'nullable|numeric',
            'seg_tipo' => 'nullable|string',
            'seg_obs' => 'nullable|string',
            'seg_status' => 'nullable|string',
            'seg_arquivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('seg_arquivo')) {
            if (!Auth::user()->temPermissao('SEG007')) {
                 return back()->withInput()->with('error', 'Você não tem permissão para incluir imagens/arquivos na apólice.');
            }
        }

        if ($request->hasFile('seg_arquivo')) {
            // Delete old file if exists
            if ($apolice->seg_arquivo && Storage::exists($apolice->seg_arquivo)) {
                Storage::delete($apolice->seg_arquivo);
            }
            
            $file = $request->file('seg_arquivo');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('seguros_docs', $filename);
            $validated['seg_arquivo'] = $path;
        }

        $apolice->update($validated);

        return redirect()->route('seguros.index')->with('success', 'Apólice atualizada com sucesso!');
    }

    public function destroy($id)
    {
        if (!Auth::user()->temPermissao('SEG004')) {
            return redirect()->route('seguros.index')->with('error', 'O usuário não possuí permissão para excluir apólices');
        }

        $apolice = SeguroApolice::findOrFail($id);
        $apolice->delete();
        
        return redirect()->route('seguros.index')->with('success', 'Apólice excluída com sucesso!');
    }

    public function renew($id)
    {
        if (!Auth::user()->temPermissao('SEG003')) {
            return redirect()->route('seguros.index')->with('error', 'O usuário não possuí permissão para renovar apólices');
        }

        $oldApolice = SeguroApolice::findOrFail($id);
        
        $newApolice = $oldApolice->replicate();
        $newApolice->seg_numero = $oldApolice->seg_numero . '-RENOV'; 
        // Logic for dates: if old end date is known, start new one day after. Otherwise today.
        $startDate = $oldApolice->seg_fim ? \Carbon\Carbon::parse($oldApolice->seg_fim)->addDay() : now();
        $newApolice->seg_inicio = $startDate;
        $newApolice->seg_fim = $startDate->copy()->addYear()->subDay();
        $newApolice->seg_status = 'Em renovação';
        $newApolice->save();
        
        $oldApolice->update(['seg_status' => 'Vencida']);

        return redirect()->route('seguros.edit', $newApolice->seg_id)->with('success', 'Apólice renovada! Verifique os dados.');
    }

    public function download($id)
    {
        if (!Auth::user()->temPermissao('SEG001')) {
            abort(403, 'Acesso negado.');
        }

        $apolice = SeguroApolice::findOrFail($id);

        if ($apolice->seg_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }

        if (!$apolice->seg_arquivo || !Storage::exists($apolice->seg_arquivo)) {
            abort(404, 'Arquivo não encontrado.');
        }

        return Storage::download($apolice->seg_arquivo);
    }
}
