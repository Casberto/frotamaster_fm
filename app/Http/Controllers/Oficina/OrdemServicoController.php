<?php

namespace App\Http\Controllers\Oficina;

use App\Http\Controllers\Controller;
use App\Models\Oficina\ClienteOficina;
use App\Models\Oficina\OrdemServico;
use App\Models\Oficina\VeiculoTerceiro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrdemServicoController extends Controller
{
    public function create()
    {
        return view('oficina.os.create');
    }

    public function store(Request $request)
    {
        // Validação básica
        $request->validate([
            'placa' => 'required|string|max:10',
            'problema' => 'nullable|string',
            'telefone' => 'required_if:veiculo_id,null', // Obrigatório se for cliente novo
            'nome_cliente' => 'required_if:veiculo_id,null',
            'modelo_veiculo' => 'required_if:veiculo_id,null',
        ]);

        try {
            DB::beginTransaction();

            $empresaId = Auth::user()->id_empresa;
            $veiculoId = $request->input('veiculo_id');

            // 1. Se não existe veículo ID, cria Cliente e Veículo novos
            if (!$veiculoId) {
                // Cria Cliente
                $cliente = ClienteOficina::create([
                    'clo_emp_id' => $empresaId,
                    'clo_nome' => $request->input('nome_cliente'),
                    'clo_telefone' => $request->input('telefone'),
                    'clo_vip' => false,
                ]);

                // Cria Veículo
                $veiculo = VeiculoTerceiro::create([
                    'vct_clo_id' => $cliente->clo_id,
                    'vct_placa' => strtoupper($request->input('placa')),
                    'vct_modelo' => $request->input('modelo_veiculo'),
                    'vct_marca' => $request->input('marca_veiculo') ?? 'Não Informada',
                    'vct_cor' => $request->input('cor_veiculo'),
                    'vct_combustivel' => $request->input('combustivel'),
                ]);

                $veiculoId = $veiculo->vct_id;
            }

            // 2. Gera Código da OS (Ano + Sequencial)
            $ano = date('Y');
            $ultimo = OrdemServico::where('osv_emp_id', $empresaId)
                ->whereYear('created_at', $ano)
                ->count();
            $codigo = $ano . '-' . str_pad($ultimo + 1, 4, '0', STR_PAD_LEFT);

            // Define status inicial baseado se há relato de problema
            $problema = $request->input('problema');
            $statusInicial = $problema ? 'diagnostico' : 'aguardando';

            // 3. Cria a OS
            $os = OrdemServico::create([
                'osv_emp_id' => $empresaId,
                'osv_vct_id' => $veiculoId,
                'osv_codigo' => $codigo,
                'osv_status' => $statusInicial,
                'osv_prioridade' => $request->input('prioridade', 'normal'),
                'osv_problema_relatado' => $problema ?? 'Aguardando avaliação',
                'osv_gerar_orcamento' => $request->has('gerar_orcamento'),
                'osv_data_entrada' => now(),
                'osv_token_acesso' => (string) Str::uuid(), // Garante UUID
                
                // Checklist simplificado salvo como JSON
                'osv_checklist_entrada' => [
                    'combustivel' => $request->input('nivel_combustivel', 0),
                    'avarias_visiveis' => $request->input('avarias_desc', 'Nenhuma'),
                ]
            ]);
            
            $os->registrarHistorico('OS Criada', 'Ordem de Serviço iniciada.');

            DB::commit();

            return redirect()->route('oficina.painel.index')->with('success', 'OS #' . $codigo . ' criada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao criar OS: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $os = OrdemServico::with(['veiculo.cliente', 'veiculo', 'itens'])->where('osv_emp_id', Auth::user()->id_empresa)->findOrFail($id);
        
        return view('oficina.os.show', compact('os'));
    }

    public function gerarLinkWhatsapp($id)
    {
        $os = OrdemServico::with(['veiculo.cliente', 'veiculo', 'itens'])->findOrFail($id);

        // Link para o cliente aprovar (Rota Pública)
        $linkAprovacao = route('oficina.os.public.show', $os->osv_token_acesso); 

        // TRANSIÇÃO DE STATUS: Diagnóstico -> Aprovação
        if ($os->osv_status == 'diagnostico' && $os->itens()->count() > 0) {
            $os->update(['osv_status' => 'aprovacao']);
        } 

        // Verifica se é aprovação inicial ou adicional
        $pendentes = $os->itens()->where('osi_aprovado', 0)->exists();
        $isAdicional = $os->osv_status == 'aprovado' && $pendentes;

        // Formata a mensagem
        $msg = "*Olá, {$os->veiculo->cliente->clo_nome}!* \n";
        
        if ($isAdicional) {
            $msg .= "⚠️ Encontramos novos detalhes no serviço do seu *{$os->veiculo->vct_modelo}*.\n\n";
            $msg .= "Precisamos da sua aprovação para estes itens adicionais.\n";
            $msg .= "🔧 *Valor Adicional:* R$ " . number_format($os->itens()->where('osi_aprovado', 0)->get()->sum(fn($i) => $i->osi_quantidade * $i->osi_valor_venda_unit), 2, ',', '.') . "\n";
        } else {
            $msg .= "O orçamento do seu *{$os->veiculo->vct_modelo}* está pronto.\n\n";
            $msg .= "🔧 *Total:* R$ " . number_format($os->osv_valor_total, 2, ',', '.') . "\n";
        }
        
        $msg .= "📋 *Veja detalhes e aprove aqui:* \n{$linkAprovacao}\n\n";
        $msg .= "Qualquer dúvida, estamos à disposição!";

        $phone = preg_replace('/\D/', '', $os->veiculo->cliente->clo_telefone);

        $os->registrarHistorico('WhatsApp Enviado', 'Link de orçamento/aprovação enviado ao cliente.');

        return redirect("https://wa.me/55{$phone}?text=" . urlencode($msg));
    }
    public function salvarDiagnostico(Request $request, $id)
    {
        $os = OrdemServico::where('osv_emp_id', Auth::user()->id_empresa)->findOrFail($id);

        $request->validate([
            'diagnostico' => 'required|string|min:10',
        ]);

        $novoStatus = 'diagnostico';
        $msgSucesso = 'Diagnóstico salvo! Agora adicione as peças e serviços.';

        if (!$os->osv_gerar_orcamento) {
            $novoStatus = 'pecas'; // Pula aprovação
            $msgSucesso = 'Diagnóstico salvo! OS foi para "Aguardando Peças" conforme configurado.';
        }

        $os->update([
            'osv_problema_relatado' => $request->input('diagnostico'),
            'osv_status' => $novoStatus
        ]);

        $os->registrarHistorico('Diagnóstico Definido', 'Mecânico registrou o diagnóstico técnico.');

        return redirect()->route('oficina.os.show', $id)->with('success', $msgSucesso);
    }
    public function iniciarExecucao($id)
    {
        $os = OrdemServico::where('osv_emp_id', Auth::user()->id_empresa)->findOrFail($id);
        
        // Verifica se pode transitar
        // Se for garantia Total (item com valor 0), pode ir direto do diagnostico para execucao
        $isGarantiaTotal = ($os->osv_status == 'diagnostico' && $os->osv_pai_id && $os->osv_valor_total == 0);

        if (!in_array($os->osv_status, ['aprovado', 'pecas']) && !$isGarantiaTotal) {
            return back()->with('error', 'Status inválido para iniciar execução.');
        }

        $os->update(['osv_status' => 'execucao']);
        $os->registrarHistorico('Execução Iniciada', 'Mecânico iniciou a execução do serviço.');

        return back()->with('success', 'OS em Execução! Bom trabalho.');
    }

    public function solicitarPecas($id)
    {
        $os = OrdemServico::where('osv_emp_id', Auth::user()->id_empresa)->findOrFail($id);
        
        if ($os->osv_status !== 'aprovado') {
            return back()->with('error', 'Status inválido.');
        }

        $os->update(['osv_status' => 'pecas']);
        $os->registrarHistorico('Compra de Peças', 'Solicitação de compra de peças registrada.');

        return back()->with('success', 'Status alterado para Aguardando Peças. Verifique a Lista de Compras.');
    }

    public function finalizarServico($id)
    {
        $os = OrdemServico::where('osv_emp_id', Auth::user()->id_empresa)->findOrFail($id);
        
        if ($os->osv_status !== 'execucao') {
            return back()->with('error', 'A OS precisa estar em execução para ser finalizada.');
        }

        $os->update(['osv_status' => 'pronto']);
        $os->registrarHistorico('Serviço Finalizado', 'OS marcada como Pronta.');

        return back()->with('success', 'Serviço finalizado! Avise o cliente.');
    }

    public function gerarLinkWhatsappPronto($id)
    {
        $os = OrdemServico::with(['veiculo.cliente'])->findOrFail($id);

        $msg = "*Olá, {$os->veiculo->cliente->clo_nome}!* \n\n";
        $msg .= "O serviço no seu *{$os->veiculo->vct_modelo}* foi finalizado e já está pronto para retirada! 🚗💨\n\n";
        $msg .= "🔧 *Valor Final:* R$ " . number_format($os->osv_valor_total, 2, ',', '.') . "\n\n";
        $msg .= "Aguardamos você!";

        $phone = preg_replace('/\D/', '', $os->veiculo->cliente->clo_telefone);

        return redirect("https://wa.me/55{$phone}?text=" . urlencode($msg));
    }

    public function rejeitarOrcamento($id)
    {
        $os = OrdemServico::where('osv_emp_id', Auth::user()->id_empresa)->findOrFail($id);

        if ($os->osv_status !== 'aprovacao') {
             return back()->with('error', 'Apenas orçamentos em aguardo de aprovação podem ser rejeitados.');
        }

        $os->update(['osv_status' => 'cancelado']);
        $os->registrarHistorico('Orçamento Rejeitado', 'O cliente optou por não realizar o serviço.');

        return back()->with('success', 'OS Cancelada/Rejeitada com sucesso.');
    }

    public function entregarVeiculo(Request $request, $id)
    {
        $os = OrdemServico::where('osv_emp_id', Auth::user()->id_empresa)->findOrFail($id);

        if ($os->osv_status !== 'pronto') {
             return back()->with('error', 'Apenas veículos Prontos podem ser entregues.');
        }

        $request->validate([
            'dias_garantia' => 'nullable|integer|min:0',
            'status_pagamento' => 'required|in:pendente,pago',
            'forma_pagamento' => 'required_if:status_pagamento,pago|in:pix,dinheiro,cartao_credito,cartao_debito,boleto,null',
        ]);

        // Cálculo da Data de Compensação (Previsão de Recebimento)
        $dataCompensacao = now();
        if ($request->status_pagamento === 'pago') {
            switch ($request->forma_pagamento) {
                case 'cartao_credito':
                    $dataCompensacao = now()->addDays(30);
                    break;
                case 'boleto':
                    $dataCompensacao = now()->addDays(2);
                    break;
                case 'cartao_debito':
                    $dataCompensacao = now()->addDays(1);
                    break;
                default: // pix, dinheiro
                    $dataCompensacao = now();
                    break;
            }
        }

        $dados = [
             'osv_status' => 'entregue',
             'osv_data_saida' => now(),
             'osv_dias_garantia' => $request->dias_garantia,
             'osv_status_pagamento' => $request->status_pagamento,
             'osv_forma_pagamento' => $request->forma_pagamento,
             'osv_data_pagamento' => $request->status_pagamento === 'pago' ? now() : null,
             'osv_data_compensacao' => $request->status_pagamento === 'pago' ? $dataCompensacao : null,
        ];

        if($request->dias_garantia > 0) {
            $dados['osv_vencimento_garantia'] = now()->addDays((int) $request->dias_garantia);
        }

        $os->update($dados);
        
        $msgHist = 'OS encerrada e veículo entregue. Garantia: ' . ($request->dias_garantia ?? 0) . ' dias.';
        if ($request->status_pagamento === 'pago') {
            $msgHist .= " Pagamento recebido via " . ucfirst(str_replace('_', ' ', $request->forma_pagamento));
        } else {
            $msgHist .= " Pagamento PENDENTE.";
        }

        $os->registrarHistorico('Veículo Entregue', $msgHist);

        return redirect()->route('oficina.painel.index')->with('success', 'OS Encerrada com sucesso! Veículo entregue.');
    }

    public function acionarGarantia($id)
    {
        $osPai = OrdemServico::where('osv_emp_id', Auth::user()->id_empresa)->findOrFail($id);

        // Validation: Can only trigger warranty if delivered
        if ($osPai->osv_status !== 'entregue') {
            return back()->with('error', 'Garantia só pode ser acionada para OSs entregues.');
        }

        // Create new OS linked to the parent
        $novaOs = OrdemServico::create([
            'osv_emp_id' => $osPai->osv_emp_id,
            'osv_vct_id' => $osPai->osv_vct_id,
            'osv_pai_id' => $osPai->osv_id,
            'osv_codigo' => date('Y') . '-' . str_pad(OrdemServico::where('osv_emp_id', $osPai->osv_emp_id)->whereYear('created_at', date('Y'))->count() + 1, 4, '0', STR_PAD_LEFT),
            'osv_status' => 'aguardando', // Starts as new
            // 'osv_tipo' => 'garantia', // If column existed, we would set it here.
            'osv_problema_relatado' => 'Garantia referente à OS #' . $osPai->osv_codigo,
            'osv_data_entrada' => now(),
            'osv_checklist_entrada' => $osPai->osv_checklist_entrada, // Optional: Copy checklist or leave empty? Leaving copy for context.
        ]);

        $novaOs->registrarHistorico('OS de Garantia Criada', 'OS gerada a partir da garantia da OS #' . $osPai->osv_codigo);

        return redirect()->route('oficina.os.show', $novaOs->osv_id)->with('success', 'OS de Garantia gerada com sucesso! Detalhe o problema.');
    }
}
