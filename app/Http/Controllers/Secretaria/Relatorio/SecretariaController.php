<?php

namespace App\Http\Controllers\Secretaria\Relatorio;

use App\Http\Controllers\Controller;
use App\Models\AnoLetivo;
use App\Models\Secretaria\Matricula;
use App\Models\Secretaria\Pessoa;
use App\Models\Secretaria\SituacaoMatricula;
use App\Models\Secretaria\Turma;
use App\Models\TipoDescontoCurso;
use App\Models\UnidadeEnsino;
use App\User;
use Illuminate\Http\Request;

class SecretariaController extends Controller
{

    /**
     * Página inicial relatórios secretaria
     */
    public function index()
    {
        $this->authorize('Pessoa Ver');

        $situacoesMatriculas = new SituacaoMatricula;
        $situacoesMatriculas = $situacoesMatriculas
            ->orderBy('situacao_matricula')
            ->get();
        
        $tiposDescontoCurso = TipoDescontoCurso::            
            orderBy('tipo_desconto_curso')
            ->get();

        $anosLetivos = AnoLetivo::where('fk_id_unidade_ensino', '=', session()->get('id_unidade_ensino'))
            ->orderBy('ano', 'desc')
            ->get();

        return view('secretaria.paginas.relatorios.index', [
            'anosLetivos' => $anosLetivos,
            'situacoesMatriculas' => $situacoesMatriculas,
            'tiposDescontoCurso' => $tiposDescontoCurso,
        ]);
    }

