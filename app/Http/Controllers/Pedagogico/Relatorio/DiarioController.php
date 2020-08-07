<?php

namespace App\Http\Controllers\Pedagogico\Relatorio;

use App\Http\Controllers\Controller;
use App\Models\AnoLetivo;
use App\Models\Secretaria\Disciplina;
use App\Models\Secretaria\Matricula;
use App\Models\Secretaria\Turma;
use App\Models\UnidadeEnsino;
use Illuminate\Http\Request;

class DiarioController extends Controller
{

    /**
     * Página inicial relatórios diários
     */
    public function diario()
    {
        $this->authorize('Frequência Ver');

        $anosLetivos = AnoLetivo::where('fk_id_unidade_ensino', '=', session()->get('id_unidade_ensino'))
            ->orderBy('ano', 'desc')
            ->get();

        return view('pedagogico.paginas.turmas.relatorios.index_diario', [
            'anosLetivos' => $anosLetivos,
        ]);
    }

    public function filtros(Request $request)
    {
        $this->authorize('Frequência Ver');

        //dd($request);
        $unidadeEnsino = new UnidadeEnsino;

        $turma = Turma::where('id_turma', $request->turma)->first();
        $unidadeEnsino = $unidadeEnsino->where('id_unidade_ensino', $turma->tipoTurma->anoLetivo->fk_id_unidade_ensino)->first();

        $alunos = new Matricula;
        $alunos = $alunos->getAlunosTurma($request->turma);
        //dd($alunos);

        /* Imprime ficha de frequência em branco sem dados das frequencias 
            Somente dados da turma, disciplina e aluno
        */
        if ($request->frequencia == 'freq_mensal_branco') {
            if ($request->disciplina == null)
                return redirect()->back()->with('atencao', 'Escolha uma disciplina.');

            $disciplina = new Disciplina;
            $disciplina = $disciplina->getDisciplina($request->disciplina);

            return view('pedagogico.paginas.turmas.relatorios.frequencia_mensal_branco', [
                'unidadeEnsino' => $unidadeEnsino,
                'turma' => $turma,
                'mes'   => $request->mes,
                'disciplina' => $disciplina,
                'alunos'    => $alunos,
                'qtColunasDias' => 24,
            ]);
        }

        /* Imprime ficha de frequência COM dados das frequencias 
            Somente dados da turma, disciplina e aluno
        */ else if ($request->frequencia == 'freq_mensal_disciplina') {
            if ($request->disciplina == null)
                return redirect()->back()->with('atencao', 'Escolha uma disciplina.');

            $disciplina = new Disciplina;
            $disciplina = $disciplina->getDisciplina($request->disciplina);

            return view('pedagogico.paginas.turmas.relatorios.frequencia_mensal_disciplina', [
                'unidadeEnsino' => $unidadeEnsino,
                'turma' => $turma,
                'mes'   => $request->mes,
                'disciplina' => $disciplina,
                'alunos'    => $alunos,
                'qtColunasDias' => 24,
            ]);
        } else {
            return redirect()->back()->with('atencao', 'Escolha um tipo de relatório.');
        }
    }
}
