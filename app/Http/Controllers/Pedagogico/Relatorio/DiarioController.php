<?php

namespace App\Http\Controllers\Pedagogico\Relatorio;

use App\Http\Controllers\Controller;
use App\Models\AnoLetivo;
use App\Models\Pedagogico\Frequencia;
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
            
            $diasFrequencias = DB::table('tb_frequencias')
                ->select(DB::Raw('DAY(data_aula) dia'))
                ->join('tb_turmas_periodos_letivos', 'fk_id_turma_periodo_letivo', 'id_turma_periodo_letivo')
                ->where('fk_id_turma', $request->turma)
                ->where('fk_id_disciplina', $request->disciplina)
                ->whereMonth('data_aula', '=', $request->mes)
                ->groupBy('dia')
                /* ->orderBy('data_aula') */
                ->get();

            $frequencias = DB::table('tb_frequencias')                                
                ->join('tb_turmas_periodos_letivos', 'fk_id_turma_periodo_letivo', 'id_turma_periodo_letivo') 
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
