<?php

namespace App\Http\Controllers\Pedagogico;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateFrequencia;
use App\Models\GradeCurricular;
use App\Models\Pedagogico\Frequencia;
use App\Models\Pedagogico\ResultadoAlunoPeriodo;
use App\Models\Pedagogico\TurmaPeriodoLetivo;
use App\Models\Secretaria\Matricula;
use App\Models\Pedagogico\TipoFrequencia;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class FrequenciaController extends Controller
{
    private $repositorio, $resultadoAlunoPeriodo;
        
    public function __construct(Frequencia $frequencia)
    {
        $this->repositorio = $frequencia;        
        $this->resultadoAlunoPeriodo = new ResultadoAlunoPeriodo;        
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
        $frequencia = $this->repositorio
            ->where('id_frequencia', $id_frequencia)                                    
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
        //dd($request);
        $frequencia = $this->repositorio
            ->select('tb_turmas_periodos_letivos.situacao',
                'id_matricula',
                'fk_id_disciplina',
                'tb_frequencias.fk_id_periodo_letivo'
                )
            ->join('tb_matriculas', 'fk_id_matricula', 'id_matricula')
            ->join('tb_turmas_periodos_letivos', 'tb_turmas_periodos_letivos.fk_id_turma', 'tb_matriculas.fk_id_turma')
            ->where('tb_turmas_periodos_letivos.fk_id_turma',  $request->fk_id_turma)
            ->where('tb_turmas_periodos_letivos.fk_id_periodo_letivo', $request->fk_id_periodo_letivo)
            ->where('id_frequencia', $id)
            ->first();
       // dd($frequencia->turmaPeriodoLetivo->situacao);
        
        if (!$frequencia)
            return redirect()->back();
      
        if ($frequencia->situacao == 0 )
            return redirect()->back()->with('erro', 'Período letivo fechado. Não é possível alterar a frequência.');

        $frequencia_up = $this->repositorio->where('id_frequencia', $id)->update($request->except('_token', '_method', 'fk_id_turma', 'fk_id_periodo_letivo'));
        
        $dadosFrequencia = array(['fk_id_periodo_letivo' => $frequencia->fk_id_periodo_letivo, 
                                    'fk_id_matricula' => $frequencia->id_matricula,
                                    'fk_id_disciplina' => $frequencia->fk_id_disciplina
                            ]);

       // dd($dadosFrequencia);

        //Atualizar o total de faltas dos aluno X período X disciplina
        $this->gravarFaltasAlunoPeriodoDisciplina($dadosFrequencia);

        return $this->frequenciaShowAluno($frequencia->fk_id_periodo_letivo, $frequencia->id_matricula);
    }

    public function store(StoreUpdateFrequencia $request)
    {
        $this->authorize('Frequência Cadastrar');

        $dados = $request->all();
        $id_turma = $dados['fk_id_turma'];

        $frequencia = [];
        $frequencias = [];
        /* Lendo os dados de cada aluno p gravar a frequencia */
        foreach($dados['fk_id_matricula'] as $index => $matricula){            
            $frequencia['fk_id_periodo_letivo'] = $dados['fk_id_periodo_letivo'];
            $frequencia['fk_id_matricula'] = $matricula;
            $frequencia['fk_id_disciplina'] = $dados['fk_id_disciplina'];
            $frequencia['data_aula'] = $dados['data_aula'];
            $frequencia['fk_id_tipo_frequencia'] = $dados['fk_id_tipo_frequencia'][$index];
            $frequencia['fk_id_user'] = $dados['fk_id_user'];

            /* gravando a frequencia de um aluno */
            $this->repositorio->create($frequencia);    

            /*gerando novo array com todos os alunos p gravar resultado aluno X PERIODO X DISCIPLINA*/
            $frequencias[$index] = $frequencia;
        }

        //Gravar o total de faltas do aluno no resultado do período
        $this->gravarFaltasAlunoPeriodoDisciplina($frequencias);

        return $this->index($id_turma);               
    }

    /**
     * Gravar quantidade de faltas no período todas as vezes que:
     * Incluir
     * Alterar
     * Excluir
     * Quaisquer tipos de frequências
     */
    public function gravarFaltasAlunoPeriodoDisciplina(array $frequencias)
    {
        foreach ($frequencias as $key => $frequencia) {
         //  dd($frequencia);
            /*Ler a quantidade de faltas do aluno X periodo X disciplina */
            $total_faltas = $this->repositorio->getFaltasAlunoPeriodoDisciplina($frequencia['fk_id_matricula'], $frequencia['fk_id_periodo_letivo'], $frequencia['fk_id_disciplina']);
            
            /*Incluindo o total de faltas no array */
            $frequencia = array_merge($frequencia, ['total_faltas' => $total_faltas]);

            //dd($this->repositorio->getFaltasAlunoPeriodoDisciplina($fk_id_matricula, $fk_id_periodo_letivo, $fk_id_disciplina));    

            //Verificar se já foi lançado resultado para um período
            $existeResultado = $this->resultadoAlunoPeriodo->getResultadoAlunoPeriodoDisciplina($frequencia['fk_id_matricula'], $frequencia['fk_id_periodo_letivo'], $frequencia['fk_id_disciplina']);

            //Existe resultado lançado
            //ALTERAR QUANTIDADE DE FALTAS NO RESULTADO DO PERIODO
            if ($existeResultado == 1)
                $this->alterarFaltasResultadoAluno($frequencia);
            
            //Não existe resultado lançado
            //INSERIR QUANTIDADE DE FALTAS NO RESULTADO DO PERIODO
            else
                $this->inserirFaltasResultadoAluno($frequencia);
        }
    }

    /**
     * Inserir quantidade de faltas no resultado do aluno X periodo X disciplina
     * o array deve conter
     * ['fk_id_matricula'], 
     * ['fk_id_periodo_letivo'], 
     * ['fk_id_disciplina']
     * ['total_faltas']
     */
    public function inserirFaltasResultadoAluno(array $frequencia)
    {
        $this->resultadoAlunoPeriodo->create($frequencia);
    }

    /**
     * Alterar quantidade de faltas no resultado do aluno X periodo X disciplina
     * o array deve conter
     * ['fk_id_matricula'], 
     * ['fk_id_periodo_letivo'], 
     * ['fk_id_disciplina']
     * ['total_faltas']
     */
    public function alterarFaltasResultadoAluno(array $frequencia)
    {
        try {
            $this->resultadoAlunoPeriodo->where('fk_id_matricula', $frequencia['fk_id_matricula'])
                                    ->where('fk_id_periodo_letivo', $frequencia['fk_id_periodo_letivo'])
                                    ->where('fk_id_disciplina', $frequencia['fk_id_disciplina'])
                                    ->update(array('total_faltas' => $frequencia['total_faltas']));
        } catch (\Throwable $th) {
            return redirect()->back()->with('erro', 'ATENÇÃO: houve erro ao calcular o total de faltas do aluno. FAVOR CONTATAR O SUPORTE TÉCNICO.');
        }
    }
    
    public function frequenciaShowAluno($id_periodo_letivo, $id_matricula)
    {
        
        $frequencia = $this->repositorio->select('fk_id_turma')
                                        ->join('tb_matriculas', 'fk_id_matricula', 'id_matricula')
                                        ->where('fk_id_matricula', '=', $id_matricula)
                                        ->first();
                                        // dd($id_matricula);
        if (!$frequencia)
            return redirect()->back()->with('atencao', 'Não há frequência lançada para este aluno.');

        $frequenciasAlunoDisciplinasPeriodo = $this->repositorio->getFrequenciasAlunoDisciplinasPeriodo($id_periodo_letivo, $id_matricula);
        $frequenciasAlunoDatasPeriodo = $this->repositorio->getFrequenciasAlunoDatasPeriodo($id_periodo_letivo, $id_matricula);
        $frequenciasAlunoMesesPeriodo = $this->repositorio->getFrequenciasAlunoMesesPeriodo($id_periodo_letivo, $id_matricula);
        $frequenciasAlunoPeriodo = $this->repositorio->getFrequenciasAlunoPeriodo($id_periodo_letivo, $id_matricula, $frequencia->fk_id_turma);

       

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
                                    'tb_frequencias.fk_id_periodo_letivo',
                                    'data_aula',
                                    'periodo_letivo',
                                    'nome_turma',
                                    'sub_nivel_ensino',
                                    'descricao_turno',                                    
                                    'disciplina',
                                    'tb_periodos_letivos.situacao',
                                    'id_turma')
                            ->join('tb_periodos_letivos', 'fk_id_periodo_letivo', 'id_periodo_letivo')    
                            ->join('tb_matriculas', 'fk_id_matricula', 'id_matricula')                        
                            ->join('tb_turmas', 'fk_id_turma', 'id_turma')
                            ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
                            ->join('tb_sub_niveis_ensino', 'fk_id_sub_nivel_ensino', 'id_sub_nivel_ensino')
                            ->join('tb_turnos', 'fk_id_turno', 'id_turno')
                            ->join('tb_disciplinas', 'fk_id_disciplina', 'id_disciplina')
                            ->where('id_turma', $id_turma)
                            ->where('fk_id_periodo_letivo', $id_periodo_letivo)
                            ->where('fk_id_disciplina', $id_disciplina)                            
                            ->get();
        
        if(count($frequencias) == 0)
            return redirect()->back()->with('atencao', 'Não há frequência lançada para esta disciplina, neste período letivo.');

        $frequenciasDatas = $this->repositorio
                                    ->select('data_aula')
                                    ->join('tb_periodos_letivos', 'fk_id_periodo_letivo', 'id_periodo_letivo')
                                    ->join('tb_matriculas', 'fk_id_matricula', 'id_matricula') 
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
        
        $frequencias = $this->repositorio
            ->join('tb_matriculas', 'fk_id_matricula', 'id_matricula')
            ->where('fk_id_turma', $id_turma)
            ->where('fk_id_disciplina', $id_disciplina)
            ->where('data_aula', $data_aula)
            ->get();

        /* $dadosFrequencia = array(['fk_id_periodo_letivo' => $frequencia->fk_id_periodo_letivo, 
                                    'fk_id_matricula' => $frequencia->fk_id_matricula,
                                    'fk_id_disciplina' => $frequencia->fk_id_disciplina
                                ]);    */  
        
        if (!$frequencias)
            return redirect()->back()->with('atencao', 'Não há frequência lançada para os dados informados.');
        
        //dd($id_turma);
        $dadosFrequencias = [];
        /* Lendo os dados de cada aluno p atualizar o RESULTADO DO PERIODO */
        foreach($frequencias as $index => $frequencia){            
            $freq['fk_id_periodo_letivo'] = $frequencia['fk_id_periodo_letivo'];
            $freq['fk_id_matricula'] = $frequencia->fk_id_matricula;
            $freq['fk_id_disciplina'] = $frequencia['fk_id_disciplina'];            

            /*gerando novo array com todos os alunos p gravar/alterar resultado aluno X PERIODO X DISCIPLINA*/
            $dadosFrequencias[$index] = $freq;
        }

        $frequencias = $this->repositorio
            ->join('tb_matriculas', 'fk_id_matricula', 'id_matricula')
            ->where('fk_id_turma', $id_turma)
            ->where('fk_id_disciplina', $id_disciplina)
            ->where('data_aula', $data_aula)
            ->delete();
        
        //Atualizar o total de faltas dos alunos X período X disciplina
        $this->gravarFaltasAlunoPeriodoDisciplina($dadosFrequencias);
        
        return $this->index($id_turma); 
        //return redirect()->back()->with('sucesso', 'Frequências excluídas com sucesso.');
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
