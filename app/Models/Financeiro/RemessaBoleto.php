<?php

namespace App\Models\Financeiro;

use Illuminate\Database\Eloquent\Model;

class RemessaBoleto extends Model
{
    protected $table = "tb_remessas_boletos";
    protected $primaryKey = 'id_remessa_boleto';
    
    public $timestamps = false;
        
    protected $fillable = [
        'fk_id_remessa',
        'fk_id_boleto',         
        
    ];
   
}
