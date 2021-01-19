<?php

namespace App\Models\Financeiro;

use Illuminate\Database\Eloquent\Model;

class Remessa extends Model
{
    protected $table = "tb_remessas";
    protected $primaryKey = 'id_remessa';
    
    public $timestamps = false;
        
    protected $fillable = [
        'data_remessa',
        'situacao_remessa',         
        
    ];
   
    /**
     * Relacinar remessa X boletos
     */
    public function remessaBoletos()
    {
        //join M:M matricula X documentos
        return $this->belongsToMany(RemessaBoleto::class, 'tb_remessas_boletos', 'fk_id_remessa', 'fk_id_boleto');
    }
}
