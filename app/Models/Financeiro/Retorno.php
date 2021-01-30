<?php

namespace App\Models\Financeiro;

use Illuminate\Database\Eloquent\Model;

class Retorno extends Model
{
    protected $table = "tb_retornos";
    protected $primaryKey = 'id_retorno';
    
    public $timestamps = false;
        
    protected $fillable = [
        'data_retorno',
        'sequencial_retorno_banco',         
        'data_processamento',
        'fk_id_unidade_ensino',
        'fk_id_user_retorno',        
    ];
   
    /**
     * Relacinar remessa X boletos
     */
    /* public function remessaBoletos()
    {
        //join M:M matricula X documentos
        return $this->belongsToMany(RemessaBoleto::class, 'tb_remessas_boletos', 'fk_id_remessa', 'fk_id_boleto');
    } */
}
