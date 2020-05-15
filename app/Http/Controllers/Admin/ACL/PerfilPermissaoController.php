<?php

namespace App\Http\Controllers\Admin\ACL;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Perfil;
use App\Models\Permissao;

class PerfilPermissaoController extends Controller
{
    protected $perfil, $permissao;
    
    public function __construct(Perfil $perfil, Permissao $permissao)
    {
        $this->perfil = $perfil;
        $this->permissao = $permissao;
    }

    //Permissões de um perfil
    public function permissoes($id_perfil)
    {
        $perfil = $this->perfil->where('id_perfil', $id_perfil)->first();

        if (!$perfil)
            return redirect()->back();
        
        $permissoes = $perfil->permissoes()->paginate();

        return view('admin.paginas.perfis.permissoes.permissoes', [
            'perfil' => $perfil,
            'permissoes' => $permissoes,
        ]);
    }

    public function permissoesAdd(Request $request, $id_perfil)
    {
        $perfil = $this->perfil->where('id_perfil', $id_perfil)->first();

        if (!$perfil)
            return redirect()->back();

        $filtros = $request->except('_token');
        $permissoes = $perfil->permissoesLivres($request->filtro);        
        
        return view('admin.paginas.perfis.permissoes.add', [
            'perfil' => $perfil,
            'permissoes' => $permissoes,
            'filtros' => $filtros,
        ]);
    }

    public function vincularPermissoesPerfil(Request $request, $id_perfil)
    {
        $perfil = $this->perfil->where('id_perfil', $id_perfil)->first();

        if (!$perfil)
            return redirect()->back();

        if (!$request->permissoes || count($request->permissoes) == 0){
            return redirect()
                    ->back()
                    ->with('info', 'Escolha pelo menos uma permissão.');
        }

        $perfil->permissoes()->attach($request->permissoes);

        return redirect()->route('perfis.permissoes', $perfil->id_perfil);
    }

    public function removerPermissoesPerfil($id_perfil, $id_permissao)
    {
        $perfil = $this->perfil->where('id_perfil', $id_perfil)->first();
        $permissao = $this->permissao->where('id_permissao', $id_permissao)->first();

        if (!$perfil || !$permissao)
            return redirect()->back();

        $perfil->permissoes()->detach($permissao);

        return redirect()->route('perfis.permissoes', $perfil->id_perfil);
    }
}
