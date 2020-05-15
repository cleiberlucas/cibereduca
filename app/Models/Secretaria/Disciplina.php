<?php

namespace App\Models\Secretaria;

use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    protected $table = "tb_disciplinas";
    protected $primaryKey = 'id_disciplina';
    
    public $timestamps = false;
    
    protected $fillable = ['disciplina', 'sigla_disciplina'];
   
    public function search($filtro = null)
    {
        $resultado = $this->where('disciplina', 'LIKE', "%{$filtro}%")
                            ->orWhere('sigla_disciplina', 'LIKE', "%{$filtro}%")
                            ->paginate();
        
        return $resultado;
    }
}
