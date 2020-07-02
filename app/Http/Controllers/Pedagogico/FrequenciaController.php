<?php

namespace App\Http\Controllers\Pedagogico;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateFrequencia;
use App\Models\GradeCurricular;
use App\Models\Pedagogico\Frequencia;
use App\Models\Pedagogico\TurmaPeriodoLetivo;
use App\Models\Secretaria\Matricula;
use App\Models\Pedagogico\TipoFrequencia;
use Illuminate\Http\Request;

class FrequenciaController extends Controller
{
    private $repositorio;
        
    public function __construct(Frequencia $frequencia)
    {
        $this->repositorio = $frequencia;        
        
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

        $turmaPeriodoLetivo = new TurmaPeriodoLetivo;
        $turmaPeriodoLetivo->getTurmaPeriodosLetivos($frequencia);
        
        //dd($frequencia);
        return view('pedagogico.paginas.turmas.frequencias.edit', [
            'frequenciaAluno' => $frequencia,
            'tiposFrequencia' => $tiposFrequencia,            
        ]);
    }

    public function update(StoreUpdateFrequencia $request, $id)
    {        
        $this->authorize('Frequência Alterar');

        $frequencia = $this->repositorio->where('id_frequencia', $id)->first();
       // dd($frequencia->turmaPeriodoLetivo->situacao);
        if ($frequencia->turmaPeriodoLetivo->situacao == 0 )
            return redirect()->back()->with('erro', 'Período letivo fechado. Não é possível alterar a frequência.');
        
        if (!$frequencia)
            return redirect()->back();
      
        $frequencia->where('id_frequencia', $id)->update($request->except('_token', '_method'));

        $frequenciaAluno = $this->repositorio->where('id_frequencia', $id)->first();

        return $this->frequenciaShowAluno($frequenciaAluno->fk_id_turma_periodo_letivo, $frequenciaAluno->fk_id_matricula);
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
        
        if (!$frequencia)
            return redirect()->back()->with('atencao', 'Não há frequência lançada para este aluno.');

        $id_turma = $frequencia->fk_id_turma;

        //dd($id_turma);
        return view('pedagogico.paginas.turmas.frequencias.showaluno', [
            'id_turma'                      => $id_turma,
            'frequenciasAlunoPeriodo'       => $frequenciasAlunoPeriodo,
            'frequenciasAlunoDisciplinasPeriodo' => $frequenciasAlunoDisciplinasPeriodo,
            'frequenciasAlunoDatasPeriodo'  => $frequenciasAlunoDatasPeriodo,
            'frequenciasAlunoMesesPeriodo'  => $frequenciasAlunoMesesPeriodo,
        ]);
    }

    /**
     * Abrir view p remover frequência
     */
    public function delete($id_turma, $id_periodo_letivo, $id_disciplina)
    {
        $frequencias = $this->repositorio
                            ->select('tb_frequencias.fk_id_disciplina',
                                    'tb_frequencias.fk_id_turma_periodo_letivo',
                                    'data_aula',
                                    'periodo_letivo',
                                    'nome_turma',
                                    'sub_nivel_ensino',
                                    'descricao_turno',                                    
                                    'disciplina',
                                    'tb_turmas_periodos_letivos.situacao',
                                    'id_turma')
                            ->join('tb_turmas_periodos_letivos', 'fk_id_turma_periodo_letivo', 'id_turma_periodo_letivo')
                            ->join('tb_periodos_letivos', 'fk_id_periodo_letivo', 'id_periodo_letivo')
                            ->join('tb_turmas', 'fk_id_turma', 'id_turma')
                            ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
                            ->join('tb_sub_niveis_ensino', 'fk_id_sub_nivel_ensino', 'id_sub_nivel_ensino')
                            ->join('tb_turnos', 'fk_id_turno', 'id_turno')
                            ->join('tb_disciplinas', 'fk_id_disciplina', 'id_disciplina')
                            ->where('tb_turmas_periodos_letivos.fk_id_turma', $id_turma)
                            ->where('fk_id_periodo_letivo', $id_periodo_letivo)
                            ->where('fk_id_disciplina', $id_disciplina)                            
                            ->get();
        
        if(count($frequencias) == 0)
            return redirect()->back()->with('atencao', 'Não há frequência lançada para esta disciplina, neste período letivo.');


        $frequenciasDatas = $this->repositorio
                                    ->select('data_aula')
                                    ->join('tb_turmas_periodos_letivos', 'fk_id_turma_periodo_letivo', 'id_turma_periodo_letivo')
                                    ->where('fk_id_turma', $id_turma)
                                    ->where('fk_id_periodo_letivo', $id_periodo_letivo)
                                    ->where('fk_id_disciplina', $id_disciplina)
                                    ->groupBy('data_aula')
                                    ->paginate();
       
        //dd($frequencias);
        return view('pedagogico.paginas.turmas.frequencias.delete', [
                    'frequencias'   => $frequencias,
                    'frequenciasDatas' => $frequenciasDatas,
                ]);
    }

    /**
     * Remover frequencia
     */
    public function remover($id_turma, $data_aula, $id_disciplina)
    {
        $this->authorize('Frequência Remover');   
        
        $frequencia = $this->repositorio->where('fk_id_turma_periodo_letivo', $id_turma)
                                        ->where('fk_id_disciplina', $id_disciplina)
                                        ->where('data_aula', $data_aula)
                                        ->first();
        
        if (!$frequencia)
            return redirect()->back()->with('atencao', 'Não há frequência lançada para os dados informados.');

        $frequencia->where('fk_id_turma_periodo_letivo', $id_turma)
                    ->where('fk_id_disciplina', $id_disciplina)
                    ->where('data_aula', $data_aula)
                    ->delete();

        return redirect()->back()->with('sucesso', 'Frequências excluídas com sucesso.');
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
