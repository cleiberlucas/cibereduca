<?php

namespace App\Models\Secretaria;

use Illuminate\Database\Eloquent\Model;

class TipoPessoa extends Model
{
    protected $table = "tb_situacoes_matricula";
    protected $primaryKey = 'id_situacao_matricula';
    
    public $timestamps = false;
}
