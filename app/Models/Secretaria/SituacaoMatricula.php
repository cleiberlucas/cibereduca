<?php

namespace App\Models\Secretaria;

use Illuminate\Database\Eloquent\Model;

class SituacaoMatricula extends Model
{
    protected $table = "tb_situacoes_matricula";
    protected $primaryKey = 'id_situacao_matricula';
    
    public $timestamps = false;
}
