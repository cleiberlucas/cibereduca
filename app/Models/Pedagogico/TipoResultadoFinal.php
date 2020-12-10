<?php

namespace App\Models\Pedagogico;

use Illuminate\Database\Eloquent\Model;

class TipoResultadoFinal extends Model
{
    protected $table = "tb_tipos_resultado_final"; 
    protected $primaryKey = 'id_tipo_resultado_final';
    
    public $timestamps = false;
        
    protected $fillable = ['tipo_resultado_final', 'aprovado', 'situacao'];
   
   /*  public function search($filtro = null)
    {
        $resultado = $this->where('tipo_resultado_final', 'like', "%{$filtro}%")                                                         
                            ->paginate();
        
        return $resultado;
    } */

    public function getTiposResultadoFinal(){
        return TipoResultadoFinal::select('*')
            ->orderBy('tipo_resultado_final')
            ->get();
    }
}
