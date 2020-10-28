<?php

namespace App\Models\Captacao;

use Illuminate\Database\Eloquent\Model;

class TipoDescoberta extends Model
{
    protected $table = "tb_tipos_descoberta";
    protected $primaryKey = 'id_tipo_descoberta';
    
    public $timestamps = false;
        
    protected $fillable = ['tipo_descoberta'];
   
    public function search($filtro = null)
    {
        $resultado = $this
            ->where('tipo_descoberta', 'like', "%{$filtro}%")                             
            ->paginate();
        
        return $resultado;
    }
}
