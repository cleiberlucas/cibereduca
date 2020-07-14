<?php

namespace App\Http\Controllers\Pedagogico;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateAvaliacao;
use App\Models\GradeCurricular;
use App\Models\Pedagogico\Avaliacao;
use App\Models\Pedagogico\TipoAvaliacao;
use App\Models\PeriodoLetivo;
use App\Models\TipoTurma;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

/**
 * Avaliações dos alunos
 */
class AvaliacaoController extends Controller
{
    private $repositorio;
    
    public function __construct(Avaliacao $avaliacao)
    {
        $this->repositorio = $avaliacao;        
    }

    public function index($id_tipo_turma)
    {
        $this->authorize('Avaliação Ver');
         
        $avaliacoes = $this->repositorio->select('*')
                                        ->join('tb_tipos_avaliacao', 'fk_id_tipo_avaliacao', 'id_tipo_avaliacao')
                                        ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')           
                                        ->join('tb_sub_niveis_ensino', 'fk_id_sub_nivel_ensino', 'id_sub_nivel_ensino')                 
                                        ->join('tb_periodos_letivos', 'tb_avaliacoes.fk_id_periodo_letivo', 'id_periodo_letivo')
                                        ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', 'tb_anos_letivos.id_ano_letivo')                                       
                                        ->join('tb_disciplinas', 'fk_id_disciplina', 'id_disciplina')
                                        ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())   
                                        ->where('id_tipo_turma', $id_tipo_turma)                                                                         
                                        ->orderBy('periodo_letivo')
                                        ->orderBy('disciplina')
                                        ->orderBy('tipo_avaliacao')
                                        ->paginate();

        return view('pedagogico.paginas.tiposturmas.avaliacoes.index', [
                    'avaliacoes' => $avaliacoes,  
                    'tipoTurma'  => $id_tipo_turma,                  
        ]);
    }

    public function search(Request $request)
    {
        $filtros = $request->except('_token');

        $avaliacoes = $this->repositorio->searchAvaliacaoDisciplina($request->filtro, $request->id_tipo_turma);
                
        return view('pedagogico.paginas.tiposturmas.avaliacoes.index', [
                    'avaliacoes' => $avaliacoes,  
                    'tipoTurma'  => $request->id_tipo_turma,   
                    'filtros'    => $filtros,               
        ]);
    }

    public function create($id_tipo_turma)
    {  
        $this->authorize('Avaliação Cadastrar');   

        $periodosLetivos = new PeriodoLetivo;
        $periodosLetivos = $periodosLetivos->join('tb_tipos_turmas', 'tb_tipos_turmas.fk_id_ano_letivo', 'tb_periodos_letivos.fk_id_ano_letivo')                                            
                                            ->where('id_tipo_turma', $id_tipo_turma)
                                            ->where('tb_periodos_letivos.situacao', '1')
                                            ->orderBy('periodo_letivo')
                                            ->get();

        $gradeCurricular = new GradeCurricular();
        $gradeCurricular = $gradeCurricular->disciplinasTipoTurma($id_tipo_turma);
        //dd($gradeCurricular);
        $tiposAvaliacao = new TipoAvaliacao;
        $tiposAvaliacao = $tiposAvaliacao->getTiposAvaliacao(1);
        $tipoTurma = TipoTurma::where('id_tipo_turma', $id_tipo_turma)->first();
       
        return view('pedagogico.paginas.tiposturmas.avaliacoes.create', [            
            'periodosLetivos' => $periodosLetivos,
            'gradeCurricular' => $gradeCurricular,
            'tiposAvaliacao'  => $tiposAvaliacao,
            'tipoTurma'       => $tipoTurma,
            
        ]);
    }

    public function store(StoreUpdateAvaliacao $request )
    {
        $dados = $request->all();
               
        $this->repositorio->create($dados);
 
        return redirect()->route('tiposturmas.avaliacoes', $dados['fk_id_tipo_turma']);
    }

    public function show($id)
    {
        $this->authorize('Avaliação Ver');   
        $avaliacao = $this->repositorio                            
                            ->where('id_avaliacao', $id)                            
                            ->first();
        //dd($avaliacao);

        if (!$avaliacao)
            return redirect()->back();

        return view('pedagogico.paginas.tiposturmas.avaliacoes.show', [
            'avaliacao' => $avaliacao
        ]);
    }

    public function destroy($id)
    {
        $this->authorize('Avaliação Apagar');

        $avaliacao = $this->repositorio->where('id_avaliacao', $id)->first();

        if (!$avaliacao)
            return redirect()->back();

        try {
            $avaliacao->where('id_avaliacao', $id)->delete();
        } catch (QueryException $qe) {
            return redirect()->back()->with('error', 'Existe nota lançada para esta avaliação. Não é possível excluir. ');            
        }
        return redirect()->route('tiposturmas.avaliacoes', $avaliacao->fk_id_tipo_turma);
    }

    public function edit($id)
    {       
        $this->authorize('Avaliação Alterar');

        $avaliacao = $this->repositorio->where('id_avaliacao', $id)->first();
        //dd($avaliacao);
             
        if (!$avaliacao)
            return redirect()->back();
        
        $id_tipo_turma = $avaliacao->fk_id_tipo_turma;

        $periodosLetivos = new PeriodoLetivo;
        $periodosLetivos = $periodosLetivos->join('tb_tipos_turmas', 'tb_tipos_turmas.fk_id_ano_letivo', 'tb_periodos_letivos.fk_id_ano_letivo')                                            
                                            ->where('id_tipo_turma', $id_tipo_turma)
                                            ->where('tb_periodos_letivos.situacao', '1')
                                            ->orderBy('periodo_letivo')->get();

        $gradeCurricular = new GradeCurricular();
        $gradeCurricular = $gradeCurricular->disciplinasTipoTurma($id_tipo_turma);
        //dd($gradeCurricular);
        $tiposAvaliacao = new TipoAvaliacao;
        $tiposAvaliacao = $tiposAvaliacao->getTiposAvaliacao(1);
        $tipoTurma = TipoTurma::where('id_tipo_turma', $id_tipo_turma)->first();
        
        return view('pedagogico.paginas.tiposturmas.avaliacoes.edit',[
                    'avaliacao' => $avaliacao,    
                    'tipoTurma' => $tipoTurma,
                    'periodosLetivos' => $periodosLetivos,
                    'gradeCurricular' => $gradeCurricular,
                    'tiposAvaliacao'  => $tiposAvaliacao,
        ]);
    }

    public function update(StoreUpdateAvaliacao $request, $id)
    {
        $avaliacao = $this->repositorio->where('id_avaliacao', $id)->first();

        if (!$avaliacao)
            return redirect()->back();
        
        $avaliacao->where('id_avaliacao', $id)->update($request->except('_token', '_method'));

        return redirect()->route( 'tiposturmas.avaliacoes', $avaliacao->fk_id_tipo_turma) ;
    }

}
