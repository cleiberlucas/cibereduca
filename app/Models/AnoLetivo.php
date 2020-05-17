<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnoLetivo extends Model
{
    protected $table = "tb_anos_letivos";
    protected $primaryKey = 'id_ano_letivo';
    
    public $timestamps = false;
        
    protected $fillable = ['ano',  'fk_id_unidade_ensino', 'media_minima_aprovacao', 'situacao'];
   
    public function search($filtro = null)
    {
        $resultado = $this->where('ano', '=', "{$filtro}")                            
                            ->paginate();
        
        return $resultado;
    }
}
