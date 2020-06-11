<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateTipoTurma;
use App\Models\AnoLetivo;
use App\Models\SubNivelEnsino;
use App\Models\TipoTurma;
use App\User;
use Illuminate\Http\Request;

class TipoTurmaController extends Controller
{
    private $repositorio, $anosLetivos, $subNiveisEnsino;
    
    public function __construct(TipoTurma $tipoTurma)
    {
        $this->repositorio = $tipoTurma;
        $this->anosLetivos = new AnoLetivo();        
        $this->subNiveisEnsino = new SubNivelEnsino();        
    }

    public function index()
    {
        $tiposturmas = $this->repositorio
                                ->join('tb_anos_letivos', 'fk_id_ano_letivo', 'id_ano_letivo')
                                ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
                                ->orderBy('fk_id_ano_letivo', 'desc')
                                ->orderBy('fk_id_sub_nivel_ensino', 'asc')
                                ->orderBy('tipo_turma', 'asc')
                                ->paginate();      
                
        return view('admin.paginas.tiposturmas.index', [
                    'tiposturmas' => $tiposturmas,        
        ]);
    }

    public function create()
    {       
        return view('admin.paginas.tiposturmas.create', [
            'anosLetivos' => $this->anosLetivos->anosLetivosAbertos(User::getUnidadeEnsinoSelecionada()),
            'subNiveisEnsino' => $this->subNiveisEnsino->subNiveisEnsino(),
        ]);
    }

    public function store(StoreUpdateTipoTurma $request )
    {
        $dados = $request->all();        
        $dados = array_merge($dados);
       //dd($this->usuario);
        $this->repositorio->create($dados);

        return redirect()->route('tiposturmas.index');
    }

    public function show($id)
    {
        $tipoTurma = $this->repositorio
                            ->join('tb_anos_letivos', 'fk_id_ano_letivo', 'id_ano_letivo')
                            ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
                            ->where('id_tipo_turma', $id)->with('anoLetivo', 'subNivelEnsino', 'usuario')->first();

        if (!$tipoTurma)
            return redirect()->back();

        return view('admin.paginas.tiposturmas.show', [
            'tipoturma' => $tipoTurma
        ]);
    }

    public function destroy($id)
    {
        $tipoTurma = $this->repositorio->where('id_tipo_turma', $id)->first();

        if (!$tipoTurma)
            return redirect()->back();

        $tipoTurma->where('id_tipo_turma', $id)->delete();
        return redirect()->route('tiposturmas.index');
    }

    public function search(Request $request)
    {
        $filtros = $request->except('_token');
        $tiposturmas = $this->repositorio->search($request->filtro);
        
        return view('admin.paginas.tiposturmas.index', [
            'tiposturmas' => $tiposturmas,
            'filtros' => $filtros,
        ]);
    }

    public function edit($id)
    {
        $tipoTurma = $this->repositorio
                                ->join('tb_anos_letivos', 'fk_id_ano_letivo', 'id_ano_letivo')
                                ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
                                ->where('id_tipo_turma', $id)->first();
             
        if (!$tipoTurma)
            return redirect()->back();
        
        return view('admin.paginas.tiposturmas.edit',[
            'tipoTurma' => $tipoTurma,
            'anosLetivos' => $this->anosLetivos->anosLetivosAbertos(session()->get('id_unidade_ensino')),
            'subNiveisEnsino' => $this->subNiveisEnsino->subNiveisEnsino(),
        ]);
    }

    public function update(StoreUpdateTipoTurma $request, $id)
    {        
        $tipoTurma = $this->repositorio->where('id_tipo_turma', $id)->first();     
        if (!$tipoTurma)
            return redirect()->back();

        $tipoTurma->where('id_tipo_turma', $id)->update($request->except('_token', '_method'));

        return redirect()->route('tiposturmas.index');
    }
}
