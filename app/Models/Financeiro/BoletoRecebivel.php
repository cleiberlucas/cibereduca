<?php

namespace App\Models\Financeiro;

use Illuminate\Database\Eloquent\Model;

class BoletoRecebivel extends Model
{
    protected $table = "tb_boletos_recebiveis";
    protected $primaryKey = 'id_boleto_recebivel';
    
    public $timestamps = false;
        
    protected $fillable = [
        'fk_id_boleto', 
        'fk_id_recebivel', 
        
    ];
   
}
