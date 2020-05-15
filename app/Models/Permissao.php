<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permissao extends Model
{
    protected $table = "tb_permissoes";
    protected $primaryKey = 'id_permissao';
    
    public $timestamps = false;
    
    protected $fillable = ['permissao', 'descricao_permissao'];
   
    public function search($filtro = null)
    {
        $resultado = $this->where('permissao', 'LIKE', "%{$filtro}%")
                            ->orWhere('descricao_permissao', 'LIKE', "%{$filtro}%")
                            ->paginate();
        
        return $resultado;
    }

    /**
     * Ler perfis
     */
    public function perfis()
    {
        return $this->belongsToMany(Perfil::class, 'tb_perfis_permissoes', 'fk_id_permissao');
    }
}
