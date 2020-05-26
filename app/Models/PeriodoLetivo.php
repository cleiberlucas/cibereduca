<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodoLetivo extends Model
{
    protected $table = "tb_periodos_letivos";
    protected $primaryKey = 'id_periodo_letivo';
    
    public $timestamps = false;
        
    protected $fillable = ['periodo_letivo',  'fk_id_ano_letivo', 'data_inicio', 'data_fim', 'situacao', 'fk_id_user'];
   
    public function search($filtro = null)
    {
        $resultado = $this->where('periodo_letivo', 'like', "%{$filtro}%") 
                            ->paginate();
        
        return $resultado;
    }

    public function anoLetivo()
    {       
        return $this->belongsTo(AnoLetivo::class, 'fk_id_ano_letivo', 'id_ano_letivo');
    }

    public function usuario()
    {       
        return $this->belongsTo(User::class, 'fk_id_user', 'id');
    }
}
