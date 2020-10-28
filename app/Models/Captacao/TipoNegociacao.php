<?php

namespace App\Models\Captacao;

use Illuminate\Database\Eloquent\Model;

class TipoNegociacao extends Model
{
    protected $table = "tb_tipos_negociacao";
    protected $primaryKey = 'id_tipo_negociacao';
    
    public $timestamps = false;
        
    protected $fillable = ['tipo_negociacao'];
   
    public function search($filtro = null)
    {
        $resultado = $this
            ->where('tipo_negociacao', 'like', "%{$filtro}%")                             
            ->paginate();
        
        return $resultado;
    }
}
