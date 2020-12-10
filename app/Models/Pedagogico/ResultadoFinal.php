<?php

namespace App\Models\Pedagogico;

use Illuminate\Database\Eloquent\Model;

class ResultadoFinal extends Model
{
    protected $table = "tb_resultado_final"; 
    protected $primaryKey = 'id_resultado_final';
    
    public $timestamps = false;
        
    protected $fillable = ['fk_id_matricula', 'fk_id_tipo_resultado_final', 'fk_id_user'];
   /* 
    public function getTiposResultadoFinal(){
        return TipoResultadoFinal::select('*')
            ->orderBy('tipo_resultado_final')
            ->get();
    } */
}
