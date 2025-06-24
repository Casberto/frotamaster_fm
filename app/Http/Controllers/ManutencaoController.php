<?php

namespace App\Http\Controllers;

use App\Models\Manutencao;
use App\Models\Veiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ManutencaoController extends Controller
{
    // ... os métodos index, create e store permanecem iguais ...
    public function index()
    {
        $idEmpresa = Auth::user()->id_empresa;
        $manutencoes = Manutencao::with('veiculo')
            ->where('id_empresa', $idEmpresa)
            ->latest()
            ->paginate(15);
        
        return view('manutencoes.index', compact('manutencoes'));
    }

    public function create()
    {
        $veiculos = Veiculo::where('id_empresa', Auth::user()->id_empresa)->get();
        return view('manutencoes.create', compact('veiculos'));
    }

    public function store(Request $request)
    {
        $idEmpresa = Auth::user()->id_empresa;

        $validatedData = $request->validate([
            'id_veiculo' => ['required', Rule::exists('veiculos', 'id')->where('id_empresa', $idEmpresa)],
            'tipo_manutencao' => ['required', Rule::in(['preventiva', 'corretiva', 'preditiva', 'outra'])],
            'descricao_servico' => ['required', 'string', 'max:255'],
            'data_manutencao' => ['required', 'date'],
            'quilometragem' => ['required', 'integer'],
            'custo_total' => ['required', 'numeric'],
            'nome_fornecedor' => ['nullable', 'string', 'max:255'],
            'observacoes' => ['nullable', 'string'],
            'proxima_revisao_data' => ['nullable', 'date'],
            'proxima_revisao_km' => ['nullable', 'integer'],
            'status' => ['required', Rule::in(['agendada', 'em_andamento', 'concluida', 'cancelada'])],
        ]);

        $manutencao = new Manutencao($validatedData);
        $manutencao->id_empresa = $idEmpresa;
        $manutencao->save();

        return redirect()->route('manutencoes.index')
                         ->with('success', 'Manutenção registrada com sucesso!');
    }

    // --- CORREÇÃO APLICADA AQUI ---
    // Em vez de receber o objeto Manutencao, recebemos o $id da URL.
    public function edit($id)
    {
        // Buscamos a manutenção manualmente para garantir que todos os dados são carregados.
        $manutencao = Manutencao::findOrFail($id);
        
        // Agora a verificação de segurança funcionará corretamente.
        if ((int)$manutencao->id_empresa !== (int)Auth::user()->id_empresa) {
            abort(403, 'Acesso não autorizado.');
        }

        $veiculos = Veiculo::where('id_empresa', Auth::user()->id_empresa)->get();
        return view('manutencoes.edit', compact('manutencao', 'veiculos'));
    }
    
    // --- CORREÇÃO APLICADA AQUI ---
    // Também ajustamos o método update para receber o $id.
    public function update(Request $request, $id)
    {
        $manutencao = Manutencao::findOrFail($id);

        if ((int)$manutencao->id_empresa !== (int)Auth::user()->id_empresa) {
            abort(403, 'Acesso não autorizado.');
        }

        $idEmpresa = Auth::user()->id_empresa;
        
        $validatedData = $request->validate([
            'id_veiculo' => ['required', Rule::exists('veiculos', 'id')->where('id_empresa', $idEmpresa)],
            'tipo_manutencao' => ['required', Rule::in(['preventiva', 'corretiva', 'preditiva', 'outra'])],
            'descricao_servico' => ['required', 'string', 'max:255'],
            'data_manutencao' => ['required', 'date'],
            'quilometragem' => ['required', 'integer'],
            'custo_total' => ['required', 'numeric'],
            'nome_fornecedor' => ['nullable', 'string', 'max:255'],
            'observacoes' => ['nullable', 'string'],
            'proxima_revisao_data' => ['nullable', 'date'],
            'proxima_revisao_km' => ['nullable', 'integer'],
            'status' => ['required', Rule::in(['agendada', 'em_andamento', 'concluida', 'cancelada'])],
        ]);

        $manutencao->update($validatedData);

        return redirect()->route('manutencoes.index')
                         ->with('success', 'Manutenção atualizada com sucesso!');
    }

    // --- CORREÇÃO APLICADA AQUI ---
    // E o método destroy também.
    public function destroy($id)
    {
        $manutencao = Manutencao::findOrFail($id);

        if ((int)$manutencao->id_empresa !== (int)Auth::user()->id_empresa) {
            abort(403, 'Acesso não autorizado.');
        }
        
        $manutencao->delete();

        return redirect()->route('manutencoes.index')
                         ->with('success', 'Registro de manutenção removido com sucesso!');
    }
}
