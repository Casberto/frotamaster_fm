<?php

namespace App\Http\Controllers;

use App\Models\Perfil;
use App\Models\Permissao;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PerfilController extends Controller
{
    public function index()
    {
        $perfis = Perfil::where('per_emp_id', Auth::user()->id_empresa)->latest()->paginate(15);
        return view('perfis.index', compact('perfis'));
    }

    private function getDadosFormulario()
    {
        $idEmpresa = Auth::user()->id_empresa;
        return [
            'usuarios' => User::where('id_empresa', $idEmpresa)->orderBy('name')->get(),
            'permissoes' => Permissao::orderBy('prm_modulo')->orderBy('prm_acao')->get(),
        ];
    }

    public function create()
    {
        $dados = $this->getDadosFormulario();
        return view('perfis.create', $dados);
    }

    public function store(Request $request)
    {
        $request->validate([
            'per_nome' => 'required|string|max:100',
            'per_descricao' => 'nullable|string|max:255',
            'per_status' => 'required|boolean',
        ]);

        DB::transaction(function () use ($request) {
            $idEmpresa = Auth::user()->id_empresa;

            $perfi = Perfil::create([
                'per_emp_id' => $idEmpresa,
                'per_nome' => $request->per_nome,
                'per_descricao' => $request->per_descricao,
                'per_status' => $request->per_status,
            ]);

            // Prepara os dados para sincronização com o ID da empresa
            $usuariosSyncData = [];
            if ($request->usuarios) {
                foreach ($request->usuarios as $usuarioId) {
                    $usuariosSyncData[$usuarioId] = ['usp_emp_id' => $idEmpresa];
                }
            }
             $perfi->usuarios()->sync($usuariosSyncData);

            $permissoesSyncData = [];
            if ($request->permissoes) {
                foreach ($request->permissoes as $permissaoId) {
                    $permissoesSyncData[$permissaoId] = ['ppr_emp_id' => $idEmpresa];
                }
            }
            $perfi->permissoes()->sync($permissoesSyncData);
        });

        return redirect()->route('perfis.index')->with('success', 'Perfil criado com sucesso.');
    }

    public function edit(Perfil $perfi)
    {
        if ($perfi->per_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }
        $dados = $this->getDadosFormulario();
        $perfi->load('usuarios', 'permissoes');

        return view('perfis.edit', compact('perfi') + $dados);
    }

    public function update(Request $request, Perfil $perfi)
    {
        if ($perfi->per_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }

        $request->validate([
            'per_nome' => 'required|string|max:100',
            'per_descricao' => 'nullable|string|max:255',
            'per_status' => 'required|boolean',
        ]);

        DB::transaction(function () use ($request, $perfi) {
            $idEmpresa = Auth::user()->id_empresa;

            $perfi->update([
                'per_nome' => $request->per_nome,
                'per_descricao' => $request->per_descricao,
                'per_status' => $request->per_status,
            ]);

            // Prepara os dados para sincronização com o ID da empresa
            $usuariosSyncData = [];
            if ($request->usuarios) {
                foreach ($request->usuarios as $usuarioId) {
                    $usuariosSyncData[$usuarioId] = ['usp_emp_id' => $idEmpresa];
                }
            }
            $perfi->usuarios()->sync($usuariosSyncData);

            $permissoesSyncData = [];
            if ($request->permissoes) {
                foreach ($request->permissoes as $permissaoId) {
                    $permissoesSyncData[$permissaoId] = ['ppr_emp_id' => $idEmpresa];
                }
            }
            $perfi->permissoes()->sync($permissoesSyncData);
        });

        return redirect()->route('perfis.index')->with('success', 'Perfil atualizado com sucesso.');
    }

    public function destroy(Perfil $perfi)
    {
        if ($perfi->per_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }
        $perfi->delete();
        return redirect()->route('perfis.index')->with('success', 'Perfil excluído com sucesso.');
    }
}
