<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateRecebivel;
use App\Models\Financeiro\Acrescimo;
use App\Models\Financeiro\ContaContabil;
use App\Models\Financeiro\Recebimento;
use App\Models\Financeiro\Recebivel;
use App\Models\FormaPagamento;
use App\Models\Secretaria\Matricula;
use App\Models\Secretaria\Pessoa;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanceiroController extends Controller
{
    private $repositorio;
        
    public function __construct(Recebivel $recebivel)
    {
        $this->repositorio = $recebivel;        
    }

    /**
    *Todos alunos cadastrados, mesmo que não tenha recebível lançado p ele */
    public function index()
    {
        $this->authorize('Recebível Ver');   

        /* $idUnidade = User::getUnidadeEnsinoSelecionada(); */
        /* $perfilUsuario = new User;        
        $perfilUsuario = $perfilUsuario->getPerfilUsuarioUnidadeEnsino($idUnidade, Auth::id()); */
                
        $pessoas = new Pessoa;

        $pessoas = $pessoas
        ->select('id_pessoa', 'nome', 'situacao_pessoa')
        ->join('tb_unidades_ensino', 'fk_id_unidade_ensino', 'id_unidade_ensino')
        ->where('fk_id_tipo_pessoa', 1)
        ->where('id_unidade_ensino', '=', User::getUnidadeEnsinoSelecionada())
        ->orderBy('nome', 'asc')
        ->paginate(20);

        return view('financeiro.paginas.recebiveis.index', [
            'pessoas' => $pessoas,
        ]); 
    }

    /* Filtrando alunos */
    public function searchAluno(Request $request)
    {
        $filtros = $request->except('_token');      
       // dd(strlen($filtros['filtro']))  ;

        if (isset($filtros['filtro']) and strlen($filtros['filtro']) < 3){
            return back()->with('atencao', 'Informe, no mínimo, 3 caracteres para pesquisar.');
            $filtro = '';
        }
        else if (!isset($filtros['filtro']))
            $filtro = '';
        else
        $filtro = $filtros['filtro'];

        //dd($filtros);
        $pessoas = new Pessoa;

        $pessoas = $pessoas
        ->select('id_pessoa', 'nome', 'situacao_pessoa')
        ->join('tb_unidades_ensino', 'fk_id_unidade_ensino', 'id_unidade_ensino')
        ->where('fk_id_tipo_pessoa', 1)
        ->where('id_unidade_ensino', '=', User::getUnidadeEnsinoSelecionada())
        ->where('nome', 'like', '%'.$filtro.'%')
        ->orderBy('nome', 'asc')
        ->paginate(20);

        return view('financeiro.paginas.recebiveis.index', [
            'pessoas' => $pessoas,
            'filtros' => $filtros,
        ]); 
    }

    /* Filtrando recebíveis de 1 aluno */
    public function search(Request $request)
    {        
        $filtros = $request->except('_token');      
       // dd(strlen($filtros['filtro']))  ;
        
        $filtro = $filtros['filtro'];
        
        $aluno = new Pessoa;
        $aluno = $aluno->select('nome', 'id_pessoa')
            ->where('id_pessoa', $request['id_aluno'])
            ->first();

        $recebiveis = $this->repositorio
            ->select('id_recebivel', 'ano', 'data_vencimento', 'fk_id_conta_contabil_principal', 'parcela',
                'fk_id_situacao_recebivel',
                'descricao_conta', 'tipo_turma', 'valor_total', 'nome')
            ->rightJoin('tb_matriculas', 'fk_id_matricula', 'id_matricula')
            ->join('tb_pessoas', 'id_pessoa', 'fk_id_aluno')
            ->join('tb_turmas', 'fk_id_turma', 'id_turma')
            ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
            ->join('tb_anos_letivos', 'fk_id_ano_letivo', 'id_ano_letivo')
            ->join('tb_contas_contabeis', 'fk_id_conta_contabil_principal', 'id_conta_contabil')
            ->join('tb_tipos_situacao_recebivel', 'fk_id_situacao_recebivel', 'id_situacao_recebivel')            
            ->where('fk_id_aluno', $request['id_aluno'])
            ->where('fk_id_situacao_recebivel', '<=', '3')
            ->where('tb_recebiveis.fk_id_unidade_ensino', '=', User::getUnidadeEnsinoSelecionada())   
            ->where('descricao_conta', 'like', '%'.$filtro.'%')         
            ->orderBy('ano', 'desc')
            ->orderBy('data_vencimento')
            ->orderBy('fk_id_conta_contabil_principal')
            ->orderBy('parcela')
            ->paginate(25);

        return view('financeiro.paginas.recebiveis.alunos.index', 
            compact('aluno', 'recebiveis', 'filtros')
        );
    }

    /**
     * Lista recebíveis de um aluno
     */
    public function indexAluno($id_aluno)
    {
        $this->authorize('Recebível Ver'); 

        $aluno = new Pessoa;
        $aluno = $aluno->select('nome', 'id_pessoa')
            ->where('id_pessoa', $id_aluno)
            ->first();

        $recebiveis = $this->repositorio
            ->select('id_recebivel', 'ano', 'data_vencimento', 'fk_id_conta_contabil_principal', 'parcela',
                'fk_id_situacao_recebivel',
                'descricao_conta', 'tipo_turma', 'valor_total', 'nome')
            ->rightJoin('tb_matriculas', 'fk_id_matricula', 'id_matricula')
            ->join('tb_pessoas', 'id_pessoa', 'fk_id_aluno')
            ->join('tb_turmas', 'fk_id_turma', 'id_turma')
            ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
            ->join('tb_anos_letivos', 'fk_id_ano_letivo', 'id_ano_letivo')
            ->join('tb_contas_contabeis', 'fk_id_conta_contabil_principal', 'id_conta_contabil')
            ->join('tb_tipos_situacao_recebivel', 'fk_id_situacao_recebivel', 'id_situacao_recebivel')            
            ->where('fk_id_aluno', $id_aluno)
            ->where('fk_id_situacao_recebivel', '<=', '3')
            ->where('tb_recebiveis.fk_id_unidade_ensino', '=', User::getUnidadeEnsinoSelecionada())            
            ->orderBy('ano', 'desc')
            ->orderBy('data_vencimento')
            ->orderBy('fk_id_conta_contabil_principal')
            ->orderBy('parcela')
            ->paginate(25);

        return view('financeiro.paginas.recebiveis.alunos.index', 
            compact('aluno', 'recebiveis')
        );
    }

    /**
     * Abre interfaace para lançamento de recebíveis
     */
    public function create($id_aluno){
        $this->authorize('Recebível Cadastrar');

        $aluno = new Pessoa;
        $aluno = $aluno->select('nome', 'id_pessoa')
            ->where('id_pessoa', $id_aluno)
            ->first();

        $matriculas = new Matricula;
        $matriculas = $matriculas
            ->select('tipo_turma', 'id_matricula')
            ->join('tb_turmas', 'fk_id_turma', 'id_turma')
            ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
            ->join('tb_pessoas', 'fk_id_aluno', 'id_pessoa')
            ->where('id_pessoa', $id_aluno)            
            ->get();

        $contasContabeis = new ContaContabil;
        $contasContabeis = $contasContabeis
            ->where('situacao', '1')
            ->where('id_conta_contabil', '<=', 3)
            ->get();

        $unidadeEnsino = User::getUnidadeEnsinoSelecionada();

        return view('financeiro.paginas.recebiveis.alunos.create',
            compact('aluno', 'matriculas', 'contasContabeis', 'unidadeEnsino')
        );
    }

    //Gravando recebível
    public function store(StoreUpdateRecebivel $request)
    {
        $this->authorize('Recebível Cadastrar');

        $dados = $request->all();

       // dd($dados);
       
        foreach($dados['valor_principal'] as $index => $recebivel ){
            //dd($professor);        
            if ($recebivel != null){
                $insert = array(
                    'fk_id_unidade_ensino' => $dados['fk_id_unidade_ensino'],
                    'fk_id_matricula' => $dados['fk_id_matricula'],
                    'fk_id_conta_contabil_principal' => $dados['fk_id_conta_contabil_principal'],
                    'fk_id_usuario_cadastro' => $dados['fk_id_usuario_cadastro'],

                    'valor_principal' => $dados['valor_principal'][$index],
                    'valor_desconto_principal' => $dados['valor_desconto_principal'][$index],
                    'valor_total' => $dados['valor_total'][$index],
                    'data_vencimento' => $dados['data_vencimento'][$index],
                    'parcela' => $dados['parcela'][$index],
                    'obs_recebivel' => $dados['obs_recebivel'][$index],
                    'fk_id_situacao_recebivel' => $dados['fk_id_situacao_recebivel'],
                    
                ); 
               // dd($insert);
                $this->repositorio->create($insert);
            }
        }

        return redirect()->route('financeiro.create', $dados['id_pessoa'] )->with('sucesso', 'Recebível lançado com sucesso.');
    }

    public function edit($id_recebivel)
    {
        $this->authorize('Recebível Alterar');   
       
        $recebivel = $this->repositorio
            ->select('descricao_conta', 
                'tipo_turma', 'ano', 
                'id_recebivel', 'parcela', 'valor_principal', 'valor_desconto_principal', 'valor_total', 'data_vencimento', 'data_recebimento', 'obs_recebivel',
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
    }

    public function update(StoreUpdateRecebivel $request, $id)
    {
        $this->authorize('Recebível Alterar');   
        $recebivel = $this->repositorio->where('id_recebivel', $id)->first();

        if (!$recebivel)
            return redirect()->back()->with('erro', 'Recebível não encontrado.');

        $recebivel->where('id_recebivel', $id)->update($request->except('_token', '_method', 'id_aluno'));

        return redirect()->route('financeiro.indexAluno', $request['id_aluno'])->with('sucesso', 'Recebível alterado com sucesso.');
    }

    //Alterando situação, valor_total e valor_desconto principal do recebível
    public function updateSituacaoRecebido($id_recebivel, $valor_desconto_principal, $valor_total, $situacao)
    {        
        $recebivel = $this->repositorio->where('id_recebivel', $id_recebivel)->first();

        if (!$recebivel)
            return redirect()->back()->with('erro', 'Recebível não encontrado.');

        $situacao_recebivel = Array('fk_id_situacao_recebivel' => $situacao,                                    
                                    'valor_desconto_principal' => $valor_desconto_principal,
                                    'valor_total' => $valor_total);

        $recebivel->where('id_recebivel', $id_recebivel)->update($situacao_recebivel);
    }

    //voltando recebível p situação A RECEBER = 1
    public function updateSituacaoReceber($id_recebivel)
    {        
        $recebivel = $this->repositorio->where('id_recebivel', $id_recebivel)->first();

        if (!$recebivel)
            return redirect()->back()->with('erro', 'Recebível não encontrado.');

        $situacao_recebivel = Array('fk_id_situacao_recebivel' => 1);

        $recebivel->where('id_recebivel', $id_recebivel)->update($situacao_recebivel);
    }

    /* Dados de um recebíuvel */
    public function show($id)
    {
        $autorizado = $this->authorize('Recebível Ver');   
        //dd($autorizado);

        $recebivel = $this->repositorio
            ->select('id_recebivel', 'valor_principal', 'valor_desconto_principal', 'valor_total', 'data_vencimento', 'parcela', 'obs_recebivel', 'tb_recebiveis.data_cadastro',
                'descricao_conta',
                'aluno.nome as nome_aluno', 'aluno.id_pessoa',
                'resp.nome as nome_resp',
                'nome_turma',
                'name',
                'cnpj', 'tb_unidades_ensino.endereco',
                'ano',
                'id_situacao_recebivel', 'situacao_recebivel',
                )                       
            ->join('tb_unidades_ensino', 'id_unidade_ensino', 'tb_recebiveis.fk_id_unidade_ensino')
            ->join('tb_contas_contabeis', 'tb_recebiveis.fk_id_conta_contabil_principal', 'id_conta_contabil')                        
            ->join('tb_matriculas', 'fk_id_matricula', 'id_matricula')
            ->join('tb_pessoas as aluno', 'fk_id_aluno', 'aluno.id_pessoa')
            ->join('tb_pessoas as resp', 'fk_id_responsavel', 'resp.id_pessoa')
            ->join('tb_turmas', 'fk_id_turma', 'id_turma')
            ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
            ->join('tb_anos_letivos', 'fk_id_ano_letivo', 'id_ano_letivo')
            ->join('users', 'fk_id_usuario_cadastro', 'id')
            ->join('tb_tipos_situacao_recebivel', 'fk_id_situacao_recebivel', 'id_situacao_recebivel')
            ->where('id_recebivel', $id)                        
            ->first();

        $recebimentos = new Recebimento;        
        $recebimento = $recebimentos
            ->select('data_recebimento', 'data_credito', 'numero_recibo', 'codigo_validacao', 'data_registra_recebimento',
                'name',)            
            ->join('users', 'fk_id_usuario_recebimento', 'id')
            ->where('fk_id_recebivel', $id)
            ->first();

        $acrescimos = new Acrescimo;
        $acrescimos = $acrescimos
            ->select('valor_acrescimo', 'valor_desconto_acrescimo', 'valor_total_acrescimo',
                'descricao_conta')
            ->join('tb_contas_contabeis', 'fk_id_conta_contabil_acrescimo', 'id_conta_contabil')
            ->where('fk_id_recebivel', $id)
            ->get();

        $formasPagamento = $recebimentos
            ->select('valor_recebido', 'forma_pagamento')            
            ->join('tb_formas_pagamento', 'fk_id_forma_pagamento', 'id_forma_pagamento')
            ->where('fk_id_recebivel', $id)
            ->get();

        if (!$recebivel)
            return redirect()->back()->with('alert', "Recebível não encontrado.");

        return view('financeiro.paginas.recebiveis.alunos.show', 
            compact('recebivel', 'recebimento', 'acrescimos', 'formasPagamento')
        );
    }

    
    public function destroy($id_recebivel)
    {
        //Remover recebimento
        $autorizado = $this->authorize('Recebível Remover');
        //dd($autorizado);

        $recebivel = $this->repositorio->where('id_recebivel', $id_recebivel)->first();

        if (!$recebivel){
            return redirect()->back()->with('error', 'Recebível não encontrado.');  
            return false;
        }                

        try {
            $recebivel->where('id_recebivel', $id_recebivel)->delete();
            return true;     
        } catch (QueryException $qe) {
            //return redirect()->back()->with('error', 'Não foi possível excluir o recebível.');            
            return false;
        }      
    }

}
