<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateUnidadeEnsino;
use App\Models\UnidadeEnsino;
use Illuminate\Http\Request;

class UnidadeEnsinoController extends Controller
{
    private $repositorio;

    public function __construct(UnidadeEnsino $unidadeEnsino)
    {
        $this->repositorio = $unidadeEnsino;
    }

    public function index()
    {
        $unidadesEnsino = $this->repositorio->paginate();
        //dd($unidadesEnsino);
        return view('admin.paginas.unidadesensino.index', [
                    'unidadesensino' => $unidadesEnsino,
        ]); 
    }

    public function create()
    {
       // dd(view('admin.paginas.unidadesensino.create'));
        return view('admin.paginas.unidadesensino.create');
    }

    public function store(StoreUpdateUnidadeEnsino $request )
    {
        $dados = $request->all();
        $this->repositorio->create($dados);

        return redirect()->route('unidadesensino.index');
    }

    public function show($id)
    {
        $unidadeEnsino = $this->repositorio->where('id_unidade_ensino', $id)->first();

        if (!$unidadeEnsino)
            return redirect()->back();

        return view('admin.paginas.unidadesensino.show', [
            'UnidadeEnsino' => $unidadeEnsino
        ]);
    }

    public function destroy($id)
    {
        $unidadeEnsino = $this->repositorio->where('id_unidade_ensino', $id)->first();

        if (!$unidadeEnsino)
            return redirect()->back();

        $unidadeEnsino->where('id_unidade_ensino', $id)->delete();
        return redirect()->route('unidadesensino.index');
    }

    public function search(Request $request)
    {
        $filtros = $request->except('_token');
        $unidadesEnsino = $this->repositorio->search($request->filtro);
        
        return view('admin.paginas.unidadesensino.index', [
            'unidadesensino' => $unidadesEnsino,
            'filtros' => $filtros,
        ]);
    }

    public function edit($id)
    {
        $unidadeEnsino = $this->repositorio->where('id_unidade_ensino', $id)->first();

        if (!$unidadeEnsino)
            return redirect()->back();
                
        return view('admin.paginas.unidadesensino.edit',[
            'unidadeensino' => $unidadeEnsino,
        ]);
    }

    public function update(StoreUpdateUnidadeEnsino $request, $id)
    {
        $unidadeEnsino = $this->repositorio->where('id_unidade_ensino', $id)->first();

        if (!$unidadeEnsino)
            return redirect()->back();

        $unidadeEnsino->where('id_unidade_ensino', $id)->update($request->except('_token', '_method'));

        return redirect()->route('unidadesensino.index');
    }
}
