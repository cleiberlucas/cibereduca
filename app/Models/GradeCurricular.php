<?php

namespace App\Models;

use App\Models\Secretaria\Disciplina;
use Illuminate\Database\Eloquent\Model;

class GradeCurricular extends Model
{
    protected $table = "tb_grades_curriculares";
    protected $primaryKey = 'id_grade_curricular';
    
    public $timestamps = false;
    
    protected $fillable = ['fk_id_tipo_turma', 'fk_id_disciplina', 'carga_horaria_anual'];
   
    /* public function search($filtro = null)
    {
        $resultado = $this->where('disciplina', 'LIKE', "%{$filtro}%")
                            ->orWhere('sigla_disciplina', 'LIKE', "%{$filtro}%")
                            ->paginate();
        
        return $resultado;
    } */

    public function disciplina()
    {       
        return $this->belongsTo(Disciplina::class, 'fk_id_disciplina', 'id_disciplina');
    }
}
