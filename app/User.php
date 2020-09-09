<?php

namespace App;

use App\Models\Secretaria\UserUnidadeEnsino;
use App\Models\Traits\UserACLTrait;
use App\Models\UnidadeEnsino;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable, UserACLTrait;

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
     * 
     */
    public function unidadesEnsino()
    {
        //join M:M user X UNIDADE ENSINO
        return $this->belongsToMany(UserUnidadeEnsino::class, 'tb_usuarios_unidade_ensino', 'fk_id_user', 'fk_id_unidade_ensino');
    }

    /**
     * Consulta perfil do usuário em determinada unidade de ensino
     * Somente ativos
     */
    public function getPerfilUsuarioUnidadeEnsino($idUnidade, $idUsuario)
    {
        return $this
            ->join('tb_usuarios_unidade_ensino', 'fk_id_user', 'id')
            ->where('fk_id_unidade_ensino', $idUnidade)
            ->where('fk_id_user', $idUsuario)
            ->where('situacao_vinculo', 1) 
            ->first();
    }

    /* public function perfil()
    {
        //join M:M user X perfil
        return $this->belongsTo(Perfil::class, 'tb_perfis', 'fk_id_user', 'id');
    }
 */
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

    public static function getUnidadeEnsinoSelecionada()
    {
        $situacaoUnidade = UnidadeEnsino::where('id_unidade_ensino', '=', session()->get('id_unidade_ensino'))->first();
        /* dd($situacaoUnidade->situacao); */
        if (isset($situacaoUnidade->situacao) && $situacaoUnidade->situacao == 1)
            return $situacaoUnidade->id_unidade_ensino;
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

    public function getVerificaPermissaoUsuario(String $permissao)
    {
        return in_array($permissao, $this->getPermissoesUsuario());
    }

    public function isAdmin(): bool
    {
        return in_array($this->email, config('acl.admins'));
    }
}
