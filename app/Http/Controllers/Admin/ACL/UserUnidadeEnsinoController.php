<?php

namespace App\Http\Controllers\Admin\ACL;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateUserUnidadeEnsino;
use App\Models\Perfil;
use Illuminate\Http\Request;
use App\User;
use App\Models\UnidadeEnsino;
use App\Models\Secretaria\UserUnidadeEnsino;

class UserUnidadeEnsinoController extends Controller
{
    protected $user, $unidadeEnsino;
    
    public function __construct(User $user, UnidadeEnsino $unidadeEnsino)
    {
        $this->user = $user;
        $this->unidadeEnsino = $unidadeEnsino;
    }

    //Unidades de um usuário
    public function unidadesEnsino($id)
    {
        $user = $this->user->where('id', $id)->first();

        if (!$user)
            return redirect()->back();
        
        $unidadesEnsino = UnidadeEnsino::select('id_usuario_unidade_ensino', 
                                                'tb_unidades_ensino.id_unidade_ensino', 
                                                'tb_unidades_ensino.nome_fantasia', 'situacao_vinculo',
                                                'tb_perfis.id_perfil', 'tb_perfis.perfil',
                                                'tb_usuarios_unidade_ensino.fk_id_perfil',
                                                )  
                                        ->join('tb_usuarios_unidade_ensino', 'tb_usuarios_unidade_ensino.fk_id_unidade_ensino', 'tb_unidades_ensino.id_unidade_ensino')
                                        ->join('users', 'id', 'tb_usuarios_unidade_ensino.fk_id_user')
                                        ->leftJoin('tb_perfis', 'fk_id_perfil', 'id_perfil')
                                        ->where('tb_usuarios_unidade_ensino.fk_id_user', $id)
                                        ->orderBy('nome_fantasia')
                                        ->get();

        $unidadesEnsinoLivres = $user->unidadesEnsinoLivres();
        $perfis = new Perfil;

        $perfis = $perfis->all()->sortBy('perfil');
        
        return view('admin.paginas.users.unidadesensino.unidadesensino', [
            'user' => $user,
            'unidadesEnsino' => $unidadesEnsino,
            'unidadesEnsinoLivres' => $unidadesEnsinoLivres,
            'perfis' => $perfis,
        ]);
    }

    public function unidadesEnsinoAdd(Request $request, $id)
    {
        $user = $this->user->where('id', $id)->first();

        if (!$user)
            return redirect()->back();

        $filtros = $request->except('_token');
        $unidadesEnsino = $user->unidadesLivres($request->filtro);         
        
        return view('admin.paginas.users.unidadesensino.add', [
            'user' => $user,
            'unidadesEnsino' => $unidadesEnsino,
            'filtros' => $filtros,
        ]);
    }

    public function vincularUnidadesUser(Request $request, $id)
    {
        $user = $this->user->where('id', $id)->first();

        if (!$user)
            return redirect()->back();

        //dd($request);

        if (!$request->unidadesEnsino || count($request->unidadesEnsino) == 0){
            return redirect()
                    ->back()
                    ->with('info', 'Escolha pelo menos uma Unidade de Ensino.');
        }

        //dd($request->unidadesEnsino);
        $user->unidadesEnsino()->attach($request->unidadesEnsino); 

        return redirect()->route('users.unidadesensino', $user->id);
    }

    public function vincularUnidadesRespUser($unidadesEnsino, $id)
    {
        $user = $this->user->where('id', $id)->first();

        if (!$user)
            return redirect()->back()->with('erro', 'usuário não encontrado.'.$id);

       /*  if (!$request->unidadesEnsino || count($request->unidadesEnsino) == 0){
            return redirect()
                    ->back()
                    ->with('info', 'Escolha pelo menos uma Unidade de Ensino.');
        } */

        //dd($request->unidadesEnsino);
        $user->unidadesEnsino()->attach($unidadesEnsino); 

        //return redirect()->route('users.unidadesensino', $user->id);
    }

    public function update(StoreUpdateUserUnidadeEnsino $request, $id)
    {
        $userUnidade = UserUnidadeEnsino::where('id_usuario_unidade_ensino', $id)->first();

        if (!$userUnidade)
            return redirect()->back();
        
        $sit = $this->verificarSituacao($request->all());
        
        $request->merge($sit);

       // dd($userUnidade);
        $userUnidade->where('id_usuario_unidade_ensino', $id)->update($request->except('_token', '_method'));

        return redirect()->route('users.unidadesensino', $userUnidade->user->id);
    }

    //Atualizando perfil RESPONSÁVEL
    public function updateRespUser($request, $id)
    {
        $userUnidade = UserUnidadeEnsino::where('fk_id_user', $id)->first();

        if (!$userUnidade)
            return redirect()->back()->with('erro', 'Usuário não encontrado para atribuir perfil.');
        
       /*  $sit = $this->verificarSituacao($request->all());
        
        $request->merge($sit); */

       // dd($userUnidade);
        $userUnidade->where('fk_id_user', $id)->update($request);

        //return redirect()->route('users.unidadesensino', $userUnidade->user->id);
    }

    public function removerUnidadesUser($id, $id_unidade_ensino)
    {
        $user = $this->user->where('id', $id)->first();
        $unidadeEnsino = $this->unidadeEnsino->where('id_unidade_ensino', $id_unidade_ensino)->first();

        if (!$user || !$unidadeEnsino)
            return redirect()->back();

        $user->unidadesEnsino()->detach($unidadeEnsino);

        return redirect()->route('users.unidadesensino', $user->id);
    }

    /**
     * Verifica se a situação foi ativada
     */
    public function verificarSituacao(array $dados)
    {
        if (!array_key_exists('situacao_vinculo', $dados))
            return ['situacao_vinculo' => '0'];
        else
             return ['situacao_vinculo' => '1'];            
    }

}
