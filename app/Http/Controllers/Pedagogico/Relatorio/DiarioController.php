<?php

namespace App\Http\Controllers\Pedagogico\Relatorio;

use App\Exports\FrequenciaExport;
use App\Exports\FrequenciaExportView;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Pedagogico\NotaController;
use App\Models\AnoLetivo;
use App\Models\GradeCurricular;
use App\Models\Pedagogico\Avaliacao;
use App\Models\Pedagogico\ConteudoLecionado;
use App\Models\Pedagogico\Nota;
use App\Models\Pedagogico\ResultadoAlunoPeriodo;
use App\Models\PeriodoLetivo;
use App\Models\Secretaria\Disciplina;
use App\Models\Secretaria\Matricula;
use App\Models\Secretaria\Turma;
use App\Models\UnidadeEnsino;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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

    //teste todas frequencias
    /* public function frequenciaExcel(){
        return Excel::download(new FrequenciaExport, 'users.xlsx');
    } */

    public function filtros(Request $request)
    {

        //dd($request);
        $turma = Turma::
            select('nome_turma', 'descricao_turno', 'sub_nivel_ensino', 'fk_id_unidade_ensino', 'ano', 'fk_id_tipo_turma')
            ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
            ->join('tb_turnos', 'fk_id_turno', 'id_turno')
            ->join('tb_sub_niveis_ensino', 'fk_id_sub_nivel_ensino', 'id_sub_nivel_ensino')
            ->join('tb_anos_letivos', 'fk_id_ano_letivo', 'id_ano_letivo')
            ->where('id_turma', $request->turma)        
            ->first();

        $unidadeEnsino = new UnidadeEnsino;
        $unidadeEnsino = $unidadeEnsino->where('id_unidade_ensino', $turma->fk_id_unidade_ensino)->first();

        $periodosLetivos = new PeriodoLetivo;

        $periodoLetivo = $periodosLetivos->where('id_periodo_letivo', $request->periodo)->first();
        
        $anosLetivos = new AnoLetivo;
        $mediaAprovacao = $anosLetivos->getMediaAprovacao($request->anoLetivo);
        $mediaAprovacao = floatval($mediaAprovacao->media_minima_aprovacao);
        
        $alunos = new Matricula;
        
        //dd($alunos);

        //Consultar apenas um aluno da turma
        if ($request->tipo_relatorio == 'boletim_aluno') {
            $alunos = $alunos->getMatriculaAluno($request->fk_id_matricula); 
           
            if ($request->fk_id_matricula == null)
                return redirect()->back()->with('atencao', 'Escolha um aluno.');

            //atualizar notas médias antes de rodar
            $atualizarMedia = new NotaController(new Nota);
            $atualizarMedia->atualizarNotasAluno($request->fk_id_matricula);

            //Le resultados de NOTAS E FALTAS do bimestre de 1 aluno
            $resultados = new ResultadoAlunoPeriodo;
            $resultados = $resultados->getResultadosMatricula($request->fk_id_matricula);
            //dd($resultados);
        } else if ($request->tipo_relatorio == 'boletim_turma') {
            //atualizar notas médias antes de rodar os boletins
            $atualizarMedia = new NotaController(new Nota);
            $atualizarMedia->atualizarNotasTurma($request->turma);

            $alunos = $alunos->getAlunosTurma($request->turma);

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
                'mediaAprovacao' => $mediaAprovacao,
            ]);
        } /* else if ($request->tipo_relatorio == 'aprendizagem') {
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
        } */
                /* Conteúdo lecionado bimestral - UMA DISCIPLINA*/
        else if($request->tipo_relatorio == 'conteudo_bimestral_disciplina'){
            if ($request->disciplina == null)
                return redirect()->back()->with('atencao', 'Escolha uma disciplina.');
        
            if ($request->conteudo_periodo == null)
                return redirect()->back()->with('atencao', 'Escolha um período Letivo.');
                
            $disciplina = new Disciplina;
            $disciplina = $disciplina->getDisciplina($request->disciplina);

            $periodoLetivo = $periodosLetivos->where('id_periodo_letivo', $request->conteudo_periodo)->first();

            $conteudosLecionados = new ConteudoLecionado;
            $conteudosLecionados = $conteudosLecionados
                ->select('data_aula', 'conteudo_lecionado')
                ->join('tb_turmas_periodos_letivos', 'fk_id_turma_periodo_letivo', 'id_turma_periodo_letivo')
                ->where('fk_id_periodo_letivo', $request->conteudo_periodo)
                ->where('fk_id_turma', $request->turma)
                ->where('fk_id_disciplina', $request->disciplina)
                ->orderBy('data_aula')
                ->get();

            return view('pedagogico.paginas.turmas.relatorios.conteudos_bimestre_disciplina', [
                'unidadeEnsino' => $unidadeEnsino,
                'periodoLetivo' => $periodoLetivo,
                'turma' => $turma,
                'disciplina' => $disciplina,
                'conteudosLecionados' => $conteudosLecionados,

                ]);
                
        }
        /* Conteúdo lecionado bimestral - TODAS DISCIPLINA*/
        else if($request->tipo_relatorio == 'conteudo_bimestral_todas_disciplinas'){
            
            if ($request->conteudo_periodo == null)
                return redirect()->back()->with('atencao', 'Escolha um período Letivo.');

            $disciplinas = new GradeCurricular;
            $disciplinas = $disciplinas->disciplinasTurma($request->turma);

            $periodoLetivo = $periodosLetivos->where('id_periodo_letivo', $request->conteudo_periodo)->first();

            $conteudosLecionados = new ConteudoLecionado;
            $conteudosLecionados = $conteudosLecionados
                ->select('data_aula', 'conteudo_lecionado', 'fk_id_disciplina')
                ->join('tb_turmas_periodos_letivos', 'fk_id_turma_periodo_letivo', 'id_turma_periodo_letivo')
                ->where('fk_id_periodo_letivo', $request->conteudo_periodo)
                ->where('fk_id_turma', $request->turma)
                ->orderBy('fk_id_disciplina')
                ->orderBy('data_aula')
                ->get();

            return view('pedagogico.paginas.turmas.relatorios.conteudos_bimestre_todas_disciplinas', [
                'unidadeEnsino' => $unidadeEnsino,
                'periodoLetivo' => $periodoLetivo,
                'turma' => $turma,
                'disciplinas' => $disciplinas,
                'conteudosLecionados' => $conteudosLecionados,

                ]);
                
        }
                /* Avaliações bimestre UMA DISCIPLINA */
        else if ($request->tipo_relatorio == 'avaliacoes_bimestre_disciplina'){
            if ($request->disciplina == null)
                return redirect()->back()->with('atencao', 'Escolha uma disciplina.');
        
            if ($request->periodo == null)
                return redirect()->back()->with('atencao', 'Escolha um período Letivo.');

                
            //atualizar notas médias antes de rodar os boletins
            $atualizarMedia = new NotaController(new Nota);
            $atualizarMedia->atualizarNotasTurma($request->turma);

            $alunos = $alunos->getAlunosTurma($request->turma);

            $disciplinas = new GradeCurricular;
            $disciplinas = $disciplinas
                ->disciplinasTurma($request->turma)
                ->where('fk_id_disciplina', $request->disciplina);

            $avaliacoes = Avaliacao::
                join('tb_tipos_avaliacao', 'fk_id_tipo_avaliacao', 'id_tipo_avaliacao')
                ->where('fk_id_periodo_letivo', $request->periodo)
                ->where('fk_id_tipo_turma', $turma->fk_id_tipo_turma)
                ->where('fk_id_disciplina', $request->disciplina)
                ->orderBy('tipo_avaliacao')
                ->get();
                //dd($avaliacoes);

            $notas = Nota::            
                join('tb_avaliacoes', 'fk_id_avaliacao', 'id_avaliacao')                
                ->where('fk_id_tipo_turma', $turma->fk_id_tipo_turma)
                ->where('fk_id_periodo_letivo', $request->periodo)
                ->where('fk_id_disciplina', $request->disciplina)
                ->get();

            $resultados = new ResultadoAlunoPeriodo;
            $resultados = $resultados->getResultadosTurmaPeriodoDisciplina($request->turma, $request->periodo, $request->disciplina);
            //dd($resultados);
            return view('pedagogico.paginas.turmas.relatorios.avaliacoes_bimestre', [
                'unidadeEnsino' => $unidadeEnsino,
                'periodoLetivo' => $periodoLetivo,
                'turma' => $turma,
                'disciplinas' => $disciplinas,
                'matriculas' => $alunos,
                'avaliacoes' => $avaliacoes,
                'notas' => $notas,
                'resultados' => $resultados,
                'mediaAprovacao' => $mediaAprovacao,
            ]);
        }
        /* Avaliações bimestre TODAS DISCIPLINAS */
        else if ($request->tipo_relatorio == 'avaliacoes_bimestre_todas_disciplinas'){
            
            if ($request->periodo == null)
                return redirect()->back()->with('atencao', 'Escolha um período Letivo.');
                
            //atualizar notas médias antes de rodar os boletins
            $atualizarMedia = new NotaController(new Nota);
            $atualizarMedia->atualizarNotasTurma($request->turma);

            $alunos = $alunos->getAlunosTurma($request->turma);

            $disciplinas = new GradeCurricular;
            $disciplinas = $disciplinas
                ->disciplinasTurma($request->turma);
            //dd($disciplinas);

            $avaliacoes = Avaliacao::
                join('tb_tipos_avaliacao', 'fk_id_tipo_avaliacao', 'id_tipo_avaliacao')
                ->where('fk_id_periodo_letivo', $request->periodo)
                ->where('fk_id_tipo_turma', $turma->fk_id_tipo_turma)                
                ->orderBy('tipo_avaliacao')
                ->get();

            $notas = Nota::            
                join('tb_avaliacoes', 'fk_id_avaliacao', 'id_avaliacao')                
                ->where('fk_id_tipo_turma', $turma->fk_id_tipo_turma)
                ->where('fk_id_periodo_letivo', $request->periodo)                
                ->get();

            $resultados = new ResultadoAlunoPeriodo;
            $resultados = $resultados->getResultadosTurmaPeriodo($request->turma, $request->periodo);
            //dd($resultados);
            return view('pedagogico.paginas.turmas.relatorios.avaliacoes_bimestre', [
                'unidadeEnsino' => $unidadeEnsino,
                'periodoLetivo' => $periodoLetivo,
                'turma' => $turma,
                'disciplinas' => $disciplinas,
                'matriculas' => $alunos,
                'avaliacoes' => $avaliacoes,
                'notas' => $notas,
                'resultados' => $resultados,
                'mediaAprovacao' => $mediaAprovacao,
            ]);
        }
            /* rendimento escolar */
        else if ($request->tipo_relatorio == 'rendimento_escolar'){
            if ($request->periodo == null)
                return redirect()->back()->with('atencao', 'Escolha um período Letivo.');

            //atualizar notas médias antes de rodar
            $atualizarMedia = new NotaController(new Nota);
            $atualizarMedia->atualizarNotasTurma($request->turma);

            $alunos = $alunos->getAlunosTurma($request->turma);

            $disciplinas = new GradeCurricular;
            $disciplinas = $disciplinas->disciplinasTurma($request->turma);

            $resultados = new ResultadoAlunoPeriodo;
            $resultados = $resultados->getResultadosTurmaPeriodo($request->turma, $request->periodo);
            //dd($resultados);

            return view('pedagogico.paginas.turmas.relatorios.rendimento_escolar', [ 
                'unidadeEnsino' => $unidadeEnsino,
                'periodoLetivo' => $periodoLetivo,
                'turma' => $turma,                
                'matriculas' => $alunos,                
                'resultados' => $resultados,
                'gradeCurricular' => $disciplinas,
                'mediaAprovacao' => $mediaAprovacao,
            ]);
        }

        /* Imprime ficha de frequência em branco sem dados das frequencias 
            Somente dados da turma, disciplina e aluno
        */ else if ($request->tipo_relatorio == 'freq_mensal_branco') {
            $this->authorize('Frequência Ver');
            
            $alunos = $alunos->getAlunosTurma($request->turma);

            if ($request->mes == null)
                return redirect()->back()->with('atencao', 'Escolha um mês.');

            if ($request->disciplina == null)
                return redirect()->back()->with('atencao', 'Escolha uma disciplina.');

            $disciplina = new Disciplina;
            $disciplina = $disciplina->getDisciplina($request->disciplina);

           /*  return Excel::download(new FrequenciaExportView("pedagogico.paginas.turmas.relatorios.frequencia_mensal_teste",
                $unidadeEnsino,
                $turma,
                $request->mes,
                $disciplina,
                $alunos,
                24), trans_choice('general.repayment', 2) . ' ' . trans_choice('general.report',
                1) . '.xlsx'); */

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

            $alunos = $alunos->getAlunosTurma($request->turma);

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
