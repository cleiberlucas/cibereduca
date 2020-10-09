<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateRecebimento;
use App\Models\Financeiro\Acrescimo;
use App\Models\Financeiro\Correcao;
use App\Models\Financeiro\Recebimento;
use App\Models\Financeiro\Recebivel;
use App\Models\FormaPagamento;
use App\Models\UnidadeEnsino;
use App\User;

class RecebimentoController extends Controller
{
    private $repositorio;
        
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
}
