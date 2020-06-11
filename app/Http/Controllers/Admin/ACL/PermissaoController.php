<?php

namespace App\Http\Controllers\Admin\ACL;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdatePermissao;
use App\Models\Permissao;
use Illuminate\Http\Request;

class PermissaoController extends Controller
{
    private $repositorio;
    
    public function __construct(Permissao $permissao)
    {
        $this->repositorio = $permissao;

    }

    public function index()
    {
        $permissoes = Permissao::orderBy('permissao')->paginate();
        
        return view('admin.paginas.permissoes.index', [
                    'permissoes' => $permissoes,
        ]);
    }

    public function create()
    {
       // dd(view('admin.paginas.permissoes.create'));
        return view('admin.paginas.permissoes.create');
    }

    public function store(StoreUpdatePermissao $request )
    {
        $dados = $request->all();
        $this->repositorio->create($dados);

        return redirect()->route('permissoes.index');
    }

    public function show($id)
    {
        $permissao = $this->repositorio->where('id_permissao', $id)->first();

        if (!$permissao)
            return redirect()->back();

        return view('admin.paginas.permissoes.show', [
            'permissao' => $permissao
        ]);
    }

    public function destroy($id)
    {
        $permissao = $this->repositorio->where('id_permissao', $id)->first();

        if (!$permissao)
            return redirect()->back();

        $permissao->where('id_permissao', $id)->delete();
        return redirect()->route('permissoes.index');
    }

    public function search(Request $request)
    {
        $filtros = $request->except('_token');
        $permissoes = $this->repositorio->search($request->filtro);
        
        return view('admin.paginas.permissoes.index', [
            'permissoes' => $permissoes,
            'filtros' => $filtros,
        ]);
    }

    public function edit($id)
    {

        $permissao = $this->repositorio->where('id_permissao', $id)->first();
        
        if (!$permissao)
            return redirect()->back();
                
        return view('admin.paginas.permissoes.edit',[
            'permissao' => $permissao,
        ]);
    }

    public function update(StoreUpdatePermissao $request, $id)
    {
        $permissao = $this->repositorio->where('id_permissao', $id)->first();

        if (!$permissao)
            return redirect()->back();

        $permissao->where('id_permissao', $id)->update($request->except('_token', '_method'));

        return redirect()->route('permissoes.index');
    }
}
