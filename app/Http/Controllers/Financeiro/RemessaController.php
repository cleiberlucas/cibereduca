<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\Financeiro\Boleto;
use App\Models\Financeiro\DadoBancario;
use App\Models\Financeiro\Recebivel;
use App\Models\Financeiro\Remessa;
use App\Models\UnidadeEnsino;
use App\User;
use Carbon\Carbon;
use Eduardokum\LaravelBoleto\Boleto\Banco\Bancoob;
use Eduardokum\LaravelBoleto\Cnab\Remessa\Cnab240\Banco\Bancoob as Cnab240BancoBancoob;
use Eduardokum\LaravelBoleto\Cnab\Retorno\Cnab240\Banco\Bancoob as BancoBancoob;
//use Eduardokum\LaravelBoleto\Contracts\Boleto\Boleto as BoletoContract;
use Eduardokum\LaravelBoleto\Pessoa;

class RemessaController extends Controller
{
    
    private $repositorio;
        
    public function __construct(Remessa $remessa)
    {
        $this->repositorio = $remessa;        
    }

    public function index()
    {
        $remessas = $this->repositorio
            ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
            ->join('tb_situacoes_remessa', 'fk_id_situacao_remessa', 'id_situacao_remessa')
            ->orderBy('data_remessa', 'desc')
            ->paginate(25);

        return view('financeiro.paginas.remessas.index',
            compact('remessas'));
    }

    public function gerarRemessaBancoob()
    {        
        //require 'autoload.php';
        $boletosRemessa = new Boleto;
        $boletosRemessa = $boletosRemessa     
            ->select('tb_boletos.*')       
            ->join('tb_dados_bancarios', 'fk_id_dado_bancario', 'id_dado_bancario')
            ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
            ->where('fk_id_situacao_registro', 1)
            ->get();
            
        if (count($boletosRemessa) <= 0)
            return redirect()->back()->with('atencao', 'Não há boleto lançado para geração de remessa.');

        $dadosUnidadeEnsino = new UnidadeEnsino;
        $dadosUnidadeEnsino = $dadosUnidadeEnsino->getUnidadeEnsino(User::getUnidadeEnsinoSelecionada());
    
        $dadoBancario = new DadoBancario;
        $dadoBancario = $dadoBancario
            ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
            ->first();
        
        $beneficiario = new Pessoa; // pessoa do boleto
    
        $beneficiario->setDocumento(mascaraCpfCnpj('##.###.###/####-##', $dadosUnidadeEnsino->cnpj))
            ->setNome($dadosUnidadeEnsino->razao_social)
            ->setCep(mascaraCEP('#####-###', $dadosUnidadeEnsino->cep))
            ->setEndereco($dadosUnidadeEnsino->endereco_boleto)
            ->setBairro($dadosUnidadeEnsino->bairro)
            ->setUf($dadosUnidadeEnsino->uf)
            ->setCidade($dadosUnidadeEnsino->cidade);
            
       

        $remessaBancoob = new Cnab240BancoBancoob(
            [
                'agencia'   => $dadoBancario->agencia,
                'conta'     => $dadoBancario->conta,
                'carteira'  => $dadoBancario->carteira,
                'convenio'  => $dadoBancario->convenio,
                'beneficiario' => $beneficiario,
            ]
        );        

        $boletosBancoob = array();
        $idBoletos = array();

        foreach($boletosRemessa as $indBoleto => $boleto)
        {
            $pagador = new Pessoa; // pessoa do boleto
            $pagador->setDocumento(mascaraCpfCnpj('###.###.###-##', $boleto->cpf_cnpj_pagador))
                ->setNome($boleto->nome_pagador)
                ->setCep(mascaraCEP('#####-###', $boleto->cep_pagador))
                ->setEndereco($boleto->endereco_pagador)
                ->setBairro($boleto->bairro_pagador)
                ->setUf($boleto->uf_pagador)
                ->setCidade($boleto->cidade_pagador);

            $bancoob = new Bancoob;

            $bancoob
                 ->setLogo('vendor/adminlte/dist/img/logo_boleto.png') 
                ->setDataVencimento(Carbon::parse($boleto->data_vencimento))
                ->setDataDocumento(Carbon::parse(date('Y-m-d', strtotime($boleto->data_geracao))))                
                ->setMulta($boleto->multa) // 2%
                ->setJuros($boleto->juros) // 1%               
                ->setJurosApos($boleto->juros_apos) // aplica juros após X dias do vencimento
                ->setValor($boleto->valor_total)
                ->setDesconto($boleto->valor_desconto)                
                ->setDataDesconto(Carbon::parse($boleto->data_desconto))
                ->setNumero($boleto->id_boleto)
                ->setNumeroDocumento($boleto->id_boleto)
                ->setPagador($pagador)
                ->setBeneficiario($beneficiario)
                ->setCarteira($dadoBancario->carteira)
                ->setAgencia($dadoBancario->agencia)
                ->setConvenio($dadoBancario->convenio)
                ->setConta($dadoBancario->conta)
                ->setAceite($dadoBancario->aceite)                
                ->setEspecieDoc($dadoBancario->especie)
                ->setDiasBaixaAutomatica($boleto->dias_baixa_automatica);
           
            $bancoob ->setDescricaoDemonstrativo([$boleto->instrucoes_dados_aluno, $boleto->instrucoes_recebiveis, $boleto->instrucoes_desconto]);

            $bancoob ->setInstrucoes([$boleto->instrucoes_desconto, $boleto->instrucoes_outros, $boleto->instrucoes_multa_juros]);

            $boletosBancoob[$indBoleto] = $bancoob;

            $idBoletos[$indBoleto] = $boleto->id_boleto;
    
        }

        $remessa = $this->repositorio->create(['fk_id_unidade_ensino' => User::getUnidadeEnsinoSelecionada()]);

        $remessaBancoob->setIdremessa($remessa->id_remessa);
       
        $remessaBancoob->addBoletos($boletosBancoob);

        //atualizando a situação dos boletos
        //situacao 2 = boleto em remessa
        $situacaoBoleto = new Boleto;
        $situacaoBoleto->whereIn('id_boleto', $idBoletos)->update(['fk_id_situacao_registro' => 2]);

        $nomeArquivo = 'remessa_bancoob_'.date('YmdHis').'.txt';

        $arquivo = $remessaBancoob->save(__DIR__. DIRECTORY_SEPARATOR . 'arquivos' . DIRECTORY_SEPARATOR . $nomeArquivo);

        $remessaBancoob->download($nomeArquivo);

         //relacionando remessa X boletos na tabela tb_remessas_boletos
        $remessa->remessaBoletos()->attach($idBoletos);

    }

    //Gravando acrescimo
    

}
