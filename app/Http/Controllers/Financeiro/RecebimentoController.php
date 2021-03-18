<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateRecebimento;
use App\Models\Financeiro\Acrescimo;
use App\Models\Financeiro\Boleto;
use App\Models\Financeiro\Correcao;
use App\Models\Financeiro\Recebimento;
use App\Models\Financeiro\Recebivel;
use App\Models\FormaPagamento;
use App\Models\UnidadeEnsino;
use App\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;

class RecebimentoController extends Controller
{
    private $repositorio;
    private $logProcessaRetorno = '';
    public function __construct(Recebimento $recebimento)
    {
        $this->repositorio = $recebimento;        
        
    }

    /**
     * Abre interfaace para lançamento de recebimento
     */
    public function create($id_recebivel){
        $this->authorize('Recebimento Cadastrar');   
       
        $recebivel = new Recebivel;
       
        $recebivel = $recebivel
            ->select('descricao_conta', 
                'tipo_turma', 'ano', 
                'id_recebivel', 'parcela', 'valor_principal', 'valor_desconto_principal', 'valor_total', 'data_vencimento', 'obs_recebivel',
                'id_pessoa', 'nome')
            ->rightJoin('tb_matriculas', 'fk_id_matricula', 'id_matricula')
            ->join('tb_pessoas', 'id_pessoa', 'fk_id_aluno')
            ->join('tb_turmas', 'fk_id_turma', 'id_turma')
            ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
            ->join('tb_anos_letivos', 'fk_id_ano_letivo', 'id_ano_letivo')
            ->join('tb_contas_contabeis', 'fk_id_conta_contabil_principal', 'id_conta_contabil')
            ->join('tb_tipos_situacao_recebivel', 'fk_id_situacao_recebivel', 'id_situacao_recebivel')
            ->leftJoin('tb_recebimentos', 'fk_id_recebivel', 'id_recebivel')            
            ->where('id_recebivel', $id_recebivel)
            ->first();

        if (!$recebivel)
            return redirect()->back()->with('erro', 'Recebível não encontrado.');
        
       /*  if ($recebivel->situacao == 2)
            return redirect()->back()->with('atencao', 'Recebível já está pago. Não é possível receber novamente.'); */

        if ($recebivel->situacao == 3)
            return redirect()->back()->with('atencao', 'Recebível foi SUBSTITUÍDO/RECALCULADO. Não é possível receber.');
                
        $codigoValidacao = sprintf('%07X', mt_rand(0, 0xFFFFFFF));
        //dd($codigoValidacao);
    
        $formasPagto = new FormaPagamento();
        $formasPagto = $formasPagto->getFormasPagamento();

        $correcoes = new Correcao;
        $correcoes = $correcoes
            ->select('descricao_conta', 'fk_id_conta_contabil',
                'indice_correcao', 'aplica_correcao')
            ->join('tb_contas_contabeis', 'fk_id_conta_contabil', 'id_conta_contabil')
            ->where('situacao', '1')
            ->where('aplica_correcao', '1')
            ->where('fk_id_unidade_ensino', '=', User::getUnidadeEnsinoSelecionada())
            ->get();
        
        return view('financeiro.paginas.recebimentos.create',
            compact('recebivel', 'formasPagto', 'codigoValidacao', 'correcoes')
        );
    }

