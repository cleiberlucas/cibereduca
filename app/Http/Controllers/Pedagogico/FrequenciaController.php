<?php

namespace App\Http\Controllers\Pedagogico;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateFrequencia;
use App\Models\GradeCurricular;
use App\Models\Pedagogico\Frequencia;
use App\Models\Pedagogico\TurmaPeriodoLetivo;
use App\Models\Secretaria\Matricula;
use App\Models\Pedagogico\TipoFrequencia;
use App\Models\Secretaria\Disciplina;
use Illuminate\Http\Request;

class FrequenciaController extends Controller
{
    private $repositorio, $disciplina;
        
    public function __construct(Frequencia $frequencia)
    {
        $this->repositorio = $frequencia;
        $this->disciplina = new Disciplina;
        
    }

    public function index($id_turma, $id_periodo_letivo = null, $id_disciplina = null)
    {
        $this->authorize('Frequência Ver');   

        //Somente disciplinas vinculadas à grade curricular da turma
        $disciplinasTurma = new GradeCurricular;
        $disciplinasTurma = $disciplinasTurma->disciplinasTurma($id_turma);
        $turmaPeriodoLetivo = new TurmaPeriodoLetivo;
        $tiposFrequencia = new TipoFrequencia;
        $tiposFrequencia = $tiposFrequencia->getTiposFrequencia();
        
        return view('pedagogico.paginas.turmas.frequencias.index', [
            'id_turma' => $id_turma,
            'disciplinasTurma' => $disciplinasTurma,
            'turmaPeriodosLetivos' => $turmaPeriodoLetivo->getTurmaPeriodosLetivos($id_turma),     
            'turmaMatriculas'      => $this->getTurmaMatriculas($id_turma),
            'tiposFrequencia'      => $tiposFrequencia,            
        ]); 
    }

    public function edit($id_frequencia)
    {
        $frequencia = $this->repositorio->where('id_frequencia', $id_frequencia)                                    
                                    ->get();
        
        $tiposFrequencia = new TipoFrequencia;
        $tiposFrequencia = $tiposFrequencia->getTiposFrequencia();
        //dd($frequencia);
        return view('pedagogico.paginas.turmas.frequencias.edit', [
            'frequenciaAluno' => $frequencia,
            'tiposFrequencia' => $tiposFrequencia,
        ]);
    }

    public function store(StoreUpdateFrequencia $request)
    {
        $this->authorize('Frequência Cadastrar');

        $dados = $request->all();
        $id_turma = $dados['fk_id_turma'];
        //dd($dados);
        //dd($dados['fk_id_matricula']);
        $frequencias = [];
        foreach($dados['fk_id_matricula'] as $index => $matricula){            
            $frequencias['fk_id_turma_periodo_letivo'] = $dados['fk_id_turma_periodo_letivo'];
            $frequencias['fk_id_matricula'] = $matricula;
            $frequencias['fk_id_disciplina'] = $dados['fk_id_disciplina'];
            $frequencias['data_aula'] = $dados['data_aula'];
            $frequencias['fk_id_tipo_frequencia'] = $dados['fk_id_tipo_frequencia'][$index];
            $frequencias['fk_id_user'] = $dados['fk_id_user'];
            //dd($frequencias);

            $this->repositorio->create($frequencias);    
        }
        
        $disciplinasTurma = new GradeCurricular;
        $disciplinasTurma = $disciplinasTurma->disciplinasTurma($id_turma);
        
        $turmaPeriodoLetivo = new TurmaPeriodoLetivo;

        $tiposFrequencia = new TipoFrequencia;
        $tiposFrequencia = $tiposFrequencia->getTiposFrequencia();

        return view('pedagogico.paginas.turmas.frequencias.index', [
                        'id_turma' => $id_turma,
                        'disciplinasTurma'     => $disciplinasTurma,
                        'turmaPeriodosLetivos' => $turmaPeriodoLetivo->getTurmaPeriodosLetivos($id_turma),  
                        'turmaMatriculas'      => $this->getTurmaMatriculas($id_turma),   
            /*             'frequencias' => $this->repositorio->getFrequencias($id_turma), */
                        'tiposFrequencia'      => $tiposFrequencia,
                        'selectPeriodoLetivo'  => $dados['id_periodo_letivo'],
                        'selectDisciplina'     =>  $dados['fk_id_disciplina'],
        ]);         
    }
    
