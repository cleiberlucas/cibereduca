<?php

namespace App\Http\Controllers\Pedagogico;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateConteudoLecionado;
use App\Models\GradeCurricular;
use App\Models\Pedagogico\ConteudoLecionado;
use App\Models\Pedagogico\TurmaPeriodoLetivo;

use Illuminate\Http\Request;

class ConteudoLecionadoController extends Controller
{
    private $repositorio;
        
    public function __construct(ConteudoLecionado $conteudoLecionado)
    {
        $this->repositorio = $conteudoLecionado;
        
    }

    public function index($id_turma, $id_periodo_letivo = null, $id_disciplina = null)
    {
        $this->authorize('Conteúdo Lecionado Ver');   

        //Somente disciplinas vinculadas à grade curricular da turma
        $disciplinasTurma = new GradeCurricular;
        $disciplinasTurma = $disciplinasTurma->disciplinasTurma($id_turma);
        $turmaPeriodoLetivo = new TurmaPeriodoLetivo;

        return view('pedagogico.paginas.turmas.conteudoslecionados.index', [
            'id_turma' => $id_turma,
            'disciplinasTurma' => $disciplinasTurma,
            'turmaPeriodosLetivos' => $turmaPeriodoLetivo->getTurmaPeriodosLetivos($id_turma),     
            'conteudosLecionados' => $this->repositorio->getConteudosLecionados($id_turma),
        ]); 
    }

    public function store(StoreUpdateConteudoLecionado $request)
    {
        $this->authorize('Conteúdo Lecionado Cadastrar');

        $dados = $request->all();
        $id_turma = $dados['fk_id_turma'];
       
        $this->repositorio->create($dados);
        
        $disciplinasTurma = new GradeCurricular;
        $disciplinasTurma = $disciplinasTurma->disciplinasTurma($id_turma);
        $turmaPeriodoLetivo = new TurmaPeriodoLetivo;

        return view('pedagogico.paginas.turmas.conteudoslecionados.index', [
                        'id_turma' => $id_turma,
                        'disciplinasTurma'     => $disciplinasTurma,
                        'turmaPeriodosLetivos' => $turmaPeriodoLetivo->getTurmaPeriodosLetivos($id_turma),     
                        'conteudosLecionados' => $this->repositorio->getConteudosLecionados($id_turma),
                        'selectPeriodoLetivo'  => $dados['id_periodo_letivo'],
                        'selectDisciplina'     =>  $dados['fk_id_disciplina'],
        ]);         
    }

    public function update(StoreUpdateConteudoLecionado $request, $id)
    {        
        $this->authorize('Conteúdo Lecionado Alterar');

        $conteudoLecionado = $this->repositorio->where('id_conteudo_lecionado', $id)->first();
        
        if (!$conteudoLecionado)
            return redirect()->back();
      
        $conteudoLecionado->where('id_conteudo_lecionado', $id)->update($request->except('_token', '_method', 'fk_id_turma', 'id_periodo_letivo'));

        $dados = $request->all();
        $id_turma = $dados['fk_id_turma'];

        $disciplinasTurma = new GradeCurricular;
        $disciplinasTurma = $disciplinasTurma->disciplinasTurma($id_turma);
        $turmaPeriodoLetivo = new TurmaPeriodoLetivo;

        //return redirect()->back();
       return view('pedagogico.paginas.turmas.conteudoslecionados.index', [
                    'id_turma' => $id_turma,
                    'disciplinasTurma'     => $disciplinasTurma,
                    'turmaPeriodosLetivos' => $turmaPeriodoLetivo->getTurmaPeriodosLetivos($id_turma),     
                    'conteudosLecionados' => $this->repositorio->getConteudosLecionados($id_turma),
                    'selectPeriodoLetivo'  => $dados['id_periodo_letivo'],
                    'selectDisciplina'     => $dados['fk_id_disciplina'],
        ]);
    } 

    /**
     * Remover conteúdo lecionado
     */
    public function remover($id_conteudo_lecionado)
    {
        $this->authorize('Conteúdo Lecionado Remover');   
        
        $conteudoLecionado = $this->repositorio->where('id_conteudo_lecionado', $id_conteudo_lecionado, )->first();
        
        if (!$conteudoLecionado)
            return redirect()->back();

        $id_turma = $conteudoLecionado->turmaPeriodoLetivo->fk_id_turma;
        $id_periodo_letivo = $conteudoLecionado->turmaPeriodoLetivo->fk_id_periodo_letivo;
        $id_disciplina = $conteudoLecionado->fk_id_disciplina;

        $conteudoLecionado->where('id_conteudo_lecionado', $id_conteudo_lecionado, )->delete();

        $disciplinasTurma = new GradeCurricular;
        $disciplinasTurma = $disciplinasTurma->disciplinasTurma($id_turma);
        $turmaPeriodoLetivo = new TurmaPeriodoLetivo;
        
        return view('pedagogico.paginas.turmas.conteudoslecionados.index', [
                    'id_turma' => $id_turma,
                    'disciplinasTurma'     => $disciplinasTurma,
                    'turmaPeriodosLetivos' => $turmaPeriodoLetivo->getTurmaPeriodosLetivos($id_turma),     
                    'conteudosLecionados' => $this->repositorio->getConteudosLecionados($id_turma),
                    'selectPeriodoLetivo'  => $id_periodo_letivo,
                    'selectDisciplina'     => $id_disciplina,
        ]); 
    }

}