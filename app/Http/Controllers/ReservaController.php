<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Veiculo;
use App\Models\Motorista;
use App\Models\Fornecedor;
use App\Models\Abastecimento;
use App\Models\Manutencao;
use App\Models\ReservaPedagio;
use App\Models\ReservaPassageiro;
use App\Http\Requests\StoreReservaRequest;
use App\Http\Requests\UpdateReservaRequest;
use App\Http\Requests\IniciarReservaRequest;
use App\Http\Requests\FinalizarReservaRequest;
use App\Http\Requests\RevisarReservaRequest;
use Illuminate\Http\Request; // Importar Request
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\MessageBag; // Importar

class ReservaController extends Controller
{
    
    /**
     * Função helper para verificar conflitos (Bloqueio ou Aviso).
     * Retorna um MessageBag se houver erro, ou null se estiver OK.
     */
    private function checkConflictos(Request $request, ?int $reservaId = null): ?MessageBag
    {
        $empresaId = Auth::user()->id_empresa;
        $veiculoId = $request->input('res_vei_id');
        $inicio = Carbon::parse($request->input('res_data_inicio'));
        $fim = Carbon::parse($request->input('res_data_fim'));

        if ($request->input('res_dia_todo')) {
            $inicio->startOfDay();
            $fim->endOfDay();
        }

        // 1. VERIFICAÇÃO DE BLOQUEIO (Hard Block)
        $conflitoBloqueio = Reserva::where('res_emp_id', $empresaId)
            ->where('res_vei_id', $veiculoId)
            ->when($reservaId, fn($query) => $query->where('res_id', '!=', $reservaId)) // Ignora a própria reserva na atualização
            ->whereIn('res_status', ['aprovada', 'em_uso'])
            ->where(function ($query) use ($inicio, $fim) {
                $query->where('res_data_inicio', '<', $fim)
                      ->where('res_data_fim', '>', $inicio);
            })
            ->exists();

        if ($conflitoBloqueio) {
            return new MessageBag(['res_vei_id' => 'Não é possível salvar. O veículo já possui uma reserva APROVADA ou EM USO que conflita com este período.']);
        }

        // 2. VERIFICAÇÃO DE AVISO (Soft Warning)
        
        // Se o usuário já confirmou o aviso no modal, o campo 'force_create' virá.
        if ($request->input('force_create')) {
            return null; // Ignora a verificação de 'pendente' e permite salvar.
        }

        $conflitoAviso = Reserva::where('res_emp_id', $empresaId)
            ->where('res_vei_id', $veiculoId)
            ->when($reservaId, fn($query) => $query->where('res_id', '!=', $reservaId)) // Ignora a própria reserva na atualização
            ->where('res_status', 'pendente')
            ->where(function ($query) use ($inicio, $fim) {
                $query->where('res_data_inicio', '<', $fim)
                      ->where('res_data_fim', '>', $inicio);
            })
            ->exists();

        if ($conflitoAviso) {
            // Retorna o erro especial que será capturado pelo modal
            return new MessageBag(['warning_pendente' => 'Atenção: Já existe uma reserva PENDENTE para este veículo em um período conflitante.']);
        }
        
        return null; // Sem conflitos
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReservaRequest $request)
    {
        // 1. Validar regras básicas (já feito pelo StoreReservaRequest)
        $validated = $request->validated();
        
        // 2. Executar a verificação de conflito (Lógica movida para cá)
        $conflictErrors = $this->checkConflictos($request);
        
        if ($conflictErrors) {
            // Se houver conflito (bloqueio ou aviso), retorna à tela de criação com os erros
            return redirect()->route('reservas.create')
                             ->withErrors($conflictErrors)
                             ->withInput(); // Mantém os dados do formulário
        }

        // 3. Se não houver conflitos, cria a reserva
        try {
            $reserva = new Reserva();
            $reserva->fill($validated);
            // created_by, res_emp_id, res_sol_id e res_status='pendente' são definidos pelo Boot do Model Reserva
            $reserva->save();

            return redirect()->route('reservas.index')->with('success', 'Reserva criada com sucesso. Aguardando aprovação.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao salvar a reserva: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReservaRequest $request, Reserva $reserva)
    {
        // 1. Autorização
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }
        
        // 2. Validação de status (só pode editar se estiver pendente, rejeitada ou em ajuste)
        if (!in_array($reserva->res_status, ['pendente', 'rejeitada', 'pendente_ajuste'])) {
            return back()->with('error', 'Não é possível editar uma reserva que já foi aprovada ou está em uso.');
        }

        // 3. Validar regras básicas (já feito pelo UpdateReservaRequest)
        $validated = $request->validated();

        // 4. Executar a verificação de conflito (Lógica movida para cá)
        $conflictErrors = $this->checkConflictos($request, $reserva->res_id);

        if ($conflictErrors) {
            // Se houver conflito (bloqueio ou aviso), retorna à tela de edição com os erros
            return redirect()->route('reservas.edit', $reserva)
                             ->withErrors($conflictErrors)
                             ->withInput(); // Mantém os dados do formulário
        }

        // 5. Se não houver conflitos, atualiza a reserva
        try {
            $reserva->fill($validated);
            // Se estava 'rejeitada' ou 'pendente_ajuste', volta para 'pendente' ao ser editada
            if (in_array($reserva->res_status, ['rejeitada', 'pendente_ajuste'])) {
                $reserva->res_status = 'pendente';
            }
            $reserva->save();

            return redirect()->route('reservas.index')->with('success', 'Reserva atualizada com sucesso.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao atualizar a reserva: ' . $e->getMessage())->withInput();
        }
    }


    // -------------------------------------------------------------------
    // MÉTODOS EXISTENTES (NENHUMA MUDANÇA ABAIXO DESTA LINHA)
    // -------------------------------------------------------------------

    public function index(Request $request) // Adicionado Request
    {
        $idEmpresa = Auth::user()->id_empresa;
        $query = Reserva::where('res_emp_id', $idEmpresa)->with(['veiculo', 'motorista', 'solicitante']);

        // --- INÍCIO: Lógica de Filtro ---
        if ($request->filled('veiculo_id')) {
            $query->where('res_vei_id', $request->veiculo_id);
        }
        if ($request->filled('motorista_id')) {
            $query->where('res_mot_id', $request->motorista_id);
        }
        if ($request->filled('status')) {
            $query->where('res_status', $request->status);
        }
        if ($request->filled('data_inicio')) {
            $query->where('res_data_inicio', '>=', $request->data_inicio . ' 00:00:00');
        }
        if ($request->filled('data_fim')) {
            $query->where('res_data_fim', '<=', $request->data_fim . ' 23:59:59');
        }
        // --- FIM: Lógica de Filtro ---

        $reservas = $query->latest('res_data_inicio')->paginate(15)->appends($request->query()); // Adicionado appends

        // Dados para os dropdowns de filtro
        $veiculos = Veiculo::where('vei_emp_id', $idEmpresa)->orderBy('vei_placa')->get(['vei_id', 'vei_placa', 'vei_modelo']);
        $motoristas = Motorista::where('mot_emp_id', $idEmpresa)->orderBy('mot_nome')->get(['mot_id', 'mot_nome']);
        $statuse = [
            'pendente' => 'Pendente',
            'aprovada' => 'Aprovada',
            'em_uso' => 'Em Uso',
            'em_revisao' => 'Em Revisão',
            'encerrada' => 'Encerrada',
            'rejeitada' => 'Rejeitada',
            'cancelada' => 'Cancelada',
            'pendente_ajuste' => 'Pendente Ajuste',
        ];

        return view('reservas.index', compact('reservas', 'veiculos', 'motoristas', 'statuse'));
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

    public function create()
    {
        $dados = $this->getDadosFormulario();
        return view('reservas.create', $dados);
    }

    public function show(Reserva $reserva)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }

        // Carrega todos os relacionamentos necessários
        $reserva->load(
            'veiculo', 'motorista', 'solicitante', 'fornecedor', 'revisor',
            'pedagios', 'passageiros',
            'abastecimentos.fornecedor', // Carrega o abastecimento E o fornecedor do abastecimento
            'manutencoes.fornecedor'     // Carrega a manutenção E o fornecedor da manutenção
        );

        $dadosFormulario = $this->getDadosFormulario();
        
        return view('reservas.show', compact('reserva') + $dadosFormulario);
    }

    public function edit(Reserva $reserva)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }

