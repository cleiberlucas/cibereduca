<?php

namespace App\Models\Financeiro;

use Illuminate\Database\Eloquent\Model;

class BoletoRetorno extends Model
{
    protected $table = "tb_boletos_retorno";
    protected $primaryKey = 'id_boleto_retorno';
    
    public $timestamps = false;
        
    protected $fillable = [
        'fk_id_boleto', 
        'sequencial_retorno_banco', 
        'valor_tarifa',
        'ocorrencia',
        'data_gravacao',
        'obs',        
    ];
   
}
