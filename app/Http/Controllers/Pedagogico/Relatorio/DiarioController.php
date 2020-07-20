<?php

namespace App\Http\Controllers\Pedagogico\Relatorio;

use App\Http\Controllers\Controller;
use App\Models\AnoLetivo;
use App\Models\Secretaria\Disciplina;
use App\Models\Secretaria\Matricula;
use App\Models\Secretaria\Turma;
use Illuminate\Http\Request;

class DiarioController extends Controller
{
    
    /**
     * Página inicial relatórios diários
     */
    public function diario()
    {
        $anosLetivos = AnoLetivo::where('fk_id_unidade_ensino', '=', session()->get('id_unidade_ensino'))
                                ->orderBy('ano', 'desc')
                                ->get();
       
        return view('pedagogico.paginas.turmas.relatorios.index_diario', [
            'anosLetivos' => $anosLetivos,
        ]);
    }

    public function filtros(Request $request)
    {
        //dd($request);
        $turma = Turma::where('id_turma', $request->turma)->first();
        $disciplina = new Disciplina;
        $disciplina = $disciplina->getDisciplina($request->disciplina);        
        $alunos = new Matricula;
        $alunos = $alunos->getAlunosTurma($request->turma);
        //dd($alunos);
        /* Imprime ficha de frequência em branco sem dados das frequencias 
            Somente dados da turma e disciplina e aluno
        */
        if ($request->frequencia == 'freq_branco'){
            
             return view('pedagogico.paginas.turmas.relatorios.frequencia_branco', [
                        'turma' => $turma,
                        'mes'   => $request->mes,
                        'disciplina' => $disciplina,
                        'alunos'    => $alunos,
             ]); 
        }
        else{
            return redirect()->back()->with('atencao', 'Escolha um tipo de relatório.');
        }
    }
}
