<?php

namespace App\Http\Controllers\Financeiro\Relatorio;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Financeiro\BoletoController;
use App\Models\AnoLetivo;
use App\Models\Financeiro\Boleto;
use App\Models\Financeiro\DadoBancario;
use App\Models\Secretaria\Matricula;
use App\Models\UnidadeEnsino;
use App\User;
use Carbon\Carbon;
use Eduardokum\LaravelBoleto\Boleto\Banco\Bancoob;
use Eduardokum\LaravelBoleto\Boleto\Render\Html;
use Eduardokum\LaravelBoleto\Pessoa;
use Illuminate\Http\Request;

class BoletoRelatorioController extends Controller
{
    private $repositorio;
        
    public function __construct(Boleto $boleto)
    {
        $this->repositorio = $boleto;        
    }

    public function index()
    {
        $this->authorize('Boleto Ver');   

        $anosLetivos = AnoLetivo::where('fk_id_unidade_ensino', '=', session()->get('id_unidade_ensino'))
            ->orderBy('ano', 'desc')
            ->get();

        return view('financeiro.paginas.boletos.relatorios.index',
            compact('anosLetivos'));
    }

    //Pesquisa boletos da turma selecionada
    public function imprimir(Request $request){
        $boletos = new Matricula;
        $boletos = $boletos
            ->select('id_boleto')
            ->join('tb_recebiveis', 'id_matricula', 'fk_id_matricula')
            ->join('tb_boletos_recebiveis', 'id_recebivel', 'fk_id_recebivel')
            ->join('tb_boletos', 'id_boleto', 'fk_id_boleto')
            ->where('fk_id_turma', $request->turma)
            ->where('fk_id_situacao_registro', '<=', '3')
            ->where('tb_boletos.data_vencimento', '>=', now())
            ->orderBy('instrucoes_dados_aluno')
            ->orderBy('tb_boletos.data_vencimento')
            ->get();        
                
        $dadosUnidadeEnsino = new UnidadeEnsino();
        $dadosUnidadeEnsino = $dadosUnidadeEnsino->getUnidadeEnsino(User::getUnidadeEnsinoSelecionada());

        $dadoBancario = new DadoBancario();
        $dadoBancario = $dadoBancario
            ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
            ->first();
        
        $beneficiario = new Pessoa;

        $beneficiario->setDocumento(mascaraCpfCnpj('##.###.###/####-##', $dadosUnidadeEnsino->cnpj))
            ->setNome($dadosUnidadeEnsino->razao_social)
            ->setCep(mascaraCEP('#####-###', $dadosUnidadeEnsino->cep))
            ->setEndereco($dadosUnidadeEnsino->endereco_boleto)
            ->setBairro($dadosUnidadeEnsino->bairro)
            ->setUf($dadosUnidadeEnsino->uf)
            ->setCidade($dadosUnidadeEnsino->cidade);

        if(count($boletos) < 1)
            return redirect()->back()->with('atencao', 'Não há boleto lançado com os critérios informados.');

        $arrayBoletos = array();
        foreach($boletos as $ind => $boleto){
            $arrayBoletos[$ind] = $boleto->id_boleto;
        }

        $dadosBoletos = $this->repositorio
            ->whereIn('id_boleto', $arrayBoletos)
            ->get();
                
        if(!$dadosBoletos)
            return redirect()->back()->with('erro', 'Boleto não encontrado.');

            $boletosBancoob = array();
        foreach ($dadosBoletos as $indBoleto => $boleto)
        {
            $pagador = new Pessoa;
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
}

?>
