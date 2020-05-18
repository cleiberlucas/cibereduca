<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    protected $table = "tb_turnos";
    protected $primaryKey = 'id_turno';
    
    public $timestamps = false;
        
    protected $fillable = ['descricao_turno'];
   
}
