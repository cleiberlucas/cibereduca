<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{  
    protected $table = "tb_perfis";
    protected $primaryKey = 'id_perfil';

    public $timestamps = false;
    
    protected $fillable = ['perfil', 'descricao_perfil'];
   
    public function search($filtro = null)
    {
        $resultado = $this->where('perfil', 'LIKE', "%{$filtro}%")
                            ->orWhere('descricao_perfil', 'LIKE', "%{$filtro}%")
                            ->paginate();
        
        return $resultado;
    }

    /**
     * Ler todas permissões
     */
    public function permissoes()
    {
        //join M:M perfis X permissoes
        return $this->belongsToMany(Permissao::class, 'tb_perfis_permissoes', 'fk_id_perfil', 'fk_id_permissao');
    }

    /**
     * Ler permissões livres para um perfil
     */
    public function permissoesLivres($filtro = null) 
    {
        $permissoes = Permissao::whereNotIn('id_permissao', function($query){
            $query->select('tb_perfis_permissoes.fk_id_permissao');
            $query->from('tb_perfis_permissoes');
            $query->whereRaw("tb_perfis_permissoes.fk_id_perfil = {$this->id_perfil}");        
            })
            ->where(function ($queryFiltro) use ($filtro){
                if ($filtro)
                    $queryFiltro->where('tb_permissoes.permissao', 'LIKE', "%{$filtro}%");
            })
            ->orderBy('permissao')
            ->paginate();
        //dd($permissoes);
        return $permissoes;
    }
}
