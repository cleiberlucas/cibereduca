<?php

namespace App\Models\Financeiro;

use Illuminate\Database\Eloquent\Model;

class Recebimento extends Model
{
    protected $table = "tb_recebimentos";
    protected $primaryKey = 'id_recebimento';
    
    public $timestamps = false;
        
    protected $fillable = [
        'fk_id_recebivel', 
        'fk_id_forma_pagamento',         
        'valor_recebido',        
        'data_recebimento',
        'data_credito',
        'data_registra_recebimento',
        'numero_recibo',
        'codigo_validacao',
        'fk_id_usuario_recebimento',        
    ];
   
}