    //Gravando recebimento
    public function store(StoreUpdateRecebimento $request)
    {
        $this->authorize('Recebimento Cadastrar');
 
        $dados = $request->all();
        
        $acrescimo = new AcrescimoController(new Acrescimo);
        //Gravando as contas contábeis de acréscimos
        $gravou_acrescimo = $acrescimo->store($dados);

        if ($gravou_acrescimo){
            //Gravar recebimentos
            foreach($dados['valor_recebido'] as $index => $valor_recebido){
                if ( $dados['valor_recebido'][$index] > 0){
                    $insert = array(
                        'fk_id_recebivel' => $dados['fk_id_recebivel'],
                        'fk_id_forma_pagamento' => $dados['fk_id_forma_pagamento'][$index],
                        'valor_recebido'    => $dados['valor_recebido'][$index],
                        'data_recebimento'  => $dados['data_recebimento'],
                        'data_credito'      => $dados['data_credito'],
                        'numero_recibo'     => $dados['numero_recibo'],
                        'codigo_validacao'  => $dados['codigo_validacao'],
                        'fk_id_usuario_recebimento' => $dados['fk_id_usuario_recebimento'],
                    );
                    //Gravando recebimentos
                    $this->repositorio->create($insert);
                }                
            }

            $recebivel = new FinanceiroController(new Recebivel);
            //Alterando situação do recebível p RECEBIDO  = 2
            //alterando valor do desconto principal
            //alteranado valor total
            $recebivel->updateSituacaoRecebido($dados['fk_id_recebivel'], $dados['valor_desconto_principal'], $dados['valor_total'], 2);

            return redirect()->route('financeiro.indexAluno', $dados['id_pessoa'])->with('sucesso', 'Recebimento lançado com sucesso.');
        }//fim gravação recebimentos
        else
            return redirect()->route('financeiro.indexAluno', $dados['id_pessoa'])->with('erro', 'Houve erro ao gravar o recebimento. Entre em contato com o desenvolvedor.');
       
    }

    public function recibo($id_recebivel){
        $recebimento = $this->repositorio
            ->select('valor_principal', 'valor_desconto_principal', 'valor_total', 'data_vencimento', 'parcela', 'obs_recebivel',
                'data_recebimento', 'numero_recibo', 'codigo_validacao', 'data_registra_recebimento',
                'conta_receb.descricao_conta as conta_receb_principal',
                'aluno.nome as nome_aluno',
                'resp.nome as nome_resp',
                'nome_turma',
                'name',
                'cnpj', 'tb_unidades_ensino.endereco',
                'ano',
                )           
            ->join('tb_recebiveis', 'tb_recebimentos.fk_id_recebivel', 'id_recebivel')
            ->join('tb_unidades_ensino', 'id_unidade_ensino', 'tb_recebiveis.fk_id_unidade_ensino')
            ->join('tb_contas_contabeis as conta_receb', 'tb_recebiveis.fk_id_conta_contabil_principal', 'conta_receb.id_conta_contabil')                        
            ->join('tb_matriculas', 'fk_id_matricula', 'id_matricula')
            ->join('tb_pessoas as aluno', 'fk_id_aluno', 'aluno.id_pessoa')
            ->join('tb_pessoas as resp', 'fk_id_responsavel', 'resp.id_pessoa')
            ->join('tb_turmas', 'fk_id_turma', 'id_turma')
            ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
            ->join('tb_anos_letivos', 'fk_id_ano_letivo', 'id_ano_letivo')
            ->join('users', 'fk_id_usuario_recebimento', 'id')
            ->where('id_recebivel', $id_recebivel)
            ->where('fk_id_situacao_recebivel', 2)
            ->orderBy('tb_recebiveis.fk_id_conta_contabil_principal')            
            ->get();
        
            //caso o usuário altere o id do recebivel na url e não esteja pago
            if (count($recebimento) == 0)
                return redirect()->back();

        $acrescimos = new Acrescimo;
        $acrescimos = $acrescimos
            ->select('valor_acrescimo', 'valor_desconto_acrescimo', 'valor_total_acrescimo',
                'descricao_conta',)
            ->join('tb_contas_contabeis', 'fk_id_conta_contabil_acrescimo', 'id_conta_contabil')
            ->where('fk_id_recebivel', $id_recebivel)
            ->orderBy('id_conta_contabil')
            ->get();

        $formasPagamento = $this->repositorio
            ->select('valor_recebido', 'forma_pagamento',)
            ->join('tb_formas_pagamento', 'fk_id_forma_pagamento', 'id_forma_pagamento')
            ->where('fk_id_recebivel', $id_recebivel)
            ->get();

        //dd($recebimento);

        return view('financeiro.paginas.recebimentos.recibo', 
            compact('recebimento', 'formasPagamento', 'acrescimos'));

    }

