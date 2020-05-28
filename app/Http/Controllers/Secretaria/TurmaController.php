<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateTurma;
use App\Models\Secretaria\Turma;
use App\Models\TipoTurma;
use App\Models\Turno;
use Illuminate\Http\Request;

class TurmaController extends Controller
{
    private $repositorio, $turnos, $tiposTurmas;
    
    public function __construct(Turma $turma)
    {
        $this->repositorio = $turma;
        $this->turnos = new Turno;
        $this->turnos = $this->turnos->all()->sortBy('descricao_turno');
        $this->tiposTurmas = new TipoTurma;
        $this->tiposTurmas = $this->tiposTurmas->all()->sortBy('tipo_turma');

    }

    public function index()
    {
       /*  $turmas = $this->repositorio
                ->orderBy('fk_id_turno', 'asc')
                ->orderBy('tb_sub_niveis_ensino.sub_nivel_ensino')
                ->orderBy('nome_turma')
                ->paginate(); */
        $turmas = Turma::select ('*')
                            ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma' )
                            ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
                            ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
                            ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno')
                            ->where('tb_anos_letivos.fk_id_unidade_ensino', '=', '1')                            
                            ->orderBy('tb_anos_letivos.ano', 'desc')
                            ->orderBy('tb_turnos.descricao_turno', 'asc')
                            ->orderBy('tb_sub_niveis_ensino.sub_nivel_ensino', 'asc')
                            ->orderBy('nome_turma', 'asc')
                            ->paginate();
                        
        return view('secretaria.paginas.turmas.index', [
                    'turmas' => $turmas,                       
        ]);
    }

    public function create()
    {  
        $tiposTurma = TipoTurma::select('*')
                                    ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
                                    ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')   
                                    ->where('tb_anos_letivos.fk_id_unidade_ensino', '=', '1')
                                    ->where('tb_anos_letivos.situacao', '=', '1')
                                    ->orderBy('tb_anos_letivos.ano', 'desc')
                                    ->orderBy('tb_sub_niveis_ensino.sub_nivel_ensino', 'asc')
                                    ->orderBy('tb_tipos_turmas.tipo_turma', 'asc')
                                    ->get();

       
        return view('secretaria.paginas.turmas.create', [
            'turnos' => $this->turnos,
            'turmas' => $this->repositorio,
            'tiposTurmas' => $tiposTurma,     
        ]);
    }

    public function store(StoreUpdateTurma $request )
    {
        $dados = $request->all();
        /* $sit = $this->verificarSituacao($dados);
        $dados = array_merge($dados, $sit); */
       // dd($dados);
        $this->repositorio->create($dados);

        return redirect()->route('turmas.index');
    }

    public function show($id)
    {
        $turma = $this->repositorio->where('id_turma', $id)->with('tipoTurma', 'usuario')->first();

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
        
        return view('secretaria.paginas.turmas.index', [
            'turmas' => $turmas,
            'filtros' => $filtros,
        ]);
    }

    public function edit($id)
    {
        $turma = $this->repositorio->where('id_turma', $id)->with('tipoTurma')->first();
        
        if (!$turma)
            return redirect()->back();
                
            //$this->tiposTurmas = $this->tiposTurmas->all()->with('subNivelEnsino');

        return view('secretaria.paginas.turmas.edit',[
            'turma'         => $turma,
            'turnos'        => $this->turnos,
            'tiposTurmas'   => $this->tiposTurmas,
        ]);
    }

    public function update(StoreUpdateTurma $request, $id)
    {
        $turma = $this->repositorio->where('id_turma', $id)->first();

        if (!$turma)
            return redirect()->back();
        
       /*  $sit = $this->verificarSituacao($request->all());
        
        $request->merge($sit); */

        $turma->where('id_turma', $id)->update($request->except('_token', '_method'));

        return redirect()->route('turmas.index');
    }

    /**
     * Verifica se a situação foi ativada
     */
    /* public function verificarSituacao(array $dados)
    {
        if (!array_key_exists('situacao_turma', $dados))
            return ['situacao_turma' => '0'];
        else
             return ['situacao_turma' => '1'];            
    } */
}
