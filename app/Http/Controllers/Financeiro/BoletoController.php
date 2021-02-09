<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\Financeiro\Acrescimo;
use App\Models\Financeiro\Boleto;
use App\Models\Financeiro\BoletoRecebivel;
use App\Models\Financeiro\Correcao;
use App\Models\Financeiro\DadoBancario;
use App\Models\Financeiro\Recebivel;
use App\Models\Secretaria\Pessoa;
use App\Models\UnidadeEnsino;
use App\User;
use App\Utils\CpfValidation;
use Carbon\Carbon;
use Eduardokum\LaravelBoleto\Boleto\Banco\Bancoob;
use Eduardokum\LaravelBoleto\Boleto\Render\Html;
use Eduardokum\LaravelBoleto\Pessoa as LaravelBoletoPessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BoletoController extends Controller
{
    private $repositorio;
        
    public function __construct(Boleto $boleto)
    {
        $this->repositorio = $boleto;        
    }

    public function indexAluno($id_aluno)
    {
        $this->authorize('Boleto Ver');

        $aluno = new Pessoa;
        $aluno = $aluno
            ->select('nome', 'id_pessoa')
            ->where('id_pessoa', $id_aluno)
            ->first();

        $boletos = $this->repositorio
            ->select('id_boleto','tb_boletos.data_vencimento',
                'tb_boletos.valor_total', 'instrucoes_recebiveis',
                'situacao_registro', 'fk_id_situacao_registro',
                'data_recebimento'
                )
            ->join('tb_boletos_recebiveis', 'id_boleto', 'fk_id_boleto')
            ->join('tb_recebiveis', 'tb_boletos_recebiveis.fk_id_recebivel', 'id_recebivel')
            ->leftJoin('tb_recebimentos', 'tb_recebimentos.fk_id_recebivel', 'id_recebivel')
            ->join( 'tb_matriculas', 'tb_recebiveis.fk_id_matricula', 'id_matricula')
            ->join('tb_pessoas', 'fk_id_aluno', 'id_pessoa')
            ->join('tb_situacoes_registro', 'fk_id_situacao_registro', 'id_situacao_registro')
            ->where('fk_id_aluno', $id_aluno)     
            ->where('fk_id_situacao_registro', '!=', 5) /* não listar boletos excluídos */                 
            ->groupBy('tb_boletos.data_vencimento')
            ->groupBy('id_boleto')
            ->groupBy('tb_boletos.valor_total')
            ->groupBy('situacao_registro')
            ->groupBy('fk_id_situacao_registro')
            ->groupBy('data_recebimento')            
            ->paginate(25);

        //dd($boletos);
        return view('financeiro.paginas.boletos.index',
            compact('aluno', 'boletos', )
        );

    }
    /**
     * Abre interface para lançamento de boleto
     */
    public function create($id_aluno){
        $this->authorize('Boleto Cadastrar');   
       
        $aluno = new Pessoa;
        $aluno = $aluno
            ->select('nome', 'id_pessoa')
            ->where('id_pessoa', $id_aluno)
            ->first();

        if (!$aluno)
            return redirect()->back()->with('erro', 'Aluno não encontrado.');

        $dadoBancario = new DadoBancario;
        $dadoBancario = $dadoBancario
            ->select('pagamento_apos_vencimento', 'juros', 'multa', 'juros_apos',
                'protesto', 'instrucao_multa_juros', 'dias_baixa_automatica')
            ->where('fk_id_unidade_ensino', '=', User::getUnidadeEnsinoSelecionada())   
            ->first();

        if (!$dadoBancario)
            return redirect()->back()->with('erro', 'Configurações de multa e juros não cadastradas nos dados bancários da Unidade de Ensinoo.');

        $recebiveis = new Recebivel;            
       
        $recebiveis = $recebiveis
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
            ->where('fk_id_aluno', $id_aluno)
            ->where('fk_id_situacao_recebivel', 1) /* situacao 1 = a receber */
            ->where('data_vencimento', '>=', date('Y-m-d'))
            ->orderBy('ano', 'desc')
            ->orderBy('data_vencimento')
            ->orderBy('descricao_conta')
            ->paginate(25);

        if (!$recebiveis)
            return redirect()->back()->with('erro', 'Recebível não encontrado.');

        $recebiveisVencidos = new Recebivel;
       
        $recebiveisVencidos = $recebiveisVencidos
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
            ->where('fk_id_aluno', $id_aluno)
            ->where('fk_id_situacao_recebivel', 1) /* situacao 1 = a receber */
            ->where('data_vencimento', '<', date('Y-m-d'))
            ->orderBy('ano', 'desc')
            ->orderBy('data_vencimento')
            ->orderBy('descricao_conta')
            ->paginate(25);

        
        $correcoes = new Correcao;
        $correcoes = $correcoes
            ->select('descricao_conta', 'fk_id_conta_contabil',
                'indice_correcao', 'aplica_correcao')
            ->join('tb_contas_contabeis', 'fk_id_conta_contabil', 'id_conta_contabil')
            ->where('situacao', '1')
            ->where('aplica_correcao', '1')
            ->where('fk_id_unidade_ensino', '=', User::getUnidadeEnsinoSelecionada())
            ->get();

        if (!$correcoes)
            return redirect()->back()->with('erro', 'Correções de multa e juros não cadastradas nas configurações da Unidade de Ensinoo.');
        
        return view('financeiro.paginas.boletos.create',
            compact('aluno', 'recebiveis', 'recebiveisVencidos', 'correcoes', 'dadoBancario' )
        );
    }

    //Gravando boletos
    public function store(Request $request)
    {
        //dd($request);
        $this->authorize('Boleto Cadastrar');
        
        //recebendo array de id_recebivel
        if (!$request->fk_id_recebivel)
            return redirect()->back()->with('atencao', 'Selecione pelo menos 1 recebível.');

       // dd($request->fk_id_recebivel);

        $dadoBancario = new DadoBancario;
        $dadoBancario = $dadoBancario
            ->select('id_dado_bancario', 'instrucao_multa_juros', 'juros', 'multa', 'juros_apos', 'dias_baixa_automatica')
            ->where('fk_id_unidade_ensino', '=', User::getUnidadeEnsinoSelecionada())   
            ->first();

        if(!$dadoBancario)
            return redirect()->back()->with('erro', 'Dados bancários não configurados.');

       // dd($dadoBancario);

        $dadosPagador = new Recebivel;
        $dadosPagador = $dadosPagador
            ->select('resp.nome as nome_pagador', 'resp.cpf as cpf_cnpj_pagador',
                'aluno.id_pessoa as id_aluno', 'aluno.nome as nome_aluno',
                'endereco', 'complemento', 'numero as numero_pagador', 'bairro as bairro_pagador', 'cep as cep_pagador',
                'cidade as cidade_pagador', 'sigla as uf_pagador')
            ->join('tb_matriculas', 'fk_id_matricula', 'id_matricula')
            ->join('tb_pessoas as resp', 'fk_id_responsavel', 'resp.id_pessoa')
            ->join('tb_pessoas as aluno', 'fk_id_aluno', 'aluno.id_pessoa')
            ->join('tb_enderecos', 'fk_id_pessoa', 'resp.id_pessoa')
            ->leftJoin('tb_cidades', 'fk_id_cidade', 'id_cidade')
            ->leftJoin('tb_estados', 'fk_id_estado', 'id_estado')
            ->where('id_recebivel', $request->fk_id_recebivel[0])
            ->first();
           // dd($dadosPagador);

        if (!$dadosPagador)
            return redirect()->back()->with('erro', 'Erro ao pesquisar o responsável (pagador). Favor entrar em contato com a CiberSys.');

        if (!$dadosPagador->bairro_pagador)
            return redirect()->back()->with('erro', 'Bairro do responsável não cadastrado. Completar o endereço do responsável.');

        if (!$dadosPagador->cidade_pagador)
            return redirect()->back()->with('erro', 'Cidade do responsável não cadastrada. Completar o endereço do responsável.');

        if (!$dadosPagador->cep_pagador or strlen($dadosPagador->cep_pagador) < 8 or substr($dadosPagador->cep_pagador, 0, 5) == '00000')
            return redirect()->back()->with('erro', 'CEP do responsável não cadastrado ou incompleto. Completar o endereço do responsável.');
        //dd($dadosPagador);

        $validaCpf = new CpfValidation();
        $validaCpf = $validaCpf->validate('', $dadosPagador->cpf_cnpj_pagador, '', '');
        if (!$validaCpf)
            return redirect()->back()->with('erro', 'CPF do responsável inválido. Corrigir o cadastro do responsável. Os boletos não foram gerados.');
       
        //$somasBoletos = array();
        //Agrupando boletos por data de vencimento
        //somando valor principal e valor desconto
        //Para recebíveis A VENCER
        if ($request->forma_geracao == 'boletos_vencimento'){
            $somasBoletos = DB::table('tb_recebiveis')        
                ->select(DB::raw('data_vencimento'), DB::raw('sum(valor_principal) as valor_principal_total, sum(valor_desconto_principal) as valor_desconto_total'))
                ->groupBy(DB::raw('data_vencimento'))                
                ->whereIn('id_recebivel', $request->fk_id_recebivel)                           
                ->get();
                //dd($somasBoletos);
        }
        /* Para recebíveis VENCIDOS */
        else
        {            
            //dd($request->fk_id_recebivel);
            $valor_principal_total = 0;
            $valor_desconto_total = 0;
            $valor_total_multa = 0;
            $valor_total_juro = 0;
            $valor_desconto_multa = 0;
            $valor_desconto_juro = 0;

            $acrescimos = array();
            
            foreach($request->fk_id_recebivel as $indRec => $receb){
                //somando valores dos recebíveis vencidos
                $valor_principal_total+=$request->valor_principal[$indRec];
                $valor_desconto_total+=$request->valor_desconto_principal[$indRec];
                
                //dd($request);
                $valor_multa_var = 'valor_multa'.$indRec;
                $valor_juro_var = 'valor_juro'.$indRec;
                $valor_desconto_multa_var = 'valor_desconto_multa'.$indRec;
                $valor_desconto_juro_var = 'valor_desconto_juro'.$indRec;
                //dd($request->$valor_multa_tmp);

                //somando valores dos acréscimos aos valores do boleto
                $valor_principal_total+=$request->$valor_multa_var; /* multa */
                $valor_principal_total+=$request->$valor_juro_var; /* juros */
                $valor_desconto_total+=$request->$valor_desconto_multa_var; /* desconto multa */
                $valor_desconto_total+=$request->$valor_desconto_juro_var; /* desconto juros */

                //somando valores dos acréscimos para as instruções do boleto
                $valor_total_multa+=$request->$valor_multa_var; /* multa */
                $valor_total_juro+=$request->$valor_juro_var; /* juros */
                $valor_desconto_multa+=$request->$valor_desconto_multa_var; /*desconto multa */
                $valor_desconto_juro+=$request->$valor_desconto_juro_var; /* desconto juros */

                //Montando array para gravar acrescimos no BD
                $acrescimos[$indRec]['id_recebivel'] = $request->fk_id_recebivel[$indRec]; // mesmo recebível p multa e juros

                $acrescimos[$indRec]['conta_multa'] = 4; //4 = multa
                $acrescimos[$indRec]['valor_acrescimo_multa'] = $request->$valor_multa_var;
                $acrescimos[$indRec]['valor_desconto_acrescimo_multa'] = $request->$valor_desconto_multa_var;
                $acrescimos[$indRec]['valor_total_acrescimo_multa'] =  $request->$valor_multa_var - $request->$valor_desconto_multa_var;                

                $acrescimos[$indRec]['conta_juro'] = 5; //5 = juro
                $acrescimos[$indRec]['valor_acrescimo_juro'] = $request->$valor_juro_var;
                $acrescimos[$indRec]['valor_desconto_acrescimo_juro'] = $request->$valor_desconto_juro_var;
                $acrescimos[$indRec]['valor_total_acrescimo_juro'] =  $request->$valor_juro_var - $request->$valor_desconto_juro_var;

            } /* fim for recebiveis */
            //dd($valor_principal_total);
            //dd($valor_desconto_total);            
            
            $somasBoletos[0]['data_vencimento'] = $request->novo_vencimento;
            $somasBoletos[0]['valor_principal_total'] = $valor_principal_total;
            $somasBoletos[0]['valor_desconto_total'] = $valor_desconto_total;
            $somasBoletos = (object)$somasBoletos;
          
            //dd($somasBoletos);
            //dd($receb_temp);
        } /* fim else recebiveis vencidos */

        if(!$somasBoletos)
            return redirect()->back()->with('erro', 'Não foi possível somar os boletos. Favor entrar em contato com a CiberSys.');

        //dd($somasBoletos);

        foreach($somasBoletos as $somaBoleto){
            //dd($somaBoleto->valor_desconto_total);
            //gravando recebiveis de um boleto
            //para relacionar boleto X recebiveis           
           
            $recebiveis = new Recebivel;
            $recebiveis = $recebiveis
                    ->select('descricao_conta', 
                        'tipo_turma', 'ano', 
                        'id_recebivel', 'parcela', 'valor_principal', 'valor_desconto_principal', 'valor_total', 'data_vencimento', 'obs_recebivel',
                        'id_pessoa', 'nome')                    
                    ->Join('tb_matriculas', 'fk_id_matricula', 'id_matricula')
                    ->join('tb_pessoas', 'id_pessoa', 'fk_id_aluno')
                    ->join('tb_turmas', 'fk_id_turma', 'id_turma')
                    ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
                    ->join('tb_anos_letivos', 'fk_id_ano_letivo', 'id_ano_letivo')
                    ->join('tb_contas_contabeis', 'fk_id_conta_contabil_principal', 'id_conta_contabil')
                    ->join('tb_tipos_situacao_recebivel', 'fk_id_situacao_recebivel', 'id_situacao_recebivel')            
                    ->whereIn('id_recebivel', $request->fk_id_recebivel);
            //Verificar vencimento se for recebível A VENCER
            if (isset($somaBoleto->data_vencimento)) 
                $recebiveis = $recebiveis
                    ->where('data_vencimento', $somaBoleto->data_vencimento);

            $recebiveis = $recebiveis
                    ->orderBy('descricao_conta')
                    ->get();

            //dd($recebiveis);

            if (!$recebiveis or count($recebiveis) <= 0)
                return redirect()->back()->with('erro', 'Não foi possível pesquisar os recebíveis. Favor entrar em contato com a CiberSys.');
        
            //Verificando e recalculando o vencimento que caiu em feriado ou final de semana
            $vencimento_verificado = '';
            if (isset($somaBoleto->data_vencimento))//recebível a vencer
                $vencimento_verificado = recalcularVencimento($somaBoleto->data_vencimento);
            else if (isset($somaBoleto['data_vencimento']))//recebível vencido
                $vencimento_verificado = recalcularVencimento($somaBoleto['data_vencimento']);


            $infoRecebivel = '';
            foreach($recebiveis as $recebivel){
                //lendo recebíveis do boleto para gravar nas instruções do boleto
                $infoRecebivel.= $recebivel->tipo_turma.' '.$recebivel->ano.' - '.$recebivel->descricao_conta.' Parc. '.$recebivel->parcela.' R$ '.number_format($recebivel->valor_principal, 2, ',', '.')." # \r\n";
            }

            //se tiver multa
            //incluir nas informações do boleto
            //$valor_total_multa_tmp = $valor_total_multa-$valor_desconto_multa;
            if ($valor_total_multa > 0)
                $infoRecebivel.= 'Multa R$ '.number_format($valor_total_multa, '2', ',', '.').' # ';

            //se tiver juro
            //incluir nas informações do boleto
            //$valor_total_juro_tmp = $valor_total_juro-$valor_desconto_juro;
            if ($valor_total_juro > 0)
                $infoRecebivel.= 'Juros R$ '.number_format($valor_total_juro, '2', ',', '.');

            $infoDesconto = '';
            if ($request->forma_geracao == 'boletos_vencimento' and $somaBoleto->valor_desconto_total > 0)//recebivel a vencer
                $infoDesconto = 'Desconto de R$ '.number_format($somaBoleto->valor_desconto_total, 2, ',', '.').' para pagamento até '.date('d/m/Y', strtotime($vencimento_verificado)).'.';

            if ($request->forma_geracao == 'recebivel_vencido' and $somaBoleto['valor_desconto_total'] > 0)//recebivel vencido
                $infoDesconto = 'Desconto de R$ '.number_format($somaBoleto['valor_desconto_total'], 2, ',', '.').' para pagamento até '.date('d/m/Y', strtotime($vencimento_verificado)).'.';

            $infoOutros = '';
            
            if ($dadoBancario->dias_baixa_automatica > 0)
                $infoOutros.= 'Pagável em até '.$dadoBancario->dias_baixa_automatica.' dias após o vencimento.';
           
            //dd($vencimento_verificado);
            //dd($somaBoleto);

            //gerar array com dados do boleto para gravar
            $boleto = array(
                'fk_id_dado_bancario' => $dadoBancario->id_dado_bancario,
                'nome_pagador' => $dadosPagador->nome_pagador,
                'cpf_cnpj_pagador' => $dadosPagador->cpf_cnpj_pagador,
                'endereco_pagador' => $dadosPagador->endereco.' '.$dadosPagador->complemento.' N° '.$dadosPagador->numero_pagador,
                'bairro_pagador' => $dadosPagador->bairro_pagador,
                'cidade_pagador' => $dadosPagador->cidade_pagador,
                'uf_pagador' => $dadosPagador->uf_pagador,
                'cep_pagador' => $dadosPagador->cep_pagador,
                'valor_total' => isset($somaBoleto->valor_principal_total) ? $somaBoleto->valor_principal_total : $somaBoleto['valor_principal_total'],
                'data_vencimento' => $vencimento_verificado,
                'valor_desconto' => isset($somaBoleto->valor_desconto_total) ? $somaBoleto->valor_desconto_total : $somaBoleto['valor_desconto_total'],
                'data_desconto' => $vencimento_verificado,
                'fk_id_situacao_registro' => 1,
                'fk_id_usuario_cadastro' => Auth::id(),               
                'instrucoes_dados_aluno' => 'Aluno(a): '.$dadosPagador->nome_aluno,
                'instrucoes_recebiveis' => $infoRecebivel,
                'instrucoes_desconto' => $infoDesconto,
                'instrucoes_multa_juros' => $dadoBancario->instrucao_multa_juros,
                'instrucoes_outros' => $infoOutros, 
                'juros' => $dadoBancario->juros,
                'multa' => $dadoBancario->multa,
                'juros_apos' => $dadoBancario->juros_apos,
                'dias_baixa_automatica' => $dadoBancario->dias_baixa_automatica,

            );
            //dd($boleto);

            //Lançando boleto na base
            $boletoGravado = $this->repositorio->create($boleto);           

            foreach ($recebiveis as $recebivel){
                $dadosBoletoRecebivel = array(
                    "fk_id_boleto" => $boletoGravado->id_boleto,
                    "fk_id_recebivel" => $recebivel->id_recebivel,
                );

                $boletoRecebivel = new BoletoRecebivel;
                $boletoRecebivel->create($dadosBoletoRecebivel);
            }

            //lançar acréscimos
            if($request->forma_geracao == 'recebivel_vencido' ){
                if (!$this->storeAcrescimos($acrescimos))
                    return redirect()->back()->with('erro', 'Houve erro ao lançar os acréscimos do boleto. Favor entrar em contato com a CiberSys.');
            }
        }        
        return redirect()->route('boleto.indexAluno', $dadosPagador->id_aluno)->with('sucesso', 'Boletos lançados com sucesso.');
    }

    //salvar os acrescimos dos boletos
    function storeAcrescimos(Array $dados){
        $acrescimo = new AcrescimoController(new Acrescimo());        
        return $acrescimo->storeAcrescimosBoleto($dados);
    }

    /* Impressão de vários boletos (botão) */
   public function imprimirBoletos(Request $boletos)
   {
    $this->authorize('Boleto Ver');
    //dd($boletos->id_boleto);
    $dadosUnidadeEnsino = new UnidadeEnsino;
    $dadosUnidadeEnsino = $dadosUnidadeEnsino->getUnidadeEnsino(User::getUnidadeEnsinoSelecionada());

    $dadoBancario = new DadoBancario;
    $dadoBancario = $dadoBancario
        ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
        ->first();
    
    $beneficiario = new LaravelBoletoPessoa;

    $beneficiario->setDocumento(mascaraCpfCnpj('##.###.###/####-##', $dadosUnidadeEnsino->cnpj))
        ->setNome($dadosUnidadeEnsino->razao_social)
        ->setCep(mascaraCEP('#####-###', $dadosUnidadeEnsino->cep))
        ->setEndereco($dadosUnidadeEnsino->endereco_boleto)
        ->setBairro($dadosUnidadeEnsino->bairro)
        ->setUf($dadosUnidadeEnsino->uf)
        ->setCidade($dadosUnidadeEnsino->cidade);

    //dd($boletos);
    if(!$boletos->id_boleto)
        return redirect()->back()->with('atencao', 'Selecione pelo menos 1 boleto.');

    $dadosBoletos = $this->repositorio
        ->whereIn('id_boleto', $boletos->id_boleto)
        ->get();
    
    if(!$dadosBoletos)
        return redirect()->back()->with('erro', 'Boleto não encontrado.');
    //dd($boletos->id_boleto);       

        $boletosBancoob = array();
       foreach ($dadosBoletos as $indBoleto => $boleto)
       {
           $pagador = new LaravelBoletoPessoa;
            $pagador->setDocumento(mascaraCpfCnpj('###.###.###-##', $boleto->cpf_cnpj_pagador))
                ->setNome($boleto->nome_pagador)
                ->setCep(mascaraCEP('#####-###', $boleto->cep_pagador))
                ->setEndereco($boleto->endereco_pagador.' - '.$boleto->bairro_pagador)                
                ->setUf($boleto->uf_pagador)
                ->setCidade($boleto->cidade_pagador);
           // dd($boleto);
            $bancoob = new Bancoob;

            $bancoob
                ->setLogo('vendor/adminlte/dist/img/logo_boleto.png')
                ->setLocalPagamento($dadoBancario->local_pagamento)
                ->setDataVencimento(Carbon::parse($boleto->data_vencimento))
                ->setDataDocumento(Carbon::parse(date('Y-m-d', strtotime($boleto->data_geracao))))                
                ->setValor($boleto->valor_total)
                ->setDesconto($boleto->valor_desconto)
                ->setDataDesconto(Carbon::parse(date('Y-m-d', strtotime($boleto->data_desconto))))
                ->setNumero($boleto->id_boleto)
                ->setNumeroDocumento($boleto->id_boleto)
                ->setPagador($pagador)
                ->setBeneficiario($beneficiario)
                ->setCarteira($dadoBancario->carteira)
                ->setAgencia($dadoBancario->agencia)
                ->setConvenio($dadoBancario->convenio)
                ->setConta($dadoBancario->conta)       
                ->setJuros($boleto->juros)      
                ->setMulta($boleto->multa) 
                ->setJurosApos($boleto->juros_apos);
           
            $bancoob ->setDescricaoDemonstrativo([$boleto->instrucoes_dados_aluno, $boleto->instrucoes_recebiveis, $boleto->instrucoes_desconto]);

            $bancoob ->setInstrucoes([$boleto->instrucoes_desconto, $boleto->instrucoes_outros, $boleto->instrucoes_multa_juros]);

            //$bancoob->addDescricaoDemonstrativo('demonstrativo 4');
            
            $boletosBancoob[$indBoleto] = $bancoob;           
       }
       //dd($boletosBancoob);
       $boletoHtml = new Html();

       $boletoHtml->addBoletos($boletosBancoob);

        //$boletoHtml->showPrint();
        $boletoHtml->hideInstrucoes();        
        return $boletoHtml->gerarBoleto();        
   }

   /* Impressão de 1 boleto (link) */
   public function imprimirBoleto($id_boleto)
   {
    $this->authorize('Boleto Ver');
    //dd($boletos->id_boleto);
    $dadosUnidadeEnsino = new UnidadeEnsino;
    $dadosUnidadeEnsino = $dadosUnidadeEnsino->getUnidadeEnsino(User::getUnidadeEnsinoSelecionada());

    $dadoBancario = new DadoBancario;
    $dadoBancario = $dadoBancario
        ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
        ->first();
    
    $beneficiario = new LaravelBoletoPessoa;

    $beneficiario->setDocumento(mascaraCpfCnpj('##.###.###/####-##', $dadosUnidadeEnsino->cnpj))
        ->setNome($dadosUnidadeEnsino->razao_social)
        ->setCep(mascaraCEP('#####-###', $dadosUnidadeEnsino->cep))
        ->setEndereco($dadosUnidadeEnsino->endereco_boleto)
        ->setBairro($dadosUnidadeEnsino->bairro)
        ->setUf($dadosUnidadeEnsino->uf)
        ->setCidade($dadosUnidadeEnsino->cidade);

    $boleto = $this->repositorio
        ->where('id_boleto', $id_boleto)
        ->first();
    //dd($boletos->id_boleto);       

        $boletosBancoob = array();
           $pagador = new LaravelBoletoPessoa;
            $pagador->setDocumento(mascaraCpfCnpj('###.###.###-##', $boleto->cpf_cnpj_pagador))
                ->setNome($boleto->nome_pagador)
                ->setCep(mascaraCEP('#####-###', $boleto->cep_pagador))
                ->setEndereco($boleto->endereco_pagador.' - '.$boleto->bairro_pagador)                          
                ->setUf($boleto->uf_pagador)
                ->setCidade($boleto->cidade_pagador);
            //dd($boleto);
            $bancoob = new Bancoob;

            $bancoob
                ->setLogo('vendor/adminlte/dist/img/logo_boleto.png')
                ->setLocalPagamento($dadoBancario->local_pagamento)
                ->setDataVencimento(Carbon::parse($boleto->data_vencimento))
                ->setDataDocumento(Carbon::parse(date('Y-m-d', strtotime($boleto->data_geracao))))                
                ->setValor($boleto->valor_total)
                ->setDesconto($boleto->valor_desconto)
                ->setDataDesconto(Carbon::parse(date('Y-m-d', strtotime($boleto->data_desconto))))
                ->setNumero($boleto->id_boleto)
                ->setNumeroDocumento($boleto->id_boleto)
                ->setPagador($pagador)
                ->setBeneficiario($beneficiario)
                ->setCarteira($dadoBancario->carteira)
                ->setAgencia($dadoBancario->agencia)
                ->setConvenio($dadoBancario->convenio)
                ->setConta($dadoBancario->conta)
                ->setJuros($boleto->juros)       
                ->setMulta($boleto->multa)
                ->setJurosApos($boleto->juros_apos);
           
            $bancoob ->setDescricaoDemonstrativo([$boleto->instrucoes_dados_aluno, $boleto->instrucoes_recebiveis, $boleto->instrucoes_desconto]);

            $bancoob ->setInstrucoes([$boleto->instrucoes_desconto, $boleto->instrucoes_outros, $boleto->instrucoes_multa_juros]);

            //$bancoob->addDescricaoDemonstrativo('demonstrativo 4');
            
            $boletosBancoob[0] = $bancoob;           
       
       //dd($boletosBancoob);
       $boletoHtml = new Html();

       $boletoHtml->addBoletos($boletosBancoob);

        //$boletoHtml->showPrint();
        $boletoHtml->hideInstrucoes();        
        return $boletoHtml->gerarBoleto();        
   }

   /* Exclusão lógica de boleto */
   public function destroy($id_boleto)
   {
        $this->authorize('Boleto Remover');
        $boletoExclui = $this->repositorio
            ->where('id_boleto', $id_boleto)
            ->first();

        if (!$boletoExclui)
            return redirect()->back()->with('erro', 'Boleto não encontrado para exclusão.');

        try {
            $boletoExclui = $this->repositorio
            ->where('id_boleto', $id_boleto)
            ->where('fk_id_situacao_registro', '!=', '4') /* não permite excluir boleto pago */
            ->update(['fk_id_situacao_registro' => '5']);

        } catch (\Throwable $th) {
             return redirect()->back()->with('erro', 'Não foi possível excluir o boleto.');
        }
        
        return redirect()->back()->with('sucesso', 'Boleto excluído com sucesso.');
   }

    /* public function destroy($fk_id_recebivel)
    {
        //Remover recebimento
        $this->authorize('Recebimento Remover');

        $boleto = $this->repositorio->where('fk_id_recebivel', $fk_id_recebivel)->first();

        if (!$boleto)
            return redirect()->back()->with('error', 'Recebimento não encontrado.');           

        try {
            $boleto->where('fk_id_recebivel', $fk_id_recebivel)->delete();

            //Remover acréscimos
            $acrescimos = new AcrescimoController(new Acrescimo);
            $acrescimos->apagarAcrescimo($fk_id_recebivel);
            
            //Voltar recebível p situação 1 = A RECEBER
            $recebivel = new FinanceiroController(new Boleto);
            $recebivel->updateSituacaoReceber($fk_id_recebivel);

        } catch (QueryException $qe) {
            return redirect()->back()->with('error', 'Não foi possível excluir o recebimento. ');            
        }
        return redirect()->back();

    } */

    
   
}
