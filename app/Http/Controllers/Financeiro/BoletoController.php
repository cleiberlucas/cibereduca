<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateBoleto;
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
use Eduardokum\LaravelBoleto\Boleto\Render\Pdf;
use Eduardokum\LaravelBoleto\Pessoa as LaravelBoletoPessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class BoletoController extends Controller
{
    private $repositorio;
        
    public function __construct(Boleto $boleto)
    {
        $this->repositorio = $boleto;        
    }

    public function indexAluno($id_aluno){

        $aluno = new Pessoa;
        $aluno = $aluno
            ->select('nome', 'id_pessoa')
            ->where('id_pessoa', $id_aluno)
            ->first();

        $boletos = $this->repositorio
            ->select('id_boleto','tb_boletos.data_vencimento',
                'tb_boletos.valor_total',
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
            ->paginate(25)
            ;

        //dd($boletos);

        return view('financeiro.paginas.boletos.index',
            compact('aluno', 'boletos')
        );

    }
    /**
     * Abre interfaace para lançamento de boleto
     */
    public function create($id_aluno){
        $this->authorize('Boleto Lançar');   
       
        $aluno = new Pessoa;
        $aluno = $aluno
            ->select('nome', 'id_pessoa')
            ->where('id_pessoa', $id_aluno)
            ->first();

        if (!$aluno)
            return redirect()->back()->with('erro', 'Aluno não encontrado.');

        /* $dadoBancario = new DadoBancario;
        $dadoBancario = $dadoBancario
            ->select('juros', 'multa', 'juros_apos')
            ->where('fk_id_unidade_ensino', '=', User::getUnidadeEnsinoSelecionada())   
            ->first();

        if (!$dadoBancario)
            return redirect()->back()->with('erro', 'Configurações de multa e juros não cadastradas nos dados bancários da Unidade de Ensinoo.'); */

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
            compact('aluno', 'recebiveis', 'recebiveisVencidos', 'correcoes' )
        );
    }

    //Gravando boletos
    public function store(Request $request)
    {
        $this->authorize('Boleto Cadastrar');
        
        //recebendo array de id_recebivel
        if (!$request->fk_id_recebivel)
            return redirect()->back()->with('atencao', 'Selecione pelo menos 1 recebível.');

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
            return redirect()->back()->with('erro', 'CPF do responsável inválido. Corrigir o cadastro do responsável. Boletos não foram gerados.');
       
        //Agrupando boletos por data de vencimento
        //somando valor principal e valor desconto
        $somasBoletos = DB::table('tb_recebiveis')        
            ->select(DB::raw('data_vencimento'), DB::raw('sum(valor_principal) as valor_principal_total, sum(valor_desconto_principal) as valor_desconto_total'))
            ->groupBy(DB::raw('data_vencimento'))                
            ->whereIn('id_recebivel', $request->fk_id_recebivel)                           
            ->get();

        if(!$somasBoletos)
        return redirect()->back()->with('erro', 'Não foi possível somar os boletos. Favor entrar em contato com a CiberSys.');

        //dd($somasBoletos);

        foreach($somasBoletos as $somaBoleto){

            //gravando recebiveis de um boleto
            //para relacionar boleto X recebiveis
           /*  $dadosRecebivel = new Recebivel;
            $dadosRecebivel = $dadosRecebivel
                ->select('id_recebivel')
                ->whereIn('id_recebivel', $request->fk_id_recebivel)
                ->where('data_vencimento', $somaBoleto->data_vencimento)
                ->get(); */
            //dd($dadosRecebivel);
           
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
                    ->whereIn('id_recebivel', $request->fk_id_recebivel)
                    ->where('data_vencimento', $somaBoleto->data_vencimento)                          
                    ->orderBy('descricao_conta')
                    ->get();
        
            $infoRecebivel = '';
            foreach($recebiveis as $recebivel){
                //lendo recebíveis do boleto para gravar nas instruções do boleto
                $infoRecebivel.= $recebivel->tipo_turma.' '.$recebivel->ano.' - '.$recebivel->descricao_conta.' Parc. '.$recebivel->parcela.' R$ '.number_format($recebivel->valor_principal, 2, ',', '.')." | \r\n";
            }

            $infoDesconto = '';
            if ($somaBoleto->valor_desconto_total > 0)
                $infoDesconto = 'Desconto de R$ '.number_format($somaBoleto->valor_desconto_total, 2, ',', '.').' para pagamento até '.date('d/m/Y', strtotime($somaBoleto->data_vencimento)).'.';

            $infoOutros = 'NÃO PAGÁVEL TESTE TESTE TESTE TESTE. ';
            
            if ($dadoBancario->dias_baixa_automatica > 0)
                $infoOutros.= 'Pagável em até '.$dadoBancario->dias_baixa_automatica.' dias após o vencimento.';

            //Verificando e recalculandoe o vencimento que caiu em feriado ou final de semana
            $vencimento_verificado = $this->recalcularVencimento($somaBoleto->data_vencimento);
            //dd($vencimento_verificado);

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
                'valor_total' => $somaBoleto->valor_principal_total,
                'data_vencimento' => $vencimento_verificado,
                'valor_desconto' => $somaBoleto->valor_desconto_total,
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
        }
        
        //Se estiver vencido
        //$acrescimo = new AcrescimoController(new Acrescimo);
        //Gravando as contas contábeis de acréscimos
        //$gravou_acrescimo = $acrescimo->store($dados);

      /*   if ($gravou_acrescimo){
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
            } */

            return redirect()->route('boleto.indexAluno', $dadosPagador->id_aluno)->with('sucesso', 'Boletos lançados com sucesso.');
            //return redirect()->route('boleto.indexAluno',68)->with('erro', 'Houve erro ao gravar o recebimento. Entre em contato com o desenvolvedor.');
       
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

    
    /*     
        Autor: Wellington Rodrigues -> U r S o L o U c O     
        MSN: ursolouco@msn.com 
        Função: vencimento_calculado()       
        Argumentos: 1 ($data) Deve ser passado no formato YYYY-mm-dd 
        Utilização: Livre - Mantenha os créditos ao Autor     
        Retorno: Data calculada no formato YYYY-mm-dd 
    */
      // Retornar uma array, com os indices dia, mes e ano.
    public function recalcularVencimento($data)
    { 			        
        list($ano, $mes, $dia) = explode("-", $data);   
        $ret = '';        
        
        //vencimento original no sábado - converte p segunda
        if(date("w", mktime(0,0,0, $mes, $dia, $ano)) == "6"){   
            $ret = date("Y-m-d", mktime(0,0,0,$mes, ($dia + 2), $ano));  											
        }       
        //vencimento original no domingo - converte p segunda
        elseif(date("w",mktime(0,0,0, $mes, $dia, $ano)) == "0"){ 
            $ret = date("Y-m-d", mktime(0, 0, 0, $mes, ($dia + 1), $ano)); 							
        }       
        //demais dias mantém
        else{   
            $ret = date("Y-m-d", mktime(0, 0, 0, $mes, $dia, $ano)); 
        }       
        
        $vencimento_tmp = strtotime($ret);
        $ano_tmp = substr($ret, 0,4);
        $mes_tmp = substr($ret, 5,2);
        $dia_tmp = substr($ret, 8,2);   

     // print "vencimento tmp ". date('Y-m-d', $vencimento_tmp);
        //dd($vencimento_tmp);
    
        //verificando se o vencimento é feriado
        foreach($this->dias_feriados($ano_tmp) as $a)
        {                
            //echo date("Y-m-d",$a).'<br>';						 
            //Verificar se o novo vencimento caiu em feriado	
            //print "<br>Feriado ". date($ano_tmp."-m-d",$a);					
            if ( date("Y-m-d",$vencimento_tmp) == date($ano_tmp."-m-d",$a)){
               // print "Encontrou feriado ". date($ano_tmp."-m-d",$a);
                if(date("w", mktime(0,0,0, $mes_tmp, $dia_tmp, $ano_tmp)) == "5"){  //se o feriado for sexta-feira, altera o vencimento original p segunda
                    $dia_tmp = $dia_tmp+3;
                    $ret = date("Y-m-d", mktime(0,0,0,$mes_tmp, $dia_tmp, $ano_tmp));  
                }					
                elseif(date("w", mktime(0,0,0, $mes_tmp, $dia_tmp, $ano_tmp)) >= "1" and date("w", mktime(0,0,0, $mes_tmp, $dia_tmp, $ano_tmp)) <= "4"){  //feriado de segunda a quinta
                    $dia_tmp++;
                    $ret = date("Y-m-d", mktime(0,0,0,$mes_tmp, $dia_tmp, $ano_tmp));  
                }
               
               // break;
            }
            $vencimento_tmp = strtotime($ret);
        }
        
       // $ano_atual = date("Y", time());        
        $vencimento = strtotime($ret); // vencimento nunca � em final de semana
        
        $ano_venc_tmp = date("Y",$vencimento);
        if ( ''.date("Y",$vencimento).date("m",$vencimento).date("d",$vencimento).'' == ''.$ano_venc_tmp."1231"){ // se vencimento = 31/12/anovenc
                
            if(date("w", mktime(0,0,0, 12, 31, $ano_venc_tmp)) == "0" ) // se 31/12/anotual = domingo
                $ret = $ano_venc_tmp."-12-28"; // vence em 28/12/anoatual (quinta-feira)     PORQUE O �LTIMO DIA �TIL DO ANO N�O TEM EXPEDIENTE BANC�RIO
                
            elseif(date("w", mktime(0,0,0, 12, 31, $ano_venc_tmp)) == "1") // se 31/12/anotual = segunda
                $ret = $ano_venc_tmp."-12-28"; // vence em 28/12/anoatual (sexta-feira)     PORQUE O �LTIMO DIA �TIL DO ANO N�O TEM EXPEDIENTE BANC�RIO
                
            elseif(date("w", mktime(0,0,0, 12, 31, $ano_venc_tmp)) >= "2" AND date("w", mktime(0,0,0, 12, 31, $ano_venc_tmp)) <= "5") // se 31/12/anotual = ter�a A SEXTA
                $ret = $ano_venc_tmp."-12-30"; // vence em 30/12/anoatual (dia anterior)     PORQUE O �LTIMO DIA �TIL DO ANO N�O TEM EXPEDIENTE BANC�RIO
            
            elseif(date("w", mktime(0,0,0, 12, 31, $ano_venc_tmp)) == "6") // se 31/12/anotual = s�bado
                $ret = $ano_venc_tmp."-12-29"; // vence em 29/12/anoatual (quinta-feira)    PORQUE O �LTIMO DIA �TIL DO ANO N�O TEM EXPEDIENTE BANC�RIO
        }

       /*  $ret_vetor['ano'] = substr($ret, 0, 4);
        $ret_vetor['mes'] = substr($ret, 5, 2);
        $ret_vetor['dia'] = substr($ret, 8, 2);	 */	

        return $ret;
    }     

    public function dias_feriados($ano = null)
    {
		if ($ano === null){
			$ano = intval(date('Y'));
		}

        $pascoa     = easter_date($ano); // Limite de 1970 ou após 2037 da easter_date PHP consulta http://www.php.net/manual/pt_BR/function.easter-date.php
        //echo "pascoa ".date('Y-m-d', $pascoa);
		$dia_pascoa = date('j', $pascoa);
		$mes_pascoa = date('n', $pascoa);
		$ano_pascoa = date('Y', $pascoa);

		$feriados = array(
            // Datas Fixas dos feriados Nacionail Basileiras
            mktime(0, 0, 0, 1,  1,   $ano), // Confraternização Universal - Lei nº 662, de 06/04/49
            mktime(0, 0, 0, 4,  21,  $ano), // Tiradentes - Lei nº 662, de 06/04/49
            mktime(0, 0, 0, 5,  1,   $ano), // Dia do Trabalhador - Lei nº 662, de 06/04/49
            mktime(0, 0, 0, 9,  7,   $ano), // Dia da Independência - Lei nº 662, de 06/04/49
            mktime(0, 0, 0, 10,  12, $ano), // N. S. Aparecida - Lei nº 6802, de 30/06/80
            mktime(0, 0, 0, 11,  2,  $ano), // Todos os santos - Lei nº 662, de 06/04/49
            mktime(0, 0, 0, 11, 15,  $ano), // Proclamação da republica - Lei nº 662, de 06/04/49
            mktime(0, 0, 0, 12, 25,  $ano), // Natal - Lei nº 662, de 06/04/49
            
            //mktime(0, 0, 0, 11, 30,  $ano), // Dia do evangélico em Brasília
            mktime(0, 0, 0, 8, 1,  $ano), // Aniversário de Formosa

            // These days have a date depending on easter
            mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 47,  $ano_pascoa),//2ºfeira Carnaval
            mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 46,  $ano_pascoa),//3ºfeira Carnaval	
            mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 2 ,  $ano_pascoa),//6ºfeira Santa  
            mktime(0, 0, 0, $mes_pascoa, $dia_pascoa     ,  $ano_pascoa),//Pascoa
            mktime(0, 0, 0, $mes_pascoa, $dia_pascoa + 60,  $ano_pascoa),//Corpus Christi
		);

        sort($feriados);
                
		return $feriados;
    }

}
