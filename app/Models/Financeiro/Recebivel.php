<?php

namespace App\Models\Financeiro;

use Illuminate\Database\Eloquent\Model;

class Recebivel extends Model
{
    protected $table = "tb_recebiveis";
    protected $primaryKey = 'id_recebivel';
    
    public $timestamps = false;
        
    protected $fillable = [
        'fk_id_unidade_ensino', 
        'fk_id_matricula', 
        'fk_id_conta_contabil_principal',
        'valor_principal',
        'valor_desconto_principal',
        'valor_total',
        'desconto_autorizado',
        'motivo_desconto',
        'fk_id_usuario_desconto',
        'data_autoriza_desconto',
        'data_vencimento',
        'parcela',
        'obs_recebivel',
        'fk_id_situacao_recebivel',
        'substituido_por',
        'fk_id_usuario_cadastro',
        'data_cadastro',
    ];
   
}
