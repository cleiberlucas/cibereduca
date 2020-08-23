<?php

namespace App\Models\Secretaria;

use App\Models\GradeCurricular;
use App\User;
use Illuminate\Database\Eloquent\Model;

class TurmaProfessor extends Model
{
    protected $table = "tb_turmas_disciplinas_professor";
    protected $primaryKey = 'id_turma_disciplina_professor';
    
    public $timestamps = false;
        
    protected $fillable = ['fk_id_grade_curricular',  'fk_id_turma', 'fk_id_professor', 'data_entrada', 'data_saida', 'situacao_disciplina_professor'];
   
    /* public function search($filtro = null)
    {
        $resultado = $this->join('tb_turnos', 'fk_id_turno', 'id_turno')
                            ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
                            ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')                                       
                            ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())                                    
                            ->where('nome_turma', 'like', "%{$filtro}%")
                            ->orderBy('descricao_turno')
                            ->orderBy('nome_turma')
                            ->paginate();
        
        return $resultado;
    } */
   
    public function turma()
    {       
        return $this->belongsTo(Turma::class, 'fk_id_turma', 'id_turma');
    }

    public function gradeCurricular() 
    {
        return $this->belongsTo(GradeCurricular::class, 'fk_id_grade_curricular', 'id_grade_curricular');
    }

    public function professor()
    {
        return $this->belongsTo(User::class, 'fk_id_professor', 'id');
    }
   
}
