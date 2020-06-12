<?php

namespace App\Http\Controllers\Admin\ACL;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdatePerfil;
use App\Models\Perfil;
use Illuminate\Http\Request;


class PerfilController extends Controller
{
    private $repositorio;
    
    public function __construct(Perfil $perfil)
    {
        $this->repositorio = $perfil;

    }

    public function index()
    {
        $perfis = $this->repositorio->paginate();
        
        return view('admin.paginas.perfis.index', [
                    'perfis' => $perfis,
        ]);
    }

    public function create()
    {
        $this->authorize('Perfil Cadastrar');
        return view('admin.paginas.perfis.create');
    }

    public function store(StoreUpdatePerfil $request )
    {
        $dados = $request->all();
        $this->repositorio->create($dados);

        return redirect()->route('perfis.index');
    }

    public function show($id)
    {
        $this->authorize('Perfil Ver');
        $perfil = $this->repositorio->where('id_perfil', $id)->first();

        if (!$perfil)
            return redirect()->back();

        return view('admin.paginas.perfis.show', [
            'perfil' => $perfil
        ]);
    }

    public function destroy($id)
    {
        $perfil = $this->repositorio->where('id_perfil', $id)->first();

        if (!$perfil)
            return redirect()->back();

        $perfil->where('id_perfil', $id)->delete();
        return redirect()->route('perfis.index');
    }

    public function search(Request $request)
    {
        $filtros = $request->except('_token');
        $perfis = $this->repositorio->search($request->filtro);
        
        return view('admin.paginas.perfis.index', [
            'perfis' => $perfis,
            'filtros' => $filtros,
        ]);
    }

    public function edit($id)
    {
        $this->authorize('Perfil Alterar');
        $perfil = $this->repositorio->where('id_perfil', $id)->first();
        
        if (!$perfil)
            return redirect()->back();
                
        return view('admin.paginas.perfis.edit',[
            'perfil' => $perfil,
        ]);
    }

    public function update(StoreUpdatePerfil $request, $id)
    {
        $perfil = $this->repositorio->where('id_perfil', $id)->first();

        if (!$perfil)
            return redirect()->back();

        $perfil->where('id_perfil', $id)->update($request->except('_token', '_method'));

        return redirect()->route('perfis.index');
    }
}
