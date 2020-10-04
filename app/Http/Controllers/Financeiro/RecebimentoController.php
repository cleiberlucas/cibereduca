<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateRecebimento;
use App\Models\Financeiro\Correcao;
use App\Models\Financeiro\Recebimento;
use App\Models\Financeiro\Recebivel;
use App\Models\FormaPagamento;
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

       $this->repositorio->create($dados);

       return redirect()->route('financeiro.indexAluno', $dados['id_pessoa'])->with('sucesso', 'Recebimento lançado com sucesso.');
    }

    /* public function edit($id_recebivel)
    {
        $this->authorize('Recebível Alterar');   
       
        $recebivel = $this->repositorio
            ->select('descricao_conta', 
                'tipo_turma', 'ano', 
                'parcela', 'valor_principal', 'valor_desconto_principal', 'valor_total', 'data_vencimento', 'data_recebimento', 'obs_recebivel',
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
        
        if ($recebivel->situacao == 2)
            return redirect()->back()->with('atencao', 'Recebível foi pago. Não é possível alterar.');

        if ($recebivel->situacao == 3)
            return redirect()->back()->with('atencao', 'Recebível foi SUBSTITUÍDO/RECALCULADO. Não é possível alterar.');
                
        return view('financeiro.paginas.recebiveis.alunos.edit',
            compact('recebivel')
                       
        );
    } */

    /* public function update(StoreUpdateRecebivel $request, $id)
    {
        $this->authorize('Recebível Alterar');   
        $recebivel = $this->repositorio->where('id_recebivel', $id)->first();

        if (!$recebivel)
            return redirect()->back()->with('erro', 'Recebível não encontrado.');

        $recebivel->where('id_recebivel', $id)->update($request->except('_token', '_method'));

        return redirect()->route('financeiro.indexAluno', $id)->with('sucesso', 'Recebível alterado com sucesso.');
    } */

}
