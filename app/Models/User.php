<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function search($filtro = null)
    {
        $resultado = $this->where('email', 'LIKE', "%{$filtro}%")
                            ->orWhere('name', 'LIKE', "%{$filtro}%")
                            ->paginate();
        
        return $resultado;
    }

    /**
     * Ler todos documentos entregues
     */
    public function unidadesEnsino()
    {
        //join M:M matricula X documentos
        return $this->belongsToMany(UnidadeEnsino::class, 'tb_usuarios_unidade_ensino', 'fk_id_unidade_ensino', 'fk_id_user');
    }

    /**
     * Ler permissões livres para um perfil
     */
    public function unidadesEnsinoLivres($filtro = null) 
    {
        $unidadesEnsino = UnidadeEnsino::whereNotIn('id_unidade_ensino', function($query){
            $query->select('tb_usuarios_unidade_ensino.fk_id_unidade_ensino');
            $query->from('tb_usuarios_unidade_ensino');
            $query->whereRaw("tb_usuarios_unidade_ensino.fk_id_user = {$this->id}");        
            })
            ->where(function ($queryFiltro) use ($filtro){
                if ($filtro)
                    $queryFiltro->where('tb_unidades_ensino.nome_fantasia', 'LIKE', "%{$filtro}%");
            })
            ->get();
        
        return $unidadesEnsino;
    }    
}
