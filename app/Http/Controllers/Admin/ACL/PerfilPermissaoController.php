<?php

namespace App\Http\Controllers\Admin\ACL;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Perfil;
use App\Models\Permissao;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        $this->authorize('Perfil Permissão Ver');   
        $perfil = $this->perfil->where('id_perfil', $id_perfil)->first();

        if (!$perfil)
            return redirect()->back();
        
        $permissoes = $perfil->permissoes()->orderBy('permissao')->paginate();

        return view('admin.paginas.perfis.permissoes.permissoes', [
            'perfil' => $perfil,
            'permissoes' => $permissoes,
        ]);
    }

    public function permissoesAdd(Request $request, $id_perfil)
    {
        $this->authorize('Perfil Permissão Adicionar');   
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
                    ->with('atencao', 'Escolha pelo menos uma permissão.');
        }

        //Gerando log de permissões liberadas
        $log = 'Usuário '.Auth::id(). ' - Perfil Permissão Cadastrar - Perfil: '.$id_perfil. ' - Permissões:';        
        foreach($request->permissoes as $perm){
            $log .= ' # '.$perm;
        }
        Log::channel('perfil_permissao')->info($log);

        $perfil->permissoes()->attach($request->permissoes);

        return redirect()->route('perfis.permissoes', $perfil->id_perfil)->with('sucesso', 'Permissão(ões) adicionada(s) com sucesso.');
    }

    public function removerPermissoesPerfil($id_perfil, $id_permissao)
    {
        $this->authorize('Perfil Permissão Remover');   
        $perfil = $this->perfil->where('id_perfil', $id_perfil)->first();
        $permissao = $this->permissao->where('id_permissao', $id_permissao)->first();

        if (!$perfil || !$permissao)
            return redirect()->back();

        $perfil->permissoes()->detach($permissao);
        Log::channel('perfil_permissao')->info('Usuário '.Auth::id(). ' - Perfil Permissão Remover - Perfil: '.$id_perfil. ' - Permissão: '.$id_permissao);   

        return redirect()->route('perfis.permissoes', $perfil->id_perfil)->with('sucesso', 'Permissão removida com sucesso.');
    }
}