    /**
     * Verifica escolhas do usuário e gera relatório
     */
    public function filtros(Request $request) 
    {
        $this->authorize('Pessoa Ver');

        $tipoDescontoCurso = '';

        $turma = new Turma;
        $matriculas = new Matricula;
        if ($request->situacaoMatricula != '99') // diferente de 99 é pq escolhe uma situação
            $matriculas = $matriculas->where('tb_matriculas.fk_id_situacao_matricula', $request->situacaoMatricula);            

        if ($request->tipoDescontoCurso != '99'){ // diferente de 99 é pq escolhe um tipo
            $matriculas = $matriculas->where('tb_matriculas.fk_id_tipo_desconto_curso', $request->tipoDescontoCurso);                          
            $tipoDescontoCurso = TipoDescontoCurso::
                select('*')
                ->where('id_tipo_desconto_curso', $request->tipoDescontoCurso)->first();
        }
        //dd($tipoDescontoCurso);

        $unidadeEnsino = new UnidadeEnsino;
        $unidadeEnsino = $unidadeEnsino->where('id_unidade_ensino', User::getUnidadeEnsinoSelecionada())->first();
        //dd($request);
        //Verificando se escolheu uma turma
        if ($request['turma']) {
            $turma = $turma->where('id_turma', $request->turma)->first();
        }

        /**Alunos de uma turma  */
        if ($request->tipo_relatorio == 'alunos_turma') {
            if ($request->turma == null)
                return redirect()->back()->with('atencao', 'Escolha uma turma.');

            $anoLetivo = AnoLetivo::where('id_ano_letivo', $request->anoLetivo)->first();

            $matriculas = $matriculas->where('fk_id_turma', $request->turma)
                ->join('tb_pessoas', 'fk_id_aluno', 'id_pessoa')
                ->join('users', 'tb_matriculas.fk_id_user_cadastro', 'id')    
                ->orderBy($request->ordem) 
                ->orderBy('nome')
                ->get();

            return view(
                'secretaria.paginas.relatorios.alunos_turma', 
                compact('turma', 'matriculas', 'unidadeEnsino', 'tipoDescontoCurso', 'anoLetivo'),
            );
        }
        /**Alunos de uma turma com telefone*/
        else if ($request->tipo_relatorio == 'alunos_turma_telefone') {
            if ($request->turma == null)
                return redirect()->back()->with('atencao', 'Escolha uma turma.');

            $anoLetivo = AnoLetivo::where('id_ano_letivo', $request->anoLetivo)->first();

            $matriculas = $matriculas
                ->select('aluno.nome as nome_aluno', 'resp.nome as nome_responsavel', 'resp.telefone_1', 'resp.telefone_2',
                    'situacao_matricula')
                ->where('fk_id_turma', $request->turma)
                ->join('tb_pessoas as aluno', 'fk_id_aluno', 'aluno.id_pessoa')
                ->join('tb_pessoas as resp', 'fk_id_responsavel', 'resp.id_pessoa')
                ->join('tb_situacoes_matricula', 'fk_id_situacao_matricula', 'id_situacao_matricula')
                ->join('users', 'tb_matriculas.fk_id_user_cadastro', 'id')    
                ->orderBy('aluno.'.$request->ordem) 
                ->orderBy('aluno.nome')
                ->get();

            return view(
                'secretaria.paginas.relatorios.alunos_telefone', 
                compact('turma', 'matriculas', 'unidadeEnsino', 'tipoDescontoCurso', 'anoLetivo'),
            );
        }
        /* Lista de assinaturas */
        else if ($request->tipo_relatorio == 'lista_assinatura') {
            if ($request->turma == null)
                return redirect()->back()->with('atencao', 'Escolha uma turma.');
            
                $titulo = $request->titulo_lista;

            $matriculas = $matriculas->where('fk_id_turma', $request->turma)
                ->join('tb_pessoas', 'fk_id_aluno', 'id_pessoa')
                ->join('users', 'tb_matriculas.fk_id_user_cadastro', 'id')    
                ->orderBy($request->ordem) 
                ->orderBy('nome')
                ->get();

            return view(
                'secretaria.paginas.relatorios.lista_assinatura_turma',
                compact('turma', 'matriculas', 'titulo', 'tipoDescontoCurso'),
            );
        }
        /**Todos alunos matriculados */
        else if ($request->tipo_relatorio == 'todas_matriculas') {
            
            $anoLetivo = AnoLetivo::where('id_ano_letivo', $request->anoLetivo)->first();

            $matriculas = $matriculas
                ->select('nome', 'data_nascimento', 'name', 'nome_turma', 'descricao_turno', 'situacao_matricula')
                ->join('tb_pessoas', 'fk_id_aluno', 'id_pessoa')
                ->join('tb_turmas', 'fk_id_turma', 'id_turma')
                ->join('tb_turnos', 'fk_id_turno', 'id_turno')
                ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
                ->join('users', 'tb_matriculas.fk_id_user_cadastro', 'id')
                ->join('tb_situacoes_matricula', 'fk_id_situacao_matricula', 'id_situacao_matricula')
                ->where('fk_id_ano_letivo', $request->anoLetivo)                
                ->orderBy($request->ordem)
                ->orderBy('nome')
                ->get();
            
            return view('secretaria.paginas.relatorios.todas_matriculas', 
                compact('anoLetivo', 'matriculas', 'unidadeEnsino', 'tipoDescontoCurso'),
            );
        }
        /**Todos alunos matriculados com TELEFONE*/
        else if ($request->tipo_relatorio == 'todas_matriculas_telefone') {
            
            $anoLetivo = AnoLetivo::where('id_ano_letivo', $request->anoLetivo)->first();

            $matriculas = $matriculas
                ->select('aluno.nome as nome_aluno', 'resp.nome as nome_responsavel', 'resp.telefone_1', 'resp.telefone_2',
                    'situacao_matricula')                
                ->join('tb_pessoas as aluno', 'fk_id_aluno', 'aluno.id_pessoa')
                ->join('tb_pessoas as resp', 'fk_id_responsavel', 'resp.id_pessoa')
                ->join('tb_situacoes_matricula', 'fk_id_situacao_matricula', 'id_situacao_matricula')
                ->join('users', 'tb_matriculas.fk_id_user_cadastro', 'id')    
                ->join('tb_turmas', 'fk_id_turma', 'id_turma')                
                ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
                ->where('fk_id_ano_letivo', $request->anoLetivo)
                ->orderBy('aluno.'.$request->ordem) 
                ->orderBy('aluno.nome')
                ->get();

            return view(
                'secretaria.paginas.relatorios.alunos_telefone', 
                compact('matriculas', 'unidadeEnsino', 'tipoDescontoCurso', 'anoLetivo'),
            );
        }
        //Todos responsáveis cadatrados
        else if ($request->tipo_relatorio == 'todos_responsaveis'){
            $responsaveis = new Pessoa;
            $responsaveis = $responsaveis
                ->select('nome', 'cpf', 'email_1', 'telefone_1', 'telefone_2')
                ->where('fk_id_tipo_pessoa', 2)
                ->orderBy('nome')
                ->get();

            return view('secretaria.paginas.relatorios.todos_responsaveis',
                compact('responsaveis', 'unidadeEnsino'));
        }
    }
}
