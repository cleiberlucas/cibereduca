<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateBoleto;
use App\Models\Financeiro\Boleto;
use App\Models\Financeiro\BoletoRecebivel;
use App\Models\Financeiro\DadoBancario;
use App\Models\Financeiro\Recebivel;
use App\Models\Secretaria\Pessoa;
use App\User;
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

    public function indexAluno($id_aluno){

        $aluno = new Pessoa;
        $aluno = $aluno
            ->select('nome', 'id_pessoa')
            ->where('id_pessoa', $id_aluno)
            ->first();

        $boletos = $this->repositorio
            ->select('tb_boletos.data_vencimento',
                'tb_boletos.valor_total',
                /* 'tb_recebimentos.data_recebimento', */
                )
            ->join('tb_boletos_recebiveis', 'id_boleto', 'fk_id_boleto')
            ->join('tb_recebiveis', 'tb_boletos_recebiveis.fk_id_recebivel', 'id_recebivel')
            ->leftJoin('tb_recebimentos', 'tb_recebimentos.fk_id_recebivel', 'id_recebivel')
            ->join( 'tb_matriculas', 'tb_recebiveis.fk_id_matricula', 'id_matricula')
            ->join('tb_pessoas', 'fk_id_aluno', 'id_pessoa')
            ->where('fk_id_aluno', $id_aluno)            
            /* ->orderBy('id_matricula', 'desc') */
            ->groupBy('tb_boletos.data_vencimento')
            ->groupBy('tb_boletos.valor_total')
            /* ->orderBy('tb_boletos.data_vencimento') */
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
        $this->authorize('Boleto Cadastrar');   
       
        $aluno = new Pessoa;
        $aluno = $aluno
            ->select('nome', 'id_pessoa')
            ->where('id_pessoa', $id_aluno)
            ->first();

        $recebivel = new Recebivel;
       
        $recebiveis = $recebivel
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
            ->where('fk_id_situacao_recebivel', 1)
            ->where('data_vencimento', '>=', date('Y-m-d'))
            ->orderBy('ano', 'desc')
            ->orderBy('data_vencimento')
            ->orderBy('descricao_conta')
            ->paginate(25);

        if (!$recebiveis)
            return redirect()->back()->with('erro', 'Recebível não encontrado.');
        /* 
        $correcoes = new Correcao;
        $correcoes = $correcoes
            ->select('descricao_conta', 'fk_id_conta_contabil',
                'indice_correcao', 'aplica_correcao')
            ->join('tb_contas_contabeis', 'fk_id_conta_contabil', 'id_conta_contabil')
            ->where('situacao', '1')
            ->where('aplica_correcao', '1')
            ->where('fk_id_unidade_ensino', '=', User::getUnidadeEnsinoSelecionada())
            ->get();
         */
        return view('financeiro.paginas.boletos.create',
            compact('aluno', 'recebiveis', )
        );
    }

    //Gravando boletos
    public function store(Request $request)
    {
        $this->authorize('Boleto Cadastrar');
        
        //recebendo array de id_recebivel
        $dados = $request->all();

        $dadoBancario = new DadoBancario;
        $dadoBancario = $dadoBancario
            ->select('id_dado_bancario')
            ->where('fk_id_unidade_ensino', '=', User::getUnidadeEnsinoSelecionada())   
            ->first();

       // dd($dadoBancario);

        $dadosPagador = new Recebivel;
        $dadosPagador = $dadosPagador
            ->select('nome as nome_pagador', 'cpf as cpf_cnpj_pagador',
                'endereco', 'complemento', 'numero as numero_pagador', 'bairro as bairro_pagador', 'cep as cep_pagador',
                'cidade as cidade_pagador', 'sigla as uf_pagador')
            ->join('tb_matriculas', 'fk_id_matricula', 'id_matricula')
            ->join('tb_pessoas', 'fk_id_responsavel', 'id_pessoa')
            ->join('tb_enderecos', 'fk_id_pessoa', 'id_pessoa')
            ->join('tb_cidades', 'fk_id_cidade', 'id_cidade')
            ->join('tb_estados', 'fk_id_estado', 'id_estado')
            ->where('id_recebivel', $request->fk_id_recebivel[0])
            ->first();
       // dd($dadosPagador->nome_pagador);

        //Agrupando boletos por data de vencimento
        //somando valor principal e valor desconto
        $somasBoletos = DB::table('tb_recebiveis')        
            ->select(DB::raw('data_vencimento'), DB::raw('sum(valor_principal) as valor_principal_total, sum(valor_desconto_principal) as valor_desconto_total'))
            ->groupBy(DB::raw('data_vencimento'))                
            ->whereIn('id_recebivel', $request->fk_id_recebivel)                           
            ->get();

        //dd($somasBoletos);

        foreach($somasBoletos as $somaBoleto){
            //gerar array com dados do boleto para gravar
            $boleto = array(
                'fk_id_dado_bancario' => $dadoBancario->id_dado_bancario,
                'nome_pagador' => $dadosPagador->nome_pagador,
                'cpf_cnpj_pagador' => $dadosPagador->cpf_cnpj_pagador,
                'endereco_pagador' => $dadosPagador->endereco.' '.$dadosPagador->complemento,
                'bairro_pagador' => $dadosPagador->bairro_pagador,
                'cidade_pagador' => $dadosPagador->cidade_pagador,
                'uf_pagador' => $dadosPagador->uf_pagador,
                'cep_pagador' => $dadosPagador->cep_pagador,
                'valor_total' => $somaBoleto->valor_principal_total,
                'data_vencimento' => $somaBoleto->data_vencimento,
                'valor_desconto' => $somaBoleto->valor_desconto_total,
                'data_desconto' => $somaBoleto->data_vencimento,
                'fk_id_situacao_registro' => 1,
                'fk_id_usuario_cadastro' => Auth::id(),                

            );
            //dd($boleto);

            //Lançando boleto na base
            $boletoGravado = $this->repositorio->create($boleto);           
            
            //gravando recebiveis de um boleto
            //para relacionar boleto X recebiveis
            $dadosRecebivel = new Recebivel;
            $dadosRecebivel = $dadosRecebivel
                ->select('id_recebivel')
                ->whereIn('id_recebivel', $request->fk_id_recebivel)
                ->where('data_vencimento', $somaBoleto->data_vencimento)
                ->get();
            //dd($dadosRecebivel);

            foreach ($dadosRecebivel as $dadoRecebivel){
                $dadosBoletoRecebivel = array(
                    "fk_id_boleto" => $boletoGravado->id_boleto,
                    "fk_id_recebivel" => $dadoRecebivel->id_recebivel,
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

            return redirect()->route('boleto.indexAluno',68)->with('sucesso', 'Boletos lançados com sucesso.');
            //return redirect()->route('boleto.indexAluno',68)->with('erro', 'Houve erro ao gravar o recebimento. Entre em contato com o desenvolvedor.');
       
    }

   public function imprimirBoleto(array $boletos)
   {
        //$beneficiario = new \Eduardokum\LaravelBoleto\Pessoa;

       foreach ($boletos as $boleto)
       {

       }

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
