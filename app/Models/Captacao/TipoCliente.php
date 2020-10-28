<?php

namespace App\Models\Captacao;

use Illuminate\Database\Eloquent\Model;

class TipoCliente extends Model
{
    protected $table = "tb_tipos_cliente";
    protected $primaryKey = 'id_tipo_cliente';
    
    public $timestamps = false;
        
    protected $fillable = ['tipo_cliente'];
   
    public function search($filtro = null)
    {
        $resultado = $this
            ->where('tipo_cliente', 'like', "%{$filtro}%")                             
            ->paginate();
        
        return $resultado;
    }
}
