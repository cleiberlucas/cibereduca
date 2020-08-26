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

    /**
     * Retorna as disciplinas de uma turma
     */
    public function disciplinasTurma($id_turma)
    {
        return $this::select('*')
                ->join('tb_tipos_turmas', 'tb_grades_curriculares.fk_id_tipo_turma', 'id_tipo_turma')
                ->join('tb_turmas', 'tb_turmas.fk_id_tipo_turma', 'id_tipo_turma')
                ->join('tb_disciplinas', 'fk_id_disciplina', 'id_disciplina')
                ->where('tb_turmas.id_turma', '=', $id_turma)
                ->orderBy('disciplina')
                ->get();
    }

    /**
     * Retorna as disciplinas de um professor ATIVO de uma turma
     */
    public function disciplinasTurmaProfessor($id_turma, $idProfessor)
    {
        return $this::select('*')
                ->join('tb_tipos_turmas', 'tb_grades_curriculares.fk_id_tipo_turma', 'id_tipo_turma')
                ->join('tb_turmas', 'tb_turmas.fk_id_tipo_turma', 'id_tipo_turma')
                ->join('tb_disciplinas', 'fk_id_disciplina', 'id_disciplina')
                ->join('tb_turmas_disciplinas_professor', 'fk_id_grade_curricular', 'id_grade_curricular')
                ->where('tb_turmas.id_turma', '=', $id_turma)
                ->where('fk_id_professor', $idProfessor)
                ->where('situacao_disciplina_professor', 1)
                ->orderBy('disciplina')
                ->get();
    }

    /**
     * Retorna as disciplinas de um Tipo turma
     */
    public function disciplinasTipoTurma($id_tipo_turma)
    {
        return $this::select('*')
                ->join('tb_tipos_turmas', 'tb_grades_curriculares.fk_id_tipo_turma', 'id_tipo_turma')                
                ->join('tb_disciplinas', 'fk_id_disciplina', 'id_disciplina')
                ->where('id_tipo_turma', '=', $id_tipo_turma)
                ->orderBy('disciplina')
                ->get();
    }

}
