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
        $this->authorize('Turma Ver');   
        $turma = $this->repositorio
                            ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
                            ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')                                       
                            ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())                                    
                            ->where('id_turma', $id)
                            ->with('tipoTurma', 'usuario')
                            ->first();
        //dd($turma);

        if (!$turma)
            return redirect()->back();

        return view('secretaria.paginas.turmas.show', [
            'turma' => $turma
        ]);
    }

    public function destroy($id)
    {
        $Turma = $this->repositorio->where('id_turma', $id)->first();

        if (!$Turma)
            return redirect()->back();

        $Turma->where('id_turma', $id)->delete();
        return redirect()->route('turmas.index');
    }

    public function search(Request $request)
    {
        $filtros = $request->except('_token');
        $turmas = $this->repositorio->search($request->filtro);
        
        $matriculas = new Matricula;
        $quantVagas = [];
        
        foreach ($turmas as $turma)
            $quantVagas[$turma->id_turma] = $matriculas->quantVagasDisponiveis($turma->id_turma);
        
        return view('secretaria.paginas.turmas.index', [
            'turmas' => $turmas,
            'filtros' => $filtros,
            'quantVagas' => $quantVagas,
        ]);
    }

    public function edit($id)
    {       
        $this->authorize('Turma Alterar');    
        $turma = $this->repositorio
                                ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma' )
                                ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')                                       
                                ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada()) 
                                ->where('id_turma', $id)
                                ->with('tipoTurma')
                                ->first();
        
        if (!$turma)
            return redirect()->back();
     
            $tiposTurmas = TipoTurma::select('*')
                                    ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
                                    ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')                                       
                                    ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())                                    
                                    ->where('tb_anos_letivos.situacao', '=', '1')
                                    ->orderBy('tb_anos_letivos.ano', 'desc')
                                    ->orderBy('tb_sub_niveis_ensino.sub_nivel_ensino', 'asc')
                                    ->orderBy('tb_tipos_turmas.tipo_turma', 'asc')
                                    ->get();

        return view('secretaria.paginas.turmas.edit',[
            'turma'         => $turma,
            'turnos'        => $this->turnos,
            'tiposTurmas'   => $tiposTurmas,
        ]);
    }

    public function update(StoreUpdateTurma $request, $id)
    {
        $turma = $this->repositorio->where('id_turma', $id)->first();

        if (!$turma)
            return redirect()->back();
        
        $sit = $this->verificarSituacao($request->all());
        
        $request->merge($sit);

        $turma->where('id_turma', $id)->update($request->except('_token', '_method'));

        return redirect()->route('turmas.index');
    }

    /**
     * Verifica se a situação foi ativada
     */
    public function verificarSituacao(array $dados)
    {
        if (!array_key_exists('situacao_turma', $dados))
            return ['situacao_turma' => '0'];
        else
             return ['situacao_turma' => '1'];            
    }
}
