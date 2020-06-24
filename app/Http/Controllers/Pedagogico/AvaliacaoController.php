<?php

namespace App\Http\Controllers\Pedagogico;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateAvaliacao;
use App\Models\GradeCurricular;
use App\Models\Pedagogico\Avaliacao;
use App\Models\Pedagogico\TipoAvaliacao;
use App\Models\PeriodoLetivo;
use App\Models\Secretaria\Disciplina;
use App\Models\TipoTurma;
use App\User;
use Illuminate\Http\Request;

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
        $avaliacoes = $this->repositorio->where('fk_id_tipo_turma', $id_tipo_turma)
        /* ->select ('*')
                            ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma' )
                            ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
                            ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
                            ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno')                                
                            ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())                        
                            ->where('tb_anos_letivos.fk_id_unidade_ensino', '=', session()->get('id_unidade_ensino'))                             
                            ->orderBy('tb_anos_letivos.ano', 'desc')
                            ->orderBy('tb_turnos.descricao_turno', 'asc')
                            ->orderBy('tb_sub_niveis_ensino.sub_nivel_ensino', 'asc')
                            ->orderBy('nome_turma', 'asc') */
                            ->paginate();

       

        return view('pedagogico.paginas.tiposturmas.avaliacoes.index', [
                    'avaliacoes' => $avaliacoes,  
                    'tipoTurma'  => $id_tipo_turma,                  
        ]);
    }

    public function create($id_tipo_turma)
    {  
        $this->authorize('Avaliação Cadastrar');   

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
       
        return view('pedagogico.paginas.tiposturmas.avaliacoes.create', [            
            'periodosLetivos' => $periodosLetivos,
            'gradeCurricular' => $gradeCurricular,
            'tiposAvaliacao'  => $tiposAvaliacao,
            'tipoTurma'       => $id_tipo_turma,
            
        ]);
    }

    public function store(StoreUpdateAvaliacao $request )
    {
        $dados = $request->all();
       
        // dd($dados);
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

        $avaliacao->where('id_avaliacao', $id)->delete();
        return redirect()->route('tiposturmas.avaliacoes', $avaliacao->fk_id_tipo_turma);
    }

    public function edit($id)
    {       
        $this->authorize('Avaliação Alterar');

        $avaliacao = $this->repositorio->where('id_avaliacao', $id)->first();
             
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
        
        return view('pedagogico.paginas.tiposturmas.avaliacoes.edit',[
                    'avaliacao' => $avaliacao,    
                    'tipoTurma' => $id_tipo_turma,
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
