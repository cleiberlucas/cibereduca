<?php

namespace App\Models\Pedagogico;

use App\Models\PeriodoLetivo;
use App\Models\Secretaria\Turma;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TurmaPeriodoLetivo extends Model
{ 
    protected $table = "tb_turmas_periodos_letivos";
    protected $primaryKey = 'id_turma_periodo_letivo';
    
    public $timestamps = false;
    
    protected $fillable = ['fk_id_turma', 'fk_id_periodo_letivo', 'situacao'];
   
    public function turma()
    {       
        return $this->belongsTo(Turma::class, 'fk_id_turma', 'id_turma');
    }

    public function periodoLetivo()
    {
        return $this->belongsTo(PeriodoLetivo::class, 'fk_id_periodo_letivo', 'id_periodo_letivo');
    }
    
    public function getPeriodosTurma()
    {       
        $turmaPeriodoLetivo = $this->select('tb_periodos_letivos.*')                                                                                                
                                    ->join('tb_turmas', 'tb_turmas_periodos_letivos.fk_id_turma', 'id_turma')          
                                    ->join('tb_periodos_letivos', 'tb_turmas.id_turma', 'tb_periodos_letivos.fk_id_turma')                                       
                                    ->get();
        $periodosLetivos = [];        
        foreach ($turmaPeriodoLetivo as $periodoLetivo){        
            array_push($periodosLetivos, $periodoLetivo['periodo_letivo']);            
        }

        return($periodosLetivos);
    }

}