    public function frequenciaShowAluno($id_turma_periodo_letivo, $id_matricula)
    {
        $frequenciasAlunoDisciplinasPeriodo = $this->repositorio->getFrequenciasAlunoDisciplinasPeriodo($id_turma_periodo_letivo, $id_matricula);
        $frequenciasAlunoDatasPeriodo = $this->repositorio->getFrequenciasAlunoDatasPeriodo($id_turma_periodo_letivo, $id_matricula);
        $frequenciasAlunoMesesPeriodo = $this->repositorio->getFrequenciasAlunoMesesPeriodo($id_turma_periodo_letivo, $id_matricula);
        $frequenciasAlunoPeriodo = $this->repositorio->getFrequenciasAlunoPeriodo($id_turma_periodo_letivo, $id_matricula);

        $frequencia = $this->repositorio->select('fk_id_turma')
                                    ->join('tb_matriculas', 'fk_id_matricula', 'id_matricula')
                                    ->where('fk_id_matricula', '=', $id_matricula)
                                    ->first();

        $id_turma = $frequencia->fk_id_turma;
        //dd($id_turma);
        return view('pedagogico.paginas.turmas.frequencias.showaluno', [
            'id_turma'                  => $id_turma,
            'frequenciasAlunoPeriodo' => $frequenciasAlunoPeriodo,
            'frequenciasAlunoDisciplinasPeriodo' => $frequenciasAlunoDisciplinasPeriodo,
            'frequenciasAlunoDatasPeriodo'  => $frequenciasAlunoDatasPeriodo,
            'frequenciasAlunoMesesPeriodo'  => $frequenciasAlunoMesesPeriodo,
        ]);
    }

    public function update(StoreUpdateFrequencia $request, $id)
    {        
        $this->authorize('Frequência Alterar');

        $frequencia = $this->repositorio->where('id_frequencia', $id)->first();
        
        if (!$frequencia)
            return redirect()->back();
      
        $frequencia->where('id_frequencia', $id)->update($request->except('_token', '_method'));

        $frequenciaAluno = $this->repositorio->where('id_frequencia', $id)->get();

        $tiposFrequencia = new TipoFrequencia;
        $tiposFrequencia = $tiposFrequencia->getTiposFrequencia();
        //dd($frequencia);
        return view('pedagogico.paginas.turmas.frequencias.edit', [
            'frequenciaAluno' => $frequenciaAluno,
            'tiposFrequencia' => $tiposFrequencia,
        ]);
        
        /* $dados = $request->all();
        $id_turma = $dados['fk_id_turma'];

        $disciplinasTurma = new GradeCurricular;
        $disciplinasTurma = $disciplinasTurma->disciplinasTurma($id_turma);
        $turmaPeriodoLetivo = new TurmaPeriodoLetivo; */

        //return redirect()->back();
       /* return view('pedagogico.paginas.turmas.frequencias.index', [
                    'id_turma' => $id_turma,
                    'disciplinasTurma'     => $disciplinasTurma,
                    'turmaPeriodosLetivos' => $turmaPeriodoLetivo->getTurmaPeriodosLetivos($id_turma),     
                    'frequencias' => $this->repositorio->getFrequencias($id_turma),
                    'selectPeriodoLetivo'  => $dados['id_periodo_letivo'],
                    'selectDisciplina'     => $dados['fk_id_disciplina'],
        ]); */
    } 

    /**
     * Remover conteúdo lecionado
     */
    public function remover($id_frequencia)
    {
        $this->authorize('Frequência Remover');   
        
        $frequencia = $this->repositorio->where('id_frequencia', $id_frequencia, )->first();
        
        if (!$frequencia)
            return redirect()->back();

        $id_turma = $frequencia->turmaPeriodoLetivo->fk_id_turma;
        $id_periodo_letivo = $frequencia->turmaPeriodoLetivo->fk_id_periodo_letivo;
        $id_disciplina = $frequencia->fk_id_disciplina;

        $frequencia->where('id_frequencia', $id_frequencia, )->delete();

        $disciplinasTurma = new GradeCurricular;
        $disciplinasTurma = $disciplinasTurma->disciplinasTurma($id_turma);
        $turmaPeriodoLetivo = new TurmaPeriodoLetivo;
        
        return view('pedagogico.paginas.turmas.frequencias.index', [
                    'id_turma' => $id_turma,
                    'disciplinasTurma'     => $disciplinasTurma,
                    'turmaPeriodosLetivos' => $turmaPeriodoLetivo->getTurmaPeriodosLetivos($id_turma),     
                    'frequencias' => $this->repositorio->getFrequencias($id_turma),
                    'selectPeriodoLetivo'  => $id_periodo_letivo,
                    'selectDisciplina'     => $id_disciplina,
        ]); 
    }

    /**
     * Retorna todas as matrículas de uma turma
     */
    public function getTurmaMatriculas($id_turma)    
    {
        $matricula = new Matricula;
        $turmaMatriculas = $matricula->select('id_matricula',
                                                'tb_pessoas.nome')                                    
                                    ->join('tb_pessoas', 'fk_id_aluno', 'id_pessoa')
                                    ->where('tb_matriculas.fk_id_turma', '=', $id_turma)
                                    ->orderBy('tb_pessoas.nome')
                                    ->get();

        return $turmaMatriculas;
    }

}
