<?php

namespace App\Models\Financeiro;

use Illuminate\Database\Eloquent\Model;

class Acrescimo extends Model
{
    protected $table = "tb_acrescimos";
    protected $primaryKey = 'id_acrescimo';
    
    public $timestamps = false;
        
    protected $fillable = [
        'fk_id_recebivel',
        'fk_id_conta_contabil_acrescimo', 
        'valor_acrescimo',
        'valor_desconto_acrescimo',         
        'valor_total_acrescimo',        
        'desconto_acrescimo_autorizado',      
        'motivo_desconto_acrescimo',
        'fk_id_usuario_desconto_acrescimo',
        'data_autoriza_desconto_acrescimo',
        'data_lancamento'
    ];
   
}
