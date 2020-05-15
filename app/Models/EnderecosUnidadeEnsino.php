<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnderecosUnidadeEnsino extends Model
{
    private $table = 'tb_enderecos';

    public function UnidadeEnsino()
    {
        return $this->belongsTo(UnidadeEnsino::class);
    }
}
