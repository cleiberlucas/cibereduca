<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * Fundamental 1
 * Fundamental 2
 */
class SubNivelEnsino extends Model
{
    protected $table = "tb_sub_niveis_ensino";
    protected $primaryKey = 'id_sub_nivel_ensino';
    
    public $timestamps = false;
        
    protected $fillable = ['sub_nivel_ensino', 'fk_id_nivel_ensino'];
   
    public function search($filtro = null)
    {
        $resultado = $this->where('sub_nivel_ensino', '=', "{$filtro}")                            
                            ->paginate();
        
        return $resultado;
    }

    /**
     * Todos os subníveis
     */
    public function subNiveisEnsino()
    {
        return $this->orderBy('sub_nivel_ensino')->get();        
    }

}
