<?php

namespace App\Http\Controllers\Pedagogico;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateNota;
use App\Models\GradeCurricular;
use App\Models\Pedagogico\Avaliacao;
use App\Models\Pedagogico\Nota;
use App\Models\Pedagogico\TurmaPeriodoLetivo;
use App\Models\Secretaria\Matricula;
use App\Models\Secretaria\Turma;
use App\Models\User;
use Illuminate\Http\Request;

class NotaController extends Controller
{
    private $repositorio;
        
    public function __construct(Nota $nota)
    {
        $this->repositorio = $nota;        
        
    }

    /**
     * Lista Turmas para lançamento de notas
     */
    public function indexNotas()
    {
        $turmas = Turma::select ('*')
                            ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma' )
                            ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
                            ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
                            ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno')                                                            
                            ->where('tb_anos_letivos.fk_id_unidade_ensino', '=', User::getUnidadeEnsinoSelecionada())                             
                            ->orderBy('tb_anos_letivos.ano', 'desc')
                            ->orderBy('tb_turnos.descricao_turno', 'asc')
                            ->orderBy('tb_sub_niveis_ensino.sub_nivel_ensino', 'asc')
                            ->orderBy('nome_turma', 'asc')
                            ->paginate();
       //dd($turmas);
        return view('pedagogico.paginas.turmas.notas.index_turmas', [
                    'turmas' => $turmas,       
        ]);
    }

    /**
     * Gera abas de períodos letivos e disciplinas para lançamento de notas
     */
    public function index($id_turma, $id_periodo_letivo = null, $id_disciplina = null)
    {
        $this->authorize('Frequência Ver');   

        $turma = Turma::where('id_turma', $id_turma)->first();

        $idTipoTurma = $turma->fk_id_tipo_turma;

        //Somente disciplinas vinculadas à grade curricular da turma
        $disciplinasTurma = new GradeCurricular;        
        $turmaPeriodoLetivo = new TurmaPeriodoLetivo;
        $avaliacoes = new Avaliacao;        
                
        return view('pedagogico.paginas.turmas.notas.index', [
            'id_turma' => $id_turma,
            'disciplinasTurma' => $disciplinasTurma->disciplinasTurma($id_turma),
            'turmaPeriodosLetivos' => $turmaPeriodoLetivo->getTurmaPeriodosLetivos($id_turma),     
            'turmaMatriculas'      => $this->getTurmaMatriculas($id_turma),
            'avaliacoes'           => $avaliacoes->getAvaliacoesTipoTurma($idTipoTurma),
            
        ]); 
    }

    public function edit($id_nota)
    {
        $nota = $this->repositorio->where('id_nota', $id_nota)                                    
                                    ->get();
        
        //dd($nota);
        return view('pedagogico.paginas.turmas.notas.edit', [
            'notaAluno' => $nota,
            
        ]);
    }

    public function update(StoreUpdateNota $request, $id)
    {        
        $this->authorize('Nota Alterar');

        $nota = $this->repositorio->where('id_nota', $id)->first();
        
        if (!$nota)
            return redirect()->back();
      
        $nota->where('id_nota', $id)->update($request->except('_token', '_method'));

        $notaAluno = $this->repositorio->where('id_nota', $id)->first();

        return $this->frequenciaShowAluno($notaAluno->fk_id_turma_periodo_letivo, $notaAluno->fk_id_matricula);
    }

