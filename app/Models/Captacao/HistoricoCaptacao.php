<?php

namespace App\Models\Captacao;

use Illuminate\Database\Eloquent\Model;

class HistoricoCaptacao extends Model
{
    protected $table = "tb_historico_captacoes";
    protected $primaryKey = 'id_historico_captacao';
    
    public $timestamps = false;
        
    protected $fillable = ['fk_id_captacao', 'data_contato', 'fk_id_motivo_contato',
        'historico', 'data_lancamento', 'fk_id_usuario_hist_captacao'];
   
    public function search($filtro = null)
    {
        $resultado = $this
            ->where('historico', 'like', "%{$filtro}%")                             
            ->paginate();
        
        return $resultado;
    }
}