        // Validação de status
        if (!in_array($reserva->res_status, ['pendente', 'rejeitada', 'pendente_ajuste'])) {
            return redirect()->route('reservas.show', $reserva)->with('error', 'Esta reserva não pode mais ser editada.');
        }

        $dados = $this->getDadosFormulario();
        return view('reservas.edit', compact('reserva') + $dados);
    }

    public function destroy(Reserva $reserva)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }

        if (!in_array($reserva->res_status, ['pendente', 'rejeitada', 'cancelada'])) {
            return back()->with('error', 'Apenas reservas pendentes, rejeitadas ou canceladas podem ser excluídas.');
        }

        try {
            $reserva->delete();
            return redirect()->route('reservas.index')->with('success', 'Reserva excluída com sucesso.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir a reserva: ' . $e->getMessage());
        }
    }


    // --- AÇÕES DO WORKFLOW ---

    public function aprovar(Reserva $reserva)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }
        
        // TODO: Verificar permissão de perfil (Gestor/Admin)
        
        if ($reserva->res_status !== 'pendente') {
            return back()->with('error', 'Apenas reservas pendentes podem ser aprovadas.');
        }

        // LÓGICA DE CONFLITO AO APROVAR
        $empresaId = $reserva->res_emp_id;
        $veiculoId = $reserva->res_vei_id;
        $inicio = $reserva->res_data_inicio;
        $fim = $reserva->res_data_fim;

        if ($reserva->res_dia_todo) {
            $inicio = $inicio->copy()->startOfDay();
            $fim = $fim->copy()->endOfDay();
        }

        $conflito = Reserva::where('res_emp_id', $empresaId)
            ->where('res_vei_id', $veiculoId)
            ->where('res_id', '!=', $reserva->res_id) // Ignora a própria reserva
            ->whereIn('res_status', ['aprovada', 'em_uso']) // Conflita com outras aprovadas/em_uso
            ->where(function ($query) use ($inicio, $fim) {
                $query->where('res_data_inicio', '<', $fim)
                      ->where('res_data_fim', '>', $inicio);
            })
            ->exists();

        if ($conflito) {
            return back()->with('error', 'Não é possível aprovar. Este veículo já possui uma reserva conflitante (Aprovada ou Em Uso) no mesmo período.');
        }

        // TODO: Iniciar Transação
        $reserva->res_status = 'aprovada';
        $reserva->res_obs = null; // Limpa observações de rejeição/ajuste
        $reserva->save();
        // TODO: Registrar Log de Auditoria
        // TODO: Enviar Notificação
        // TODO: Commit Transação

        return redirect()->route('reservas.show', $reserva)->with('success', 'Reserva aprovada com sucesso.');
    }

    public function rejeitar(Request $request, Reserva $reserva)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }
        // TODO: Permissão de perfil (Gestor/Admin)
        
        if ($reserva->res_status !== 'pendente') {
            return back()->with('error', 'Apenas reservas pendentes podem ser rejeitadas.');
        }

        $request->validate(['observacao' => 'required|string|max:500']);

        // TODO: Transação
        $reserva->res_status = 'rejeitada';
        $reserva->res_obs = $request->input('observacao');
        $reserva->save();
        // TODO: Log e Notificação
        // TODO: Commit

        return redirect()->route('reservas.show', $reserva)->with('success', 'Reserva rejeitada.');
    }

    public function cancelar(Reserva $reserva)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }
        // TODO: Permissão de perfil (Gestor/Admin ou Solicitante)
        
        if (!in_array($reserva->res_status, ['pendente', 'aprovada'])) {
            return back()->with('error', 'Apenas reservas pendentes ou aprovadas podem ser canceladas.');
        }

        // TODO: Transação
        $reserva->res_status = 'cancelada';
        $reserva->res_obs = ($reserva->res_obs ? $reserva->res_obs . ' | ' : '') . 'Cancelada pelo usuário ' . Auth::user()->name . ' em ' . now()->format('d/m/Y H:i');
        $reserva->save();
        // TODO: Log e Notificação
        // TODO: Commit
        
        return redirect()->route('reservas.show', $reserva)->with('success', 'Reserva cancelada.');
    }

    public function iniciar(IniciarReservaRequest $request, Reserva $reserva)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }
        // TODO: Permissão (Motorista da reserva, Gestor, Admin)

        if ($reserva->res_status !== 'aprovada') {
            return back()->with('error', 'Apenas reservas aprovadas podem ser iniciadas.');
        }

        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $reserva->res_km_inicio = $validated['res_km_inicio'];
            $reserva->res_comb_inicio = $validated['res_comb_inicio'];
            $reserva->res_hora_saida = now();
            $reserva->res_status = 'em_uso';
            $reserva->save();

            // Atualiza o KM do veículo
            $veiculo = $reserva->veiculo;
            if ($veiculo && $reserva->res_km_inicio > $veiculo->vei_km_atual) {
                $veiculo->vei_km_atual = $reserva->res_km_inicio;
                $veiculo->save();
            }
            
            // TODO: Log

            DB::commit();
            return redirect()->route('reservas.show', $reserva)->with('success', 'Reserva iniciada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao iniciar a reserva: ' . $e->getMessage());
        }
    }

    public function finalizar(FinalizarReservaRequest $request, Reserva $reserva)
    {
         if ($reserva->res_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }
        // TODO: Permissão (Motorista, Gestor, Admin)

        if ($reserva->res_status !== 'em_uso') {
            return back()->with('error', 'Apenas reservas "Em Uso" podem ser finalizadas.');
        }

        $validated = $request->validated();
        
        DB::beginTransaction();
        try {
            $reserva->fill($validated);
            $reserva->res_status = 'em_revisao';
            $reserva->save();
            
            // Atualiza KM do veículo
            $veiculo = $reserva->veiculo;
            if ($veiculo && $reserva->res_km_fim > $veiculo->vei_km_atual) {
                $veiculo->vei_km_atual = $reserva->res_km_fim;
                $veiculo->save();
            }

            // TODO: Log

            DB::commit();
            return redirect()->route('reservas.show', $reserva)->with('success', 'Reserva finalizada. Aguardando revisão.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao finalizar a reserva: ' . $e->getMessage());
        }
    }

    public function revisar(RevisarReservaRequest $request, Reserva $reserva)
    {
         if ($reserva->res_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }
        // TODO: Permissão (Gestor, Admin)

        if ($reserva->res_status !== 'em_revisao') {
            return back()->with('error', 'Apenas reservas "Em Revisão" podem ser processadas.');
        }

        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $reserva->res_revisor_id = Auth::id();
            $reserva->res_data_revisao = now();
            $reserva->res_obs_revisor = $validated['res_obs_revisor'];

            if ($validated['acao'] === 'encerrar') {
                $reserva->res_status = 'encerrada';
                $message = 'Reserva encerrada com sucesso.';
            } else { // 'devolver'
                $reserva->res_status = 'pendente_ajuste';
                $message = 'Reserva devolvida para ajuste do motorista.';
            }
            
            $reserva->save();
            
            // TODO: Log
            
            DB::commit();
            return redirect()->route('reservas.show', $reserva)->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao processar a revisão: ' . $e->getMessage());
        }
    }


    // --- MÉTODOS DE VÍNCULO (Attach/Detach) ---

    public function attachAbastecimento(Request $request, Reserva $reserva)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) { abort(403); }
        if ($reserva->res_status !== 'em_uso') {
            return back()->with('error', 'Só é possível vincular abastecimentos a reservas "Em Uso".');
        }
        // TODO: Permissão (Motorista, Gestor, Admin)

        $validated = $request->validate([
            'abastecimento_id' => ['required', Rule::exists('abastecimentos', 'aba_id')->where('aba_emp_id', Auth::user()->id_empresa)],
            'forma_pagamento' => 'required|string|max:50',
            'reembolso' => 'required|boolean',
        ]);
        
        try {
            // Verifica se o abastecimento já está vinculado
            if ($reserva->abastecimentos()->where('aba_id', $validated['abastecimento_id'])->exists()) {
                return back()->with('error', 'Este abastecimento já está vinculado a esta reserva.');
            }

            $reserva->abastecimentos()->attach($validated['abastecimento_id'], [
                'rab_mot_id' => $reserva->res_mot_id ?? Auth::id(), // TODO: Verificar se o user é motorista
                'rab_emp_id' => $reserva->res_emp_id,
                'rab_forma_pagto' => $validated['forma_pagamento'],
                'rab_reembolso' => $validated['reembolso'],
                'created_at' => now()
            ]);

            return redirect()->route('reservas.show', $reserva)->with('success', 'Abastecimento vinculado com sucesso.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao vincular abastecimento: ' . $e->getMessage());
        }
    }

    public function detachAbastecimento(Reserva $reserva, Abastecimento $abastecimento)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa || $abastecimento->aba_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }
        // Permite desvincular se 'em_uso' ou 'em_revisao'
        if (!in_array($reserva->res_status, ['em_uso', 'em_revisao'])) {
            return back()->with('error', 'Só é possível desvincular itens em reservas que estão "Em Uso" ou "Em Revisão".');
        }
        
        try {
            $reserva->abastecimentos()->detach($abastecimento->aba_id);
            return redirect()->route('reservas.show', $reserva)->with('success', 'Abastecimento desvinculado.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao desvincular abastecimento: ' . $e->getMessage());
        }
    }

    public function attachPedagio(Request $request, Reserva $reserva)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) { abort(403); }
        if ($reserva->res_status !== 'em_uso') {
            return back()->with('error', 'Só é possível adicionar pedágios a reservas "Em Uso".');
        }
        
        $validated = $request->validate([
            'rpe_data_hora' => 'required|date',
            'rpe_desc' => 'required|string|max:255',
            'rpe_valor' => 'required|numeric|min:0',
            'rpe_forma_pagto' => 'required|string|max:50',
            'rpe_reembolso' => 'required|boolean',
        ]);
        
        try {
            $reserva->pedagios()->create($validated);
            return redirect()->route('reservas.show', $reserva)->with('success', 'Pedágio registrado com sucesso.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao registrar pedágio: ' . $e->getMessage());
        }
    }

    public function detachPedagio(Reserva $reserva, ReservaPedagio $pedagio)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa || $pedagio->rpe_res_id !== $reserva->res_id) {
            abort(403);
        }
        if (!in_array($reserva->res_status, ['em_uso', 'em_revisao'])) {
            return back()->with('error', 'Só é possível remover itens em reservas que estão "Em Uso" ou "Em Revisão".');
        }
        
        try {
            $pedagio->delete();
            return redirect()->route('reservas.show', $reserva)->with('success', 'Pedágio removido.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao remover pedágio: ' . $e->getMessage());
        }
    }

    public function attachPassageiro(Request $request, Reserva $reserva)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) { abort(403); }
        if ($reserva->res_status !== 'em_uso') {
            return back()->with('error', 'Só é possível adicionar passageiros a reservas "Em Uso".');
        }

        $validated = $request->validate([
            'rpa_nome' => 'required|string|max:255',
            'rpa_doc' => 'nullable|string|max:50',
            'rpa_entrou_em' => 'required|string|max:255',
        ]);
        
        try {
            $reserva->passageiros()->create($validated);
            return redirect()->route('reservas.show', $reserva)->with('success', 'Passageiro adicionado com sucesso.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao adicionar passageiro: ' . $e->getMessage());
        }
    }

    public function detachPassageiro(Reserva $reserva, ReservaPassageiro $passageiro)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa || $passageiro->rpa_res_id !== $reserva->res_id) {
            abort(403);
        }
         if (!in_array($reserva->res_status, ['em_uso', 'em_revisao'])) {
            return back()->with('error', 'Só é possível remover itens em reservas que estão "Em Uso" ou "Em Revisão".');
        }
        
        try {
            $passageiro->delete();
            return redirect()->route('reservas.show', $reserva)->with('success', 'Passageiro removido.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao remover passageiro: ' . $e->getMessage());
        }
    }

    public function attachManutencao(Request $request, Reserva $reserva)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) { abort(403); }
        if ($reserva->res_status !== 'em_uso') {
            return back()->with('error', 'Só é possível vincular manutenções a reservas "Em Uso".');
        }
        if ($reserva->res_tipo !== 'manutencao') {
             return back()->with('error', 'Só é possível vincular manutenções a reservas do tipo "Manutenção".');
        }

        $validated = $request->validate([
            'manutencao_id' => ['required', Rule::exists('manutencoes', 'man_id')->where('man_emp_id', Auth::user()->id_empresa)],
        ]);
        
        try {
            if ($reserva->manutencoes()->where('man_id', $validated['manutencao_id'])->exists()) {
                return back()->with('error', 'Esta manutenção já está vinculada a esta reserva.');
            }

            $reserva->manutencoes()->attach($validated['manutencao_id'], [
                'rma_mot_id' => $reserva->res_mot_id ?? Auth::id(),
                'rma_emp_id' => $reserva->res_emp_id,
                'created_at' => now()
            ]);

            return redirect()->route('reservas.show', $reserva)->with('success', 'Manutenção vinculada com sucesso.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao vincular manutenção: ' . $e->getMessage());
        }
    }
    
    public function detachManutencao(Reserva $reserva, Manutencao $manutencao)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa || $manutencao->man_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }
         if (!in_array($reserva->res_status, ['em_uso', 'em_revisao'])) {
            return back()->with('error', 'Só é possível desvincular itens em reservas que estão "Em Uso" ou "Em Revisão".');
        }
        
        try {
            $reserva->manutencoes()->detach($manutencao->man_id);
            return redirect()->route('reservas.show', $reserva)->with('success', 'Manutenção desvinculada.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao desvincular manutenção: ' . $e->getMessage());
        }
    }

}