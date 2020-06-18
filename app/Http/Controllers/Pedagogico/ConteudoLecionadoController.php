<?php

namespace App\Http\Controllers\Pedagogico;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateConteudoLecionado;
use App\Models\GradeCurricular;
use App\Models\Pedagogico\ConteudoLecionado;
use App\Models\Pedagogico\TurmaPeriodoLetivo;
use App\Models\Secretaria\Turma;
use App\User;
use Illuminate\Http\Request;

class ConteudoLecionadoController extends Controller
{
    private $repositorio;
    
    public function __construct(ConteudoLecionado $conteudoLecionado)
    {
        $this->repositorio = $conteudoLecionado;
        /* $this->turnos = new Turno;
        $this->turnos = $this->turnos->all()->sortBy('descricao_turno');
        $this->tiposTurmas = new TipoTurma; */
        //$this->tiposTurmas = $this->tiposTurmas->all()->sortBy('tipo_turma');

    }

    public function index($id_turma)
    {
        $this->authorize('Conteudo Lecionado Cadastrar');   

        /* Lista de períodos de UMA TURMA p listar no form de cadastro 
            Somente períodos abertos
        */
        $turmaPeriodosLetivos = TurmaPeriodoLetivo::select
                                    ('tb_turmas_periodos_letivos.id_turma_periodo_letivo',                                    
                                    'tb_turmas_periodos_letivos.situacao',
                                    'tb_turmas_periodos_letivos.fk_id_turma',
                                    'tb_periodos_letivos.id_periodo_letivo',
                                    'tb_periodos_letivos.periodo_letivo',                                    
                                    'tb_turmas.nome_turma',
                                    'tb_sub_niveis_ensino.sub_nivel_ensino',
                                    'tb_turnos.descricao_turno') 
                                    ->join('tb_periodos_letivos', 'fk_id_periodo_letivo', 'id_periodo_letivo')
                                    ->join('tb_turmas', 'fk_id_turma', 'id_turma')
                                    ->join('tb_turnos', 'fk_id_turno', 'id_turno')
                                    ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
                                    ->join('tb_sub_niveis_ensino', 'fk_id_sub_nivel_ensino', 'id_sub_nivel_ensino')                                    
                                    ->where('tb_turmas_periodos_letivos.fk_id_turma', '=', $id_turma)
                                    ->orderBy('periodo_letivo', 'asc')                                    
                                    ->get();
       
        //Somente disciplinas vinculadas à grade curricular da turma
        $disciplinasTurma = new GradeCurricular;
        $disciplinasTurma = $disciplinasTurma->disciplinasTurma($id_turma);
        //dd($disciplinasTurma);
        $conteudosLecionados = $this->repositorio
                                            ->join('tb_turmas_periodos_letivos', 'fk_id_turma_periodo_letivo', 'id_turma_periodo_letivo')
                                            ->where('fk_id_turma', '=', $id_turma)
                                            ->orderBy('data_aula', 'asc')
                                            ->paginate(20); 
       // dd($conteudosLecionados);
        return view('pedagogico.paginas.turmas.conteudoslecionados.index', [
            'id_turma' => $id_turma,
            'disciplinasTurma' => $disciplinasTurma,
            'turmaPeriodosLetivos' => $turmaPeriodosLetivos,     
            'conteudosLecionados' => $conteudosLecionados,
        ]); 
    }

    public function create($id_turma)
    {  
        $this->authorize('Conteudo Lecionado Cadastrar');   

        /* Lista de períodos p listar no form de cadastro 
            Somente períodos abertos
        */
        /* $turmasPeriodosLetivos = TurmaPeriodoLetivo::select('tb_turmas_periodos_letivos.id_turma_periodo_letivo',
                                    'tb_periodos_letivos.periodo_letivo',)                                    
                                    ->join('tb_periodos_letivos', 'fk_id_periodo_letivo', 'id_periodo_letivo')
                                    ->where('tb_turmas_periodos_letivos.situacao', '=', '1')
                                    ->where('fk_id_turma', '=', $id_turma)
                                    ->orderBy('periodo_letivo', 'asc')                                    
                                    ->get();
       
        $disciplinasTurma = new GradeCurricular;
        $disciplinasTurma = $disciplinasTurma->disciplinasTurma($id_turma);

        return url('pedagogico.paginas.turmas.conteudoslecionados.create', [            
            'id_turma' => $id_turma,
            'disciplinasTurma' => $disciplinasTurma,
            'turmasPeriodosLetivos' => $turmasPeriodosLetivos,     
        ]); */
    }

     public function store(StoreUpdateConteudoLecionado $request )
    {
        $dados = $request->all();
        
       // dd($dados);
        $this->repositorio->create($dados);
 
        return redirect()->route('turmas.conteudoslecionados', $dados['fk_id_turma']);
    }
/*
    public function show($id)
    {
        $this->authorize('Turma Ver');   
        $turma = $this->repositorio
                            ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
                            ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')                                       
                            ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())                                    
                            ->where('id_turma', $id)->with('tipoTurma', 'usuario')->first();

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
                                ->where('id_turma', $id)->with('tipoTurma')->first();
        
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
    } */

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
