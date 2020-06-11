<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserUnidadeEnsino extends Model
{ 
    protected $table = "tb_usuarios_unidade_ensino";
    protected $primaryKey = 'id_usuario_unidade_ensino';
    
    public $timestamps = false;
    
    protected $fillable = ['fk_id_user', 'fk_id_unidade_ensino', 'fk_id_perfil', 'situacao_vinculo'];
   
    /* public function search($filtro = null)
    {
        $resultado = $this->where('disciplina', 'LIKE', "%{$filtro}%")
                            ->orWhere('sigla_disciplina', 'LIKE', "%{$filtro}%")
                            ->paginate();
        
        return $resultado;
    } */

    public function unidadeEnsino()
    {       
        return $this->belongsTo(UnidadeEnsino::class, 'fk_id_unidade_ensino', 'id_unidade_ensino');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'fk_id_user', 'id');
    }

    public function perfil()
    {
        return $this->belongsTo(Perfil::class, 'fk_id_perfil', 'id_perfil');
    }

    
    public function getPermissoesUsuario()
    {
        $userUnidadeEnsinoPermissoes = new UserUnidadeEnsino;
        $userUnidadeEnsinoPermissoes = $userUnidadeEnsinoPermissoes->select('tb_permissoes.*')                        
                                    ->where('tb_usuarios_unidade_ensino.fk_id_user', '=', Auth::id()) 
                                    ->where('tb_usuarios_unidade_ensino.fk_id_unidade_ensino', '=', session()->get('id_unidade_ensino'))                               
                                    ->join('tb_perfis', 'tb_usuarios_unidade_ensino.fk_id_perfil', 'id_perfil')          
                                    ->join('tb_perfis_permissoes', 'tb_perfis.id_perfil', 'tb_perfis_permissoes.fk_id_perfil')   
                                    ->join('tb_permissoes', 'fk_id_permissao', 'id_permissao')
                                    ->get();
        $permissoes = [];
        //dd($userUnidadeEnsinoPermissoes);
        foreach ($userUnidadeEnsinoPermissoes as $permissao){        
            array_push($permissoes, $permissao['permissao']);            
        }

        return($permissoes);

    }

}
