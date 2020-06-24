<?php

namespace App\Models\Pedagogico;

use Illuminate\Database\Eloquent\Model;

class TipoAvaliacao extends Model
{
    protected $table = "tb_tipos_avaliacao";
    protected $primaryKey = 'id_tipo_avaliacao';
    
    public $timestamps = false;
        
    protected $fillable = ['tipo_avaliacao', 'sigla_avaliacao', 'situacao'];
   
    public function search($filtro = null)
    {
        $resultado = $this->where('tipo_avaliacao', 'like', "%{$filtro}%")                                                         
                            ->paginate();
        
        return $resultado;
    }

    public function getTiposAvaliacao($situacao){
        return $this
                    ->where('situacao', $situacao)
                    ->orderBy('tipo_avaliacao')->get();
    }
}
