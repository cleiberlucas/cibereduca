<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDocumentoEscola extends Model
{
    protected $table = "tb_tipos_documentos_escola";
    protected $primaryKey = 'id_tipo_documento';
    
    public $timestamps = false;
    
    //protected $attributes = ['situacao_disciplina' => '0'];

    protected $fillable = ['id_tipo_documento', 'tipo_documento', 'situacao'];
   
}
