<?php

namespace App\Models\Financeiro;

use Illuminate\Database\Eloquent\Model;

class DadoBancario extends Model
{
    protected $table = "tb_dados_bancarios";
    protected $primaryKey = 'id_dado_bancario';
    
    public $timestamps = false;
        
    protected $fillable = [
        'fk_id_unidade_ensino', 
        'banco', 
        'agencia',
        'conta',
        'convenio',
        'carteira',
        'aceite',
        'especie',
        'protesto',
        'pagamento_apos_vencimento',
        'instrucoes_multa_juros',
        'local_pagamento',
        
    ];
   
}
