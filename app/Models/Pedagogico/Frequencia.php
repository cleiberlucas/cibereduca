<?php

namespace App\Models\Pedagogico;

use App\Models\Secretaria\Disciplina;
use App\Models\Secretaria\Matricula;
use Illuminate\Database\Eloquent\Model;

class Frequencia extends Model
{
    protected $table = "tb_frequencias";
    protected $primaryKey = 'id_frequencia';
    
    public $timestamps = false;
        
    protected $fillable = ['fk_id_turma_periodo_letivo', 'fk_id_matricula', 'fk_id_disciplina', 'data_aula', 'fk_id_tipo_frequencia', 'fk_id_user', 'data_cadastro'];
   
    /**
     * Retorna todas as frequências de uma turma / de todas as disciplinas
     */
    public function getTurmaFrequencias($id_turma)
    {
        $this->select('tb_matriculas.id_matricula',
                'tb_pessoas.nome')
                ->Join('tb_matriculas', 'fk_id_matricula', 'id_matricula')
                ->join('tb_pessoas', 'fk_id_aluno', 'id_pessoa')
                ->where('tb_matriculas.fk_id_turma', '=', '$id_turma')
                ->orderBy('tb_pessoas.nome');
    }

    /**
     * 
     * Retorna as frequências de uma TURMA X PERIODO LETIVO X DISCIPLINA
     */
    public function getTurmaFrequenciaDisciplina($id_turma, $id_periodo_letivo, $id_disciplina)
    {
        $this->select('tb_frequencias.*',
                        'tb_turmas.nome_turma',
                        'sub_nivel_ensino',
                        'descricao_turno',
                        'periodo_letivo')
             ->join('tb_turmas_periodos_letivos', 'fk_id_turma_periodo_letivo', 'id_turma_periodo_letivo')
             ->join('tb_turmas', 'fk_id_turma', 'id_turma')
             ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
             ->join('tb_sub_niveis_ensino', 'fk_id_sub_nivel_ensino', 'id_sub_nivel_ensino')
             ->join('tb_turnos', 'fk_id_turno', 'id_turno')
             ->join('tb_periodos_letivos', 'fk_id_periodo_letivo', 'id_periodo_letivo')
             ->join('tb_tipos_frequencia', 'fk_id_tipo_frequencia', 'id_tipo_frequencia')
             ->join('tb_matriculas', 'fk_id_matricula', 'id_matricula')
             ->join('tb_pessoas', 'fk_id_aluno', 'id_pessoa')
             ->join('tb_disciplinas', 'fk_id_disciplina', 'id_disciplina')
             ->where('tb_turmas_periodos_letivos.fk_id_turma', '=', $id_turma)
             ->where('tb_turmas_periodos_letivos.id_periodo_letivo', '=', $id_periodo_letivo)
             ->where('tb_frequencias.fk_id_disciplina', '=', $id_disciplina)
             ->orderBy('tb_disciplinas.disciplina')
             ->orderBy('tb_pessoas.nome')
             ->orderBy('tb_frequencias.data_aula')
             ->get();
             
    }

    public function matricula()
    {
         $this->belongsTo(Matricula::class, 'fk_id_matricula', 'id_matricula');
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class, 'fk_id_disciplina', 'id_disciplina');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'fk_id_user', 'id');
    }
   
}
