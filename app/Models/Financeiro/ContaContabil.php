<?php

namespace App\Models\Financeiro;

use Illuminate\Database\Eloquent\Model;

class ContaContabil extends Model
{
    protected $table = "tb_contas_contabeis";
    protected $primaryKey = 'id_conta_contabil';
    
    public $timestamps = false;
        
    protected $fillable = [
        'descricao_conta', 
        'numero_contabil', 
        'situacao',        
    ];
   
}
