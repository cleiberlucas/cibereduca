<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PeriodoLetivo extends Model 
{
    protected $table = "tb_periodos_letivos";
    protected $primaryKey = 'id_periodo_letivo';
    
    public $timestamps = false;
        
    protected $fillable = ['periodo_letivo',  'fk_id_ano_letivo', 'data_inicio', 'data_fim', 'quantidade_dias_letivos', 'situacao', 'fk_id_user'];
   
    public function search($filtro = null)
    {
        $resultado = $this->join('tb_anos_letivos', 'fk_id_ano_letivo', 'id_ano_letivo')
                            ->where('periodo_letivo', 'like', "%{$filtro}%") 
                            ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
                            ->orderBy('ano', 'desc')
                            ->orderBy('periodo_letivo')                                         
                            ->paginate();
        
        return $resultado;
    }

    public function getPeriodosLetivos($situacao)
    {
        return $this->join('tb_anos_letivos', 'fk_id_ano_letivo', 'id_ano_letivo')
                    ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
                    ->where('tb_periodos_letivos.situacao', $situacao)
                    ->orderBy('periodo_letivo')->get();
    }

    public function getPeriodosLetivosAno($idAnoLetivo)
    {
        return $this->join('tb_anos_letivos', 'fk_id_ano_letivo', 'id_ano_letivo')
                    ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
                    ->where('id_ano_letivo', $idAnoLetivo)
                    ->orderBy('periodo_letivo')
                    ->get();
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
