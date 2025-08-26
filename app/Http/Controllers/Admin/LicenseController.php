<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Licenca;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LicenseController extends Controller
{
    public function index()
    {
        $licencas = Licenca::with('empresa')->latest()->paginate(15);
        return view('admin.licencas.index', compact('licencas'));
    }

    public function create()
    {
        $empresas = Empresa::orderBy('nome_fantasia')->get();
        return view('admin.licencas.create', compact('empresas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_empresa' => 'required|exists:empresas,id',
            'plano' => 'required|in:Trial,Mensal,Trimestral,Semestral,Anual',
            'data_inicio' => 'required|date',
            'valor_pago' => 'required|numeric|min:0',
            'status' => 'required|in:ativo,expirado,pendente,cancelado',
        ]);

        $data['id_usuario_criador'] = Auth::id();
        $data['data_vencimento'] = $this->calcularVencimento($data['data_inicio'], $data['plano']);

        Licenca::create($data);

        return redirect()->route('admin.licencas.index')->with('success', 'Licença criada com sucesso.');
    }

    public function edit(Licenca $licenca)
    {
        $empresas = Empresa::orderBy('nome_fantasia')->get();
        return view('admin.licencas.edit', compact('licenca', 'empresas'));
    }

    public function update(Request $request, Licenca $licenca)
    {
        $data = $request->validate([
            'id_empresa' => 'required|exists:empresas,id',
            'plano' => 'required|in:Trial,Mensal,Trimestral,Semestral,Anual',
            'data_inicio' => 'required|date',
            'valor_pago' => 'required|numeric|min:0',
            'status' => 'required|in:ativo,expirado,pendente,cancelado',
        ]);
        
        $data['data_vencimento'] = $this->calcularVencimento($data['data_inicio'], $data['plano']);

        $licenca->update($data);

        return redirect()->route('admin.licencas.index')->with('success', 'Licença atualizada com sucesso.');
    }

    public function destroy(Licenca $licenca)
    {
        $licenca->delete();
        return redirect()->route('admin.licencas.index')->with('success', 'Licença removida com sucesso.');
    }

    private function calcularVencimento($dataInicio, $plano)
    {
        $inicio = Carbon::parse($dataInicio);
        switch ($plano) {
            case 'Trial':
            case 'Mensal':
                return $inicio->addDays(30);
            case 'Trimestral':
                return $inicio->addMonths(3);
            case 'Semestral':
                return $inicio->addMonths(6);
            case 'Anual':
                return $inicio->addYear();
            default:
                return $inicio;
        }
    }
}