<?php

namespace App\Http\Controllers\Secretaria\Relatorio;

use App\Http\Controllers\Controller;
use App\Models\AnoLetivo;
use App\Models\Secretaria\Matricula;
use App\Models\Secretaria\Turma;
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

        $anosLetivos = AnoLetivo::where('fk_id_unidade_ensino', '=', session()->get('id_unidade_ensino'))
            ->orderBy('ano', 'desc')
            ->get();

        return view('secretaria.paginas.relatorios.index', [
            'anosLetivos' => $anosLetivos,
        ]);
    }

    /**
     * Verifica escolhas do usuário e gera relatório
     */
    public function filtros(Request $request)
    {
        $this->authorize('Pessoa Ver');

        $turma = new Turma;
        $matriculas = new Matricula;
        $unidadeEnsino = new UnidadeEnsino;
        $unidadeEnsino = $unidadeEnsino->where('id_unidade_ensino', User::getUnidadeEnsinoSelecionada())->first();
        //dd($request);
        //Verificando se escolheu uma turma
        if ($request['turma']) {
            $turma = $turma->where('id_turma', $request->turma)->first();
        }

        if ($request->tipo_relatorio == 'alunos_turma') {
            if ($request->turma == null)
                return redirect()->back()->with('atencao', 'Escolha uma turma.');

            $matriculas = $matriculas->where('fk_id_turma', $request->turma)
                ->join('tb_pessoas', 'fk_id_aluno', 'id_pessoa')
                ->join('users', 'tb_matriculas.fk_id_user_cadastro', 'id')
                ->orderBy($request->ordem)
                ->orderBy('nome')
                ->get();

            return view(
                'secretaria.paginas.relatorios.alunos_turma',
                compact('turma', 'matriculas', 'unidadeEnsino'),
            );
        }
        else if ($request->tipo_relatorio == 'todas_matriculas') {
            
            $anoLetivo = AnoLetivo::where('id_ano_letivo', $request->anoLetivo)->first();

            $matriculas = $matriculas
                ->select('nome', 'name', 'nome_turma', 'descricao_turno')
                ->join('tb_pessoas', 'fk_id_aluno', 'id_pessoa')
                ->join('tb_turmas', 'fk_id_turma', 'id_turma')
                ->join('tb_turnos', 'fk_id_turno', 'id_turno')
                ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
                ->join('users', 'tb_matriculas.fk_id_user_cadastro', 'id')
                ->where('fk_id_ano_letivo', $request->anoLetivo)
                ->orderBy($request->ordem)
                ->orderBy('nome')
                ->get();

            return view('secretaria.paginas.relatorios.todas_matriculas', 
                compact('anoLetivo', 'matriculas', 'unidadeEnsino'),
            );
        }
    }
}