    public function store(StoreUpdateNota $request)
    {
        $this->authorize('Nota Cadastrar');

        $dados = $request->all();
        $id_turma = $dados['fk_id_turma'];
        //dd($dados);
        //dd($dados['nota']);
        
        foreach($dados['nota'] as $index => $nota){ 
            /* Preparando array p gravar notas 
               Só envia p gravar se a nota for preenchida
               pq o aluno pode faltar a prova
            */                
            $notas = [];
            if ($nota != null)          
            {                   
                $notas['fk_id_matricula'] = $dados['fk_id_matricula'][$index];
                $notas['fk_id_avaliacao'] = $dados['fk_id_avaliacao'][0];
                $notas['data_avaliacao']  = $dados['data_avaliacao'];
                $notas['nota']            = $nota;                    
                $notas['fk_id_user']      = $dados['fk_id_user'];
            }
            
            if (count($notas) > 0)
                $this->repositorio->create($notas);    
        }
        
        $disciplinasTurma = new GradeCurricular;
        $disciplinasTurma = $disciplinasTurma->disciplinasTurma($id_turma);
        
        $turmaPeriodoLetivo = new TurmaPeriodoLetivo;

        $avaliacoes = new Avaliacao;

        $turma = Turma::where('id_turma', $id_turma)->first();

        $idTipoTurma = $turma->fk_id_tipo_turma;

        return view('pedagogico.paginas.turmas.notas.index', [
                        'id_turma' => $id_turma,
                        'disciplinasTurma'     => $disciplinasTurma,
                        'turmaPeriodosLetivos' => $turmaPeriodoLetivo->getTurmaPeriodosLetivos($id_turma),  
                        'turmaMatriculas'      => $this->getTurmaMatriculas($id_turma),   
                        'selectPeriodoLetivo'  => $dados['id_periodo_letivo'],
                        'selectDisciplina'     =>  $dados['fk_id_disciplina'],
                        'avaliacoes'           => $avaliacoes->getAvaliacoesTipoTurma($idTipoTurma),
        ]);         
    }
    
    public function notaShowAluno($id_matricula)
    {        
        $notasAluno = $this->repositorio->getNotasAluno($id_matricula)->first();

        $id_turma = $notasAluno->matricula->fk_id_turma;
        $id_tipo_turma = $notasAluno->matricula->turma->fk_id_tipo_turma;

        //Todos os períodos que possuem avaliacao cadastrada
        $periodosTurma = $this->repositorio->getPeriodosTurma($id_tipo_turma);

        //Todas as avaliações cadastradas para a turma
        $avaliacoesTurma = $this->repositorio->getAvaliacoesTurma($id_tipo_turma);

        //Grade curricular da Turma
        $gradeCurricular = new GradeCurricular;
        $gradeCurricular = $gradeCurricular->disciplinasTurma($id_turma);        
        
        $notasAluno = $this->repositorio->getNotasAluno($id_matricula);
        
        return view('pedagogico.paginas.turmas.notas.showaluno', [
                    'id_turma'              => $id_turma,
                    'periodosTurma'         => $periodosTurma,
                    'gradeCurricular'       => $gradeCurricular,
                    'avaliacoesTurma'       => $avaliacoesTurma,
                    'notasAluno'            => $notasAluno,

        ]);
    } 

    /**
     * Remover nota
     */
    /* public function remover($id_nota)
    {
        $this->authorize('Frequência Remover');   
        
        $nota = $this->repositorio->where('id_nota', $id_nota, )->first();
        
        if (!$nota)
            return redirect()->back();

        $id_turma = $nota->turmaPeriodoLetivo->fk_id_turma;
        $id_periodo_letivo = $nota->turmaPeriodoLetivo->fk_id_periodo_letivo;
        $id_disciplina = $nota->fk_id_disciplina;

        $nota->where('id_nota', $id_nota, )->delete();

        $disciplinasTurma = new GradeCurricular;
        $disciplinasTurma = $disciplinasTurma->disciplinasTurma($id_turma);
        $turmaPeriodoLetivo = new TurmaPeriodoLetivo;
        
        return view('pedagogico.paginas.turmas.notas.index', [
                    'id_turma' => $id_turma,
                    'disciplinasTurma'     => $disciplinasTurma,
                    'turmaPeriodosLetivos' => $turmaPeriodoLetivo->getTurmaPeriodosLetivos($id_turma),     
                    'frequencias' => $this->repositorio->getFrequencias($id_turma),
                    'selectPeriodoLetivo'  => $id_periodo_letivo,
                    'selectDisciplina'     => $id_disciplina,
        ]); 
    } */

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
