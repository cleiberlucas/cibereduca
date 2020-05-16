<?php

namespace App\Models\Secretaria;

use Illuminate\Database\Eloquent\Model;

class TipoPessoa extends Model
{
    protected $table = "tb_tipos_pessoa";
    protected $primaryKey = 'id_tipo_pessoa';
    
    public $timestamps = false;
}
