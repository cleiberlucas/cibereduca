<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateTipoDocIdentidade;
use App\Models\TipoDocIdentidade;
use Illuminate\Http\Request;

class TipoDocIdentidadeController extends Controller
{
    private $repositorio;
    
    public function __construct(TipoDocIdentidade $tipoDocIdentidade)
    {
        $this->repositorio = $tipoDocIdentidade;

    }

    public function index()
    {
        $tiposDocIdentidade = $this->repositorio->paginate();
        
        return view('admin.paginas.tiposdocidentidade.index', [
                    'tiposDocIdentidade' => $tiposDocIdentidade,
        ]);
    }

    public function create()
    {
       // dd(view('admin.paginas.tiposdocidentidade.create'));
        return view('admin.paginas.tiposdocidentidade.create');
    }

    public function store(StoreUpdateTipoDocIdentidade $request )
    {
        $dados = $request->all();
        
        $this->repositorio->create($dados);

        return redirect()->route('tiposdocidentidade.index');
    }

    public function show($id)
    {
        $tipoDocIdentidade = $this->repositorio->where('id_tipo_doc_identidade', $id)->first();

        if (!$tipoDocIdentidade)
            return redirect()->back();

        return view('admin.paginas.tiposdocidentidade.show', [
            'tipoDocIdentidade' => $tipoDocIdentidade
        ]);
    }

    public function destroy($id)
    {
        $tipoDocIdentidade = $this->repositorio->where('id_tipo_doc_identidade', $id)->first();

        if (!$tipoDocIdentidade)
            return redirect()->back();

        $tipoDocIdentidade->where('id_tipo_doc_identidade', $id)->delete();
        return redirect()->route('tiposdocidentidade.index');
    }

    public function search(Request $request)
    {
        $filtros = $request->except('_token');
        $tiposDocIdentidade = $this->repositorio->search($request->filtro);
        
        return view('admin.paginas.tiposdocidentidade.index', [
            'tiposDocIdentidade' => $tiposDocIdentidade,
            'filtros' => $filtros,
        ]);
    }

    public function edit($id)
    {
        $tipoDocIdentidade = $this->repositorio->where('id_tipo_doc_identidade', $id)->first();
        
        if (!$tipoDocIdentidade)
            return redirect()->back();
                
        return view('admin.paginas.tiposdocidentidade.edit',[
            'tipoDocIdentidade' => $tipoDocIdentidade,
        ]);
    }

    public function update(StoreUpdateTipoDocIdentidade $request, $id)
    {
        $tipoDocIdentidade = $this->repositorio->where('id_tipo_doc_identidade', $id)->first();

        if (!$tipoDocIdentidade)
            return redirect()->back();
        
        $tipoDocIdentidade->where('id_tipo_doc_identidade', $id)->update($request->except('_token', '_method'));

        return redirect()->route('tiposdocidentidade.index');
    }

}
