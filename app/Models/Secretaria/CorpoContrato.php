<?php

namespace App\Models\Secretaria;

use App\Models\UnidadeEnsino;
use Illuminate\Database\Eloquent\Model;

class CorpoContrato extends Model
{
    protected $table = "tb_documentos";
    protected $primaryKey = 'id_documento';
    
    public $timestamps = false;
        
    protected $fillable = [''];
   
    /**
     * Ler todos documentos entregues
     */
    public function corpoContrato()
    {
        //join M:M matricula X documentos
        return $this->belongsTo(UnidadeEnsino::class, 'tb_unidades_ensino', 'fk_id_unidade_ensino', 'id_unidade_ensino');
    }

}
