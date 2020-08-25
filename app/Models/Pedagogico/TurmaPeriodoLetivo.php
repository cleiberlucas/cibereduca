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

    /* Lista de períodos de UMA TURMA p listar no form de cadastro 
        
    */
    public function getTurmaPeriodosLetivos($id_turma)
    {
        $turmaPeriodosLetivos = $this->select
                                        ('tb_turmas_periodos_letivos.id_turma_periodo_letivo',                                    
                                        'tb_turmas_periodos_letivos.situacao',
                                        'tb_turmas_periodos_letivos.fk_id_turma', 
                                        'tb_periodos_letivos.id_periodo_letivo',
                                        'tb_periodos_letivos.periodo_letivo',                                    
                                        'tb_periodos_letivos.data_inicio',   
                                        'tb_periodos_letivos.data_fim',   
                                        'tb_turmas.nome_turma',
                                        'tb_sub_niveis_ensino.sub_nivel_ensino',
                                        'tb_turnos.descricao_turno') 
                                    ->join('tb_periodos_letivos', 'fk_id_periodo_letivo', 'id_periodo_letivo')
                                    ->join('tb_turmas', 'fk_id_turma', 'id_turma')
                                    ->join('tb_turnos', 'fk_id_turno', 'id_turno')
                                    ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
                                    ->join('tb_sub_niveis_ensino', 'fk_id_sub_nivel_ensino', 'id_sub_nivel_ensino')                                    
                                    ->where('tb_turmas_periodos_letivos.fk_id_turma', '=', $id_turma)
                                    ->orderBy('periodo_letivo', 'asc')                                    
                                    ->get();

        return $turmaPeriodosLetivos;
    }

}
