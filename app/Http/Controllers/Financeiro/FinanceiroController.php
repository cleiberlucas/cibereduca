<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\Financeiro\ContaContabil;
use App\Models\Financeiro\Recebivel;
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
        $this->authorize('Recebivel Ver');   

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

    /**
     * Lista recebíveis de um aluno
     */
    public function indexAluno($id_aluno)
    {
        $this->authorize('Recebivel Ver'); 

        $aluno = new Pessoa;
        $aluno = $aluno->select('nome', 'id_pessoa')
            ->where('id_pessoa', $id_aluno)
            ->first();

        $recebiveis = $this->repositorio
            ->select('descricao_conta', 'tipo_turma', 'ano', 'parcela', 'valor_principal', 'data_vencimento', 'data_recebimento', 'valor_recebido', 'nome')
            ->rightJoin('tb_matriculas', 'fk_id_matricula', 'id_matricula')
            ->join('tb_pessoas', 'id_pessoa', 'fk_id_aluno')
            ->join('tb_turmas', 'fk_id_turma', 'id_turma')
            ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
            ->join('tb_anos_letivos', 'fk_id_ano_letivo', 'id_ano_letivo')
            ->join('tb_contas_contabeis', 'fk_id_conta_contabil_principal', 'id_conta_contabil')
            ->join('tb_tipos_situacao_recebivel', 'fk_id_situacao_recebivel', 'id_situacao_recebivel')
            ->leftJoin('tb_recebimentos', 'fk_id_recebivel', 'id_recebivel')            
            ->where('fk_id_aluno', $id_aluno)
            ->where('tb_recebiveis.fk_id_unidade_ensino', '=', User::getUnidadeEnsinoSelecionada())
            ->orderBy('ano', 'desc')
            ->orderBy('data_vencimento', 'asc')
            ->paginate(25);

        return view('financeiro.paginas.recebiveis.alunos.index', 
            compact('aluno', 'recebiveis')
        );
    }

    /**
     * Abre interfaace para lançamento de recebíveis
     */
    public function create($id_aluno){
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
            ->get();

        return view('financeiro.paginas.recebiveis.alunos.create',
            compact('aluno', 'matriculas', 'contasContabeis')
        );
    }

}
