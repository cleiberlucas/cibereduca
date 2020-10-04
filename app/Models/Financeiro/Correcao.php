<?php

namespace App\Models\Financeiro;

use Illuminate\Database\Eloquent\Model;

class Correcao extends Model
{
    protected $table = "tb_configura_correcoes";
    protected $primaryKey = 'id_configura_correcao';
    
    public $timestamps = false;
        
    protected $fillable = [
        'fk_id_conta_contabil', 
        'indice_correcao',         
        'aplica_correcao',        
        'fk_id_unidade_ensino',        
    ];
   
}
