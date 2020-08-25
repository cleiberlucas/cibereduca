<?php

namespace App\Http\Controllers\Pedagogico\Relatorio;

use App\Http\Controllers\Controller;
use App\Models\AnoLetivo;
use App\Models\GradeCurricular;
use App\Models\Pedagogico\Avaliacao;
use App\Models\Pedagogico\Frequencia;
use App\Models\Pedagogico\Nota;
use App\Models\Pedagogico\ResultadoAlunoPeriodo;
use App\Models\Pedagogico\TipoAvaliacao;
use App\Models\PeriodoLetivo;
use App\Models\Secretaria\Disciplina;
use App\Models\Secretaria\Matricula;
use App\Models\Secretaria\Turma;
use App\Models\UnidadeEnsino;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function gerarAprendizagem(UnidadeEnsino $unidadeEnsino, Turma $turma, $idDisciplina)
    {
        $disciplina = new Disciplina;
        $disciplina = $disciplina->getDisciplina($idDisciplina);
        // dd($disciplina);
        return view(
            'pedagogico.paginas.turmas.relatorios.acompanhamento_aprendizagem',
            [
                'unidadeEnsino' => $unidadeEnsino,
                'turma' => $turma,
                'disciplina' => $disciplina,
            ]
        );
    }

    public function filtros(Request $request)
    {

        //dd($request);
        $turma = Turma::where('id_turma', $request->turma)->first();

        $unidadeEnsino = new UnidadeEnsino;
        $unidadeEnsino = $unidadeEnsino->where('id_unidade_ensino', $turma->tipoTurma->anoLetivo->fk_id_unidade_ensino)->first();

        $periodosLetivos = new PeriodoLetivo;

        $periodoLetivo = $periodosLetivos->where('id_periodo_letivo', $request->periodo)->first();

        $alunos = new Matricula;
        $alunos = $alunos->getAlunosTurma($request->turma);
        //dd($alunos);

        //Consultar apenas um aluno da turma
        if ($request->tipo_relatorio == 'boletim_aluno') {
            $alunos = $alunos->getMatriculaAluno($request->matricula);

            //Le resultados de NOTAS E FALTAS do bimestre de 1 aluno
            $resultados = new ResultadoAlunoPeriodo;
            $resultados = $resultados->getResultadosMatricula($request->matricula);
            //dd($resultados);
        } else if ($request->tipo_relatorio == 'boletim_turma') {
            //Le resultados de NOTAS E FALTAS do bimestre de 1 turma
            $resultados = new ResultadoAlunoPeriodo;
            $resultados = $resultados->getResultadosTurma($request->turma); 
            //dd($resultados);
        }

        /* Gera boletim*/
        if ($request->tipo_relatorio == 'boletim_aluno' or $request->tipo_relatorio == 'boletim_turma') {
            $this->authorize('Nota Ver');

            $disciplinas = new GradeCurricular;
            $disciplinas = $disciplinas->disciplinasTurma($request->turma);
            
            $periodosLetivos = $periodosLetivos->getPeriodosLetivosAno($request->anoLetivo);

            //dd($periodosLetivos);
            return view('pedagogico.paginas.turmas.relatorios.boletim', [
                'unidadeEnsino' => $unidadeEnsino,
                'turma' => $turma,
                'matriculas' => $alunos,
                'disciplinas' => $disciplinas,
                'periodosLetivos' => $periodosLetivos,
                'resultados'    => $resultados,
            ]);
        } else if ($request->tipo_relatorio == 'aprendizagem') {
            if ($request->disciplina == null)
                return redirect()->back()->with('atencao', 'Escolha uma disciplina.');
            
            if ($request->periodo == null)
                return redirect()->back()->with('atencao', 'Escolha um período Letivo.');

            //$this->gerarAprendizagem($unidadeEnsino, $turma, $request->disciplina);

            $disciplina = new Disciplina;
            $disciplina = $disciplina->getDisciplina($request->disciplina);
            // dd($disciplina);
            return view('pedagogico.paginas.turmas.relatorios.acompanhamento_aprendizagem', [
                'unidadeEnsino' => $unidadeEnsino,
                'periodoLetivo' => $periodoLetivo,
                'turma' => $turma,
                'disciplina' => $disciplina,
                'matriculas' => $alunos,
            ]);
        }
                /* Avaliações bimestre */
        else if ($request->tipo_relatorio == 'avaliacoes_bimestre'){
            if ($request->disciplina == null)
            return redirect()->back()->with('atencao', 'Escolha uma disciplina.');
        
            if ($request->periodo == null)
                return redirect()->back()->with('atencao', 'Escolha um período Letivo.');

            $disciplina = new Disciplina;
            $disciplina = $disciplina->getDisciplina($request->disciplina);

            $avaliacoes = Avaliacao::
                join('tb_tipos_avaliacao', 'fk_id_tipo_avaliacao', 'id_tipo_avaliacao')
                ->where('fk_id_periodo_letivo', $request->periodo)
                ->where('fk_id_tipo_turma', $turma->fk_id_tipo_turma)
                ->where('fk_id_disciplina', $request->disciplina)
                ->orderBy('tipo_avaliacao')
                ->get();

            $notas = Nota::            
                join('tb_avaliacoes', 'fk_id_avaliacao', 'id_avaliacao')                
                ->where('fk_id_tipo_turma', $turma->fk_id_tipo_turma)
                ->where('fk_id_periodo_letivo', $request->periodo)
                ->where('fk_id_disciplina', $request->disciplina)
                ->get();

            $resultados = new ResultadoAlunoPeriodo;
            $resultados = $resultados->getResultadosTurmaPeriodoDisciplina($request->turma, $request->periodo, $request->disciplina);

            return view('pedagogico.paginas.turmas.relatorios.avaliacoes_bimestre', [
                'unidadeEnsino' => $unidadeEnsino,
                'periodoLetivo' => $periodoLetivo,
                'turma' => $turma,
                'disciplina' => $disciplina,
                'matriculas' => $alunos,
                'avaliacoes' => $avaliacoes,
                'notas' => $notas,
                'resultados' => $resultados,
            ]);
        }
            /* rendimento escolar */
        else if ($request->tipo_relatorio == 'rendimento_escolar'){
            if ($request->periodo == null)
                return redirect()->back()->with('atencao', 'Escolha um período Letivo.');

            $gradeCurricular = new GradeCurricular;
            $gradeCurricular = $gradeCurricular->where('fk_id_tipo_turma', $turma->fk_id_tipo_turma)->get();

            $resultados = new ResultadoAlunoPeriodo;
            $resultados = $resultados->getResultadosTurmaPeriodo($request->turma, $request->periodo);

            return view('pedagogico.paginas.turmas.relatorios.rendimento_escolar', [
                'unidadeEnsino' => $unidadeEnsino,
                'periodoLetivo' => $periodoLetivo,
                'turma' => $turma,                
                'matriculas' => $alunos,                
                'resultados' => $resultados,
                'gradeCurricular' => $gradeCurricular,
            ]);
        }

        /* Imprime ficha de frequência em branco sem dados das frequencias 
            Somente dados da turma, disciplina e aluno
        */ else if ($request->tipo_relatorio == 'freq_mensal_branco') {
            $this->authorize('Frequência Ver');

            if ($request->mes == null)
                return redirect()->back()->with('atencao', 'Escolha um mês.');

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
        */ else if ($request->tipo_relatorio == 'freq_mensal_disciplina') {
            $this->authorize('Frequência Ver');

            if ($request->mes == null)
                return redirect()->back()->with('atencao', 'Escolha um mês.');

            if ($request->disciplina == null)
                return redirect()->back()->with('atencao', 'Escolha uma disciplina.');

            $disciplina = new Disciplina;
            $disciplina = $disciplina->getDisciplina($request->disciplina);

            $diasFrequencias = DB::table('tb_frequencias')
                ->select(DB::Raw('DAY(data_aula) dia'))
                ->join('tb_turmas_periodos_letivos', 'tb_frequencias.fk_id_periodo_letivo', 'tb_turmas_periodos_letivos.fk_id_periodo_letivo')
                ->where('fk_id_turma', $request->turma)
                ->where('fk_id_disciplina', $request->disciplina)
                ->whereMonth('data_aula', '=', $request->mes)
                ->groupBy('dia')
                /* ->orderBy('data_aula') */
                ->get();

            $frequencias = DB::table('tb_frequencias')
                ->join('tb_turmas_periodos_letivos', 'tb_frequencias.fk_id_periodo_letivo', 'tb_turmas_periodos_letivos.fk_id_periodo_letivo')
                ->join('tb_tipos_frequencia', 'fk_id_tipo_frequencia', 'id_tipo_frequencia')
                ->where('fk_id_turma', $request->turma)
                ->where('fk_id_disciplina', $request->disciplina)
                ->whereMonth('data_aula', '=', $request->mes)
                ->orderBy('data_aula')
                ->get();

            // dd($frequencias);

            return view('pedagogico.paginas.turmas.relatorios.frequencia_mensal_disciplina', [
                'unidadeEnsino' => $unidadeEnsino,
                'turma' => $turma,
                'mes'   => $request->mes,
                'disciplina' => $disciplina,
                'alunos'    => $alunos,
                'diasFrequencias' => $diasFrequencias,
                'frequencias' =>   $frequencias,
                'qtColunasDias' => count($diasFrequencias),

            ]);
        } else {
            return redirect()->back()->with('atencao', 'Escolha um tipo de relatório.');
        }
    }
}