    public function destroy($fk_id_recebivel)
    {
        //Remover recebimento
        $this->authorize('Recebimento Remover');

        $recebimento = $this->repositorio->where('fk_id_recebivel', $fk_id_recebivel)->first();

        if (!$recebimento)
            return redirect()->back()->with('error', 'Recebimento não encontrado.');           

        try {
            $recebimento->where('fk_id_recebivel', $fk_id_recebivel)->delete();

            //Remover acréscimos
            $acrescimos = new AcrescimoController(new Acrescimo);
            $acrescimos->apagarAcrescimo($fk_id_recebivel);
            
            //Voltar recebível p situação 1 = A RECEBER
            $recebivel = new FinanceiroController(new Recebivel);
            $recebivel->updateSituacaoReceber($fk_id_recebivel);

        } catch (QueryException $qe) {
            return redirect()->back()->with('error', 'Não foi possível excluir o recebimento. ');            
        }
        return redirect()->back();
    }

    /**
     * Lançando recebimento de boleto via arquivo retorno
     */
    public function receberBoleto(Array $detalhesRetorno)
    {
        //dd($detalhesRetorno);
        $quebra = chr(13).chr(10);
        $this->logProcessaRetorno = date('d/m/Y H:i:s').' INÍCIO PROCESSAMENTO RETORNO '.$quebra;
        
        $arrayRecebimento = Array(); // para lançar recebimentos
        $arrayRecebivel = Array(); // para atualizar recebiveis
        $arrayAcrescimos = Array(); // para lançar acrescimos - boletos pagos em atraso
        foreach($detalhesRetorno as $indDetalhe => $detalhe){
            if ($indDetalhe == 2){
                //dd($detalhe->numeroDocumento);
                //dd(gettype($detalhe->dataOcorrencia));
                //$dataRecebimento = str_replace('/', '-', $detalhe->dataOcorrencia);
                
                
               // dd($dataRecebimento);
                //$dataRecebimento = DateTime::createFromFormat('d/m/Y', $detalhe->dataOcorrencia);
                //dd(date('Y-m-d', strtotime($dataRecebimento)));
            }

            $recebiveisBoleto = new Boleto;
            //se ocorrencia de pagamento
            if ($detalhe->ocorrencia == '06'){ // liquidação
                //identificar recebiveis de um boleto    
                $recebiveisBoleto = $recebiveisBoleto->getRecebiveisBoleto($detalhe->numeroDocumento);
                //dd($recebiveisBoleto);
               
                $confereValorPago = -1;
                
                /**
                 * lendo os recebiveis e montando array para registrar 
                 *  - recebimentos.
                 *  - atualizar recebivel
                 * */
                foreach($recebiveisBoleto as $index => $recebivel){
                    //dd(array('fk_id_recebivel'=>$recebivel->id_recebivel));

                    //somente no primeiro índice verificar se pagou valor correto
                    if ($index == 0){        
                        //não pagou com multa ou juro
                        if ( ($detalhe->valorMora == null or $detalhe->valorMora == 0) and ($detalhe->valorMulta == null or $detalhe->valorMulta == 0)){
                            $valorCorreto = $recebivel->valor_total_boleto - $recebivel->valor_desconto_boleto;
                            if($detalhe->valorRecebido == $valorCorreto){
                                $confereValorPago = 1;//'Pagou valor correto.';
                            }
                            else if($detalhe->valorRecebido < $valorCorreto){
                                $confereValorPago = 2; // pagou valor menor
                            }
                            else if($detalhe->valorRecebido > $valorCorreto){
                                $confereValorPago = 3; // pagou valor maior
                            }
                        }
                        else{
                            
                            $confereValorPago = 4; //pago com juros
                        }                        
                    }//fim index=0

                    //array para atualizar recebível
                    $arrayRecebivel[$indDetalhe][$index]['id_recebivel'] = $recebivel->id_recebivel;                    
                    //$arrayRecebivel[$indDetalhe][$index]['fk_id_situacao_recebivel'] = 2;
                    //dd($detalhe->dataOcorrencia);
                    /* //if ($index == 1)
                        dd($detalhe->numeroDocumento);
                        dd($detalhe->dataOcorrencia);
                        dd(date('Y-d-m', strtotime($detalhe->dataOcorrencia))); */
                        
                    $dataRecebimento = str_replace('/', '-', $detalhe->dataOcorrencia);
                    $dataRecebimento = date('Y-m-d', strtotime($dataRecebimento));
                    //array para lançar recebimentos
                    $arrayRecebimento[$indDetalhe][$index]['fk_id_recebivel'] = $recebivel->id_recebivel;
                    $arrayRecebimento[$indDetalhe][$index]['fk_id_forma_pagamento'] = 2; // 2 = boleto
                    $arrayRecebimento[$indDetalhe][$index]['data_recebimento'] = $dataRecebimento;
                    $arrayRecebimento[$indDetalhe][$index]['data_credito'] = $dataRecebimento;
                    $arrayRecebimento[$indDetalhe][$index]['numero_recibo'] = (int)$detalhe->numeroDocumento;
                    $arrayRecebimento[$indDetalhe][$index]['codigo_validacao'] = (int)$detalhe->nossoNumero;
                    $arrayRecebimento[$indDetalhe][$index]['fk_id_usuario_recebimento'] = Auth::id();

                     //se pagou com desconto
                     if ($detalhe->valorDesconto > 0 )
                         $arrayRecebimento[$indDetalhe][$index]['valor_recebido'] = $recebivel->valor_total;
                    //se pagou sem desconto
                    else{
                        //se pagou sem juros                        
                        if ( ($detalhe->valorMora == null or $detalhe->valorMora == 0) and ($detalhe->valorMulta == null or $detalhe->valorMulta == 0)){
                            $arrayRecebimento[$indDetalhe][$index]['valor_recebido'] = $recebivel->valor_principal;
                        }
                        //se pagou com juros ou multa
                        //ratear juros e multa entre os recebíveis do boleto
                        else {
                            $valorRecTmp = $recebivel->valor_principal;
                            //se pagou com juros                            
                            if ($detalhe->valorMora > 0){
                                //cálculo rateio valor juro
                                //acrescenta ao valor principal
                                $valorRecTmp += $recebivel->valor_principal / $recebivel->valor_total_boleto * $detalhe->valorMora;          

                                $arrayAcrescimos[$indDetalhe][$index]['juro']['fk_id_recebivel'] = $recebivel->id_recebivel;                                
                                $arrayAcrescimos[$indDetalhe][$index]['juro']['valor_acrescimo'] = $detalhe->valorMora;
                                $arrayAcrescimos[$indDetalhe][$index]['juro']['valor_total_acrescimo'] = $detalhe->valorMora;
                            }
                            //se pagou com multa
                            if ($detalhe->valorMulta > 0){
                                //cálculo rateio multa
                                //acrescenta ao valor principal
                                $valorRecTmp += $recebivel->valor_principal / $recebivel->valor_total_boleto * $detalhe->valorMulta;                                

                                $arrayAcrescimos[$indDetalhe][$index]['multa']['fk_id_recebivel'] = $recebivel->id_recebivel;                                
                                $arrayAcrescimos[$indDetalhe][$index]['multa']['valor_acrescimo'] = $detalhe->valorMulta;
                                $arrayAcrescimos[$indDetalhe][$index]['multa']['valor_total_acrescimo'] = $detalhe->valorMulta;
                            }
                            $arrayRecebimento[$indDetalhe][$index]['valor_recebido'] = $valorRecTmp;
                        }
                    }

                    //somando valor dos recebiveis
                    /* $soma_principal += $recebivel->valor_principal;
                    $soma_desconto += $recebivel->valor_desconto_principal;
                    $soma_total +- $recebivel->valor_total; */
                    //dd(array_push($arrayRecebimento, $arrayRecebimento));
                    
                }//fim foreache recebiveisboleto
                
                if ($confereValorPago == -1){
                    $this->logProcessaRetornolog .= 'Erro ao conferir o valor pago';
                    return $this->logProcessaRetorno;
                }
                else{
                    if ($confereValorPago <= 3){

                    }
                }
            }
        }//fim foreach detalhes
        //dd($arrayRecebimento);

        //lançar o recebimento separado de cada recebível de um boleto
        $lancouRecebimento = $this->storeRecebimentoBoleto($arrayRecebimento);
        //dd($lancouRecebimento);
        
        $atualizouRecebivel = array();
        //Atualizar a situação do recebível para recebido
        if (array_key_exists('ok', $lancouRecebimento)){            
            $recebivel = new FinanceiroController(new Recebivel);
            
            $atualizouRecebivel = $recebivel->updateSituacaoRecebidoBoleto($arrayRecebivel);
           // dd($atualizouRecebivel);
            $this->logProcessaRetorno .= $atualizouRecebivel['ok'];
        }
        else{
            $this->logProcessaRetorno .= $atualizouRecebivel['erro'];
            return $this->logProcessaRetorno;
        }

        $lancouAcrescimo = true;
        //lançar acrescimos caso boleto pago em atraso
        //não preciso atualizar com tabela tb_acrescimos, somente lançar caso o boleto foi pago em atraso
        if (array_key_exists('ok', $atualizouRecebivel)){
            if (count($arrayAcrescimos) > 0){
                $acrescimo = new AcrescimoController(new Acrescimo);
                $lancouAcrescimo = $acrescimo->storeAcrescimosRetornoBoleto($arrayAcrescimos);
                if (!$lancouAcrescimo){
                    $this->logProcessaRetorno .= 'Erro ao lançar os acréscimos. '.$quebra;
                    return array('erro' => $this->logProcessaRetorno);
                }
                else{
                    $this->logProcessaRetorno .= 'Acréscimos lançados com sucesso. '.$quebra;
                    return array('ok' => $this->logProcessaRetorno);
                }
            }
            $this->logProcessaRetorno .= 'Não há acréscimo a ser lançado. '.$quebra;
            return array('ok' => $this->logProcessaRetorno);
        }
        else
            return $atualizouRecebivel;

    }//fim function receber boleto

    /**
     * Lançamento de recebimentos via boleto
     * recebe array de recebimentos
     * retorna array com log do processamento, ou erro
     * */
    private function storeRecebimentoBoleto(Array $arrayRecebimento)
    {
        $quebra = chr(13).chr(10);
        $quant = 0;
        try {
            //code...            
            $this->logProcessaRetorno .= '#### Lançamento recebimentos'.$quebra;
            foreach($arrayRecebimento as $recebimentos)
            {
            //   dd($recebimentos);
                foreach($recebimentos as $receb){
                    $this->logProcessaRetorno .= 'id_recebivel '. $receb['fk_id_recebivel']. ' Data recebimento '.$receb['data_recebimento'].' Valor '.$receb['valor_recebido']. ''.$quebra;
                    //dd($receb);
                    $this->repositorio->create($receb);
                    $quant++;   
                }                             
            }
            $this->logProcessaRetorno .= $quant. ' Recebimentos lançados '.$quebra;
        } catch (\Throwable $th) {
            //throw $th;
            return array('erro' => 'Erro ao lançar recebimento.'.$quebra.$this->logProcessaRetorno);
        }
        return array('ok' => $this->logProcessaRetorno);
    }
}
