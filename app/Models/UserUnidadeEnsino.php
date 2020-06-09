<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserUnidadeEnsino extends Model
{
    protected $table = "tb_usuarios_unidade_ensino";
    protected $primaryKey = 'id_usuario_unidade_ensino';
    
    public $timestamps = false;
    
    protected $fillable = ['fk_id_user', 'fk_id_unidade_ensino', 'situacao_vinculo'];
   
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
}
