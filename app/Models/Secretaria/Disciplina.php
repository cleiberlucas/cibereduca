<?php

namespace App\Models\Secretaria;

use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    protected $table = "tb_disciplinas";
    protected $primaryKey = 'id_disciplina';
    
    public $timestamps = false;
    
    //protected $attributes = ['situacao_disciplina' => '0'];

    protected $fillable = ['disciplina', 'sigla_disciplina', 'situacao_disciplina'];
   
    public function search($filtro = null)
    {
        $resultado = $this->where('disciplina', 'LIKE', "%{$filtro}%")
                            ->orWhere('sigla_disciplina', 'LIKE', "%{$filtro}%")
                            ->paginate();
        
        return $resultado;
    }

    /**
     * Retorna dados de uma disciplina
     * @param int id_disciplina
     * @return array dados
     */
    public function getDisciplina(int $id_disciplina)
    {
        $disciplina = $this->where('id_disciplina', $id_disciplina)->first();

        return $disciplina;
    }
}
