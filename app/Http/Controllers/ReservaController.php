<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Veiculo;
use App\Models\Motorista;
use App\Models\Fornecedor;
use App\Http\Requests\StoreReservaRequest;
use App\Http\Requests\UpdateReservaRequest;
use App\Services\ReservaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservaController extends Controller
{
    // Constantes de permissão removidas em favor dos códigos textuais 'RESxxx'

    public function __construct(protected ReservaService $reservaService) {}

    public function index(Request $request)
    {
        if (!Auth::user()->temPermissao('RES001')) {
            abort(403, 'Sem permissão para visualizar reservas.');
        }

        $idEmpresa = Auth::user()->id_empresa;
        $query = Reserva::where('res_emp_id', $idEmpresa)->with(['veiculo', 'motorista', 'solicitante']);

        // Filtros
        if ($request->filled('veiculo_id')) { $query->where('res_vei_id', $request->veiculo_id); }
        if ($request->filled('motorista_id')) { $query->where('res_mot_id', $request->motorista_id); }
        if ($request->filled('status')) { $query->where('res_status', $request->status); }
        if ($request->filled('data_inicio')) { $query->where('res_data_inicio', '>=', $request->data_inicio . ' 00:00:00'); }
        if ($request->filled('data_fim')) { $query->where('res_data_fim', '<=', $request->data_fim . ' 23:59:59'); }

        $reservas = $query->latest('res_data_inicio')->paginate(15)->appends($request->query());
        
        // Lógica Master-Detail para Desktop
        $selectedReserva = null;
        if ($request->has('selected_id')) {
            $selectedReserva = Reserva::where('res_emp_id', $idEmpresa)
                ->where('res_id', $request->selected_id)
                ->with([
                    'veiculo', 'motorista', 'solicitante', 'fornecedor', 'revisor', 
                    'pedagios', 'passageiros', 'abastecimentos.fornecedor', 'manutencoes.fornecedor'
                ])
                ->first();
        }

        $dados = $this->getDadosFormulario();
        $statuse = [
            'pendente' => 'Pendente', 'aprovada' => 'Aprovada', 'em_uso' => 'Em Uso', 
            'em_revisao' => 'Em Revisão', 'encerrada' => 'Encerrada', 'rejeitada' => 'Rejeitada', 
            'cancelada' => 'Cancelada', 'pendente_ajuste' => 'Pendente Ajuste'
        ];

        // Verifica permissão gerencial para passar para a view (usado nos detalhes)
        $isGerencial = Auth::user()->temPermissao('RES007');

        return view('reservas.index', compact('reservas', 'statuse', 'selectedReserva', 'isGerencial') + $dados);
    }

    public function create()
    {
        // Verifica permissão de CRIAR RESERVA DE VIAGEM (RES002) ou MANUTENCAO (RES010)
        // Se tiver qualquer um dos dois, acessa, mas a view filtra o tipo de reserva
        if (!Auth::user()->temPermissao('RES002') && !Auth::user()->temPermissao('RES010')) {
            abort(403, 'Sem permissão para criar reservas.');
        }

        $dados = $this->getDadosFormulario();
        $isGerencial = Auth::user()->temPermissao('RES007');
        
        return view('reservas.create', compact('isGerencial') + $dados);
    }

    public function store(StoreReservaRequest $request)
    {
        // Validação adicional de tipo
        if ($request->res_tipo == 'manutencao' && !Auth::user()->temPermissao('RES010')) {
             return abort(403, 'Sem permissão para criar reserva de manutenção.');
        }
        if ($request->res_tipo != 'manutencao' && !Auth::user()->temPermissao('RES002')) {
             return abort(403, 'Sem permissão para criar reserva de viagem.');
        }

        // Autorização já feita no Request via ID 34 - REMOVING THAT DEPENDENCY IS OUTSIDE SCOPE BUT WE OVERRIDE HERE
        
        $conflictErrors = $this->reservaService->verificarConflitos(
            $request->all(), 
            null, 
            $request->boolean('force_create')
        );
        
        if ($conflictErrors) {
            return redirect()->route('reservas.create')->withErrors($conflictErrors)->withInput();
        }

        try {
            $reserva = Reserva::create($request->validated());
            return redirect()->route('reservas.index')->with('success', 'Reserva #' . $reserva->res_codigo . ' criada com sucesso.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao salvar: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Reserva $reserva)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) abort(403);
        if (!Auth::user()->temPermissao('RES001')) abort(403);
        
        $reserva->load(
            'veiculo', 'motorista', 'solicitante', 'fornecedor', 'revisor', 
            'pedagios', 'passageiros', 'abastecimentos.fornecedor', 'manutencoes.fornecedor'
        );
        
        $isGerencial = Auth::user()->temPermissao('RES007');
        
        return view('reservas.show', compact('reserva', 'isGerencial') + $this->getDadosFormulario());
    }

    public function edit(Reserva $reserva)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) abort(403);
        
        if (!Auth::user()->temPermissao('RES003')) {
             abort(403, 'Sem permissão para editar.');
        }

        if (!in_array($reserva->res_status, ['pendente', 'rejeitada', 'pendente_ajuste'])) {
            return redirect()->route('reservas.show', $reserva)
                ->with('error', 'Status atual não permite edição.');
        }

        $isGerencial = Auth::user()->temPermissao('RES007');
        return view('reservas.edit', compact('reserva', 'isGerencial') + $this->getDadosFormulario());
    }

    public function update(UpdateReservaRequest $request, Reserva $reserva)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) abort(403);
        if (!Auth::user()->temPermissao('RES003')) abort(403);

        $conflictErrors = $this->reservaService->verificarConflitos(
            $request->all(), 
            $reserva->res_id, 
            $request->boolean('force_create')
        );

        if ($conflictErrors) {
            return redirect()->route('reservas.edit', $reserva)->withErrors($conflictErrors)->withInput();
        }

        try {
            $reserva->fill($request->validated());
            if (in_array($reserva->res_status, ['rejeitada', 'pendente_ajuste'])) {
                $reserva->res_status = 'pendente';
            }
            $reserva->save();

            return redirect()->route('reservas.index')->with('success', 'Reserva atualizada.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao atualizar: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Reserva $reserva)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) abort(403);
        
        if (!Auth::user()->temPermissao('RES004')) {
            abort(403, 'Sem permissão para excluir.');
        }

        if (!in_array($reserva->res_status, ['pendente', 'rejeitada', 'cancelada'])) {
             return back()->with('error', 'Apenas reservas pendentes, rejeitadas ou canceladas podem ser excluídas.');
        }

        try {
            $reserva->delete();
            return redirect()->route('reservas.index')->with('success', 'Reserva excluída.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro: ' . $e->getMessage());
        }
    }

    private function getDadosFormulario()
    {
        $idEmpresa = Auth::user()->id_empresa;
        return [
            'veiculos' => Veiculo::where('vei_emp_id', $idEmpresa)->where('vei_status', 1)->orderBy('vei_placa')->get(),
            'motoristas' => Motorista::where('mot_emp_id', $idEmpresa)->where('mot_status', 'Ativo')->orderBy('mot_nome')->get(),
            'fornecedores' => Fornecedor::where('for_emp_id', $idEmpresa)->where('for_status', 1)->orderBy('for_nome_fantasia')->get(),
        ];
    }
}