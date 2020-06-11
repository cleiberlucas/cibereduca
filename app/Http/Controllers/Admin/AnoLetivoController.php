<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateAnoLetivo;
use App\Models\AnoLetivo;
use App\Models\UnidadeEnsino;
use App\User;
use Illuminate\Http\Request;

class AnoLetivoController extends Controller
{
    private $repositorio, $unidadesEnsino;
    
    public function __construct(AnoLetivo $anoLetivo)
    {
        $this->repositorio = $anoLetivo;
        $this->unidadesEnsino = new UnidadeEnsino();   
    }

    public function index()
    {
        $anosLetivos = $this->repositorio->where('fk_id_unidade_ensino', '=', User::getUnidadeEnsinoSelecionada())->paginate();
        
        return view('admin.paginas.anosletivos.index', [
                    'anosletivos' => $anosLetivos,
        ]);
    }

    public function create()
    {
       // dd(view('admin.paginas.anosletivos.create'));
        return view('admin.paginas.anosletivos.create', [
            'unidadesEnsino' => $this->unidadesEnsino->unidadesEnsino(User::getUnidadeEnsinoSelecionada()),
        ]);
    }

    public function store(StoreUpdateAnoLetivo $request )
    {
        $dados = $request->all();
        $sit = $this->verificarSituacao($dados);
        $dados = array_merge($dados, $sit);
       // dd($dados);
        $this->repositorio->create($dados);

        return redirect()->route('anosletivos.index');
    }

    public function show($id)
    {        
        $anoLetivo = $this->repositorio->where('fk_id_unidade_ensino', '=', User::getUnidadeEnsinoSelecionada())
                                        ->where('id_ano_letivo', $id)->first();
        
        if (!$anoLetivo)
            return redirect()->back();

        return view('admin.paginas.anosletivos.show', [
            'anoletivo' => $anoLetivo
        ]);
    }

    public function destroy($id)
    {
        $anoLetivo = $this->repositorio->where('id_ano_letivo', $id)->first();

        if (!$anoLetivo)
            return redirect()->back();

        $anoLetivo->where('id_ano_letivo', $id)->delete();
        return redirect()->route('anosletivos.index');
    }

    public function search(Request $request)
    {
        $filtros = $request->except('_token');
        $anosLetivos = $this->repositorio->search($request->filtro);
        
        return view('admin.paginas.anosletivos.index', [
            'anosletivos' => $anosLetivos,
            'filtros' => $filtros,
        ]);
    }

    public function edit($id)
    {
        $anoLetivo = $this->repositorio->where('fk_id_unidade_ensino', '=', User::getUnidadeEnsinoSelecionada())
                                        ->where('id_ano_letivo', $id)->first();
        
        if (!$anoLetivo)
            return redirect()->back();
                
        return view('admin.paginas.anosletivos.edit',[
            'anoLetivo' => $anoLetivo,
            'unidadesEnsino' => $this->unidadesEnsino->unidadesEnsino(User::getUnidadeEnsinoSelecionada()),
        ]);
    }

    public function update(StoreUpdateAnoLetivo $request, $id)
    {
        $anoLetivo = $this->repositorio->where('id_ano_letivo', $id)->first();

        if (!$anoLetivo)
            return redirect()->back();
        
        $sit = $this->verificarSituacao($request->all());
        
        $request->merge($sit);

        $anoLetivo->where('id_ano_letivo', $id)->update($request->except('_token', '_method'));

        return redirect()->route('anosletivos.index');
    }

    /**
     * Verifica se a situação foi ativada
     */
    public function verificarSituacao(array $dados)
    {
        if (!array_key_exists('situacao', $dados))
            return ['situacao' => '0'];
        else
             return ['situacao' => '1'];            
    }
}
