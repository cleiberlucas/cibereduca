<?php

namespace App\Models\Pedagogico;

use App\Models\PeriodoLetivo;
use App\Models\Secretaria\Disciplina;
use App\Models\Secretaria\Matricula;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    protected $table = "tb_notas_avaliacoes";
    protected $primaryKey = 'id_nota_avaliacao';
    
    public $timestamps = false;
        
    protected $fillable = ['fk_id_matricula', 'fk_id_avaliacao', 'nota', 'data_avaliacao', 'fk_id_user'];
   
    /**
     * Retorna todas os períodos letivos que tem avaliacao cadastrada
     */
    public function getPeriodosTurma($id_tipo_turma)
    {
        return $this->select('id_periodo_letivo', 'periodo_letivo')
                    ->rightJoin('tb_avaliacoes', 'fk_id_avaliacao', 'id_avaliacao')
                    ->join('tb_periodos_letivos', 'fk_id_periodo_letivo', 'id_periodo_letivo')                  
                    ->where('fk_id_tipo_turma', $id_tipo_turma)
                    ->groupBy('id_periodo_letivo')
                    ->groupBy('periodo_letivo')
                    ->get();
    } 

    /**
     * Retorna todas as disciplinas que tem avaliacao cadastrada para a turma de um aluno
     */
    /* public function getDisciplinasTurma($id_tipo_turma)
    {
        return $this->select('sigla_disciplina')
                    ->rightJoin('tb_avaliacoes', 'fk_id_avaliacao', 'id_avaliacao')
                    ->join('tb_disciplinas', 'fk_id_disciplina', 'id_disciplina')                    
                    ->where('fk_id_tipo_turma', $id_tipo_turma)
                    ->groupBy('sigla_disciplina')
                    ->get();
    }
 */
    /**
     * Retorna todas as avaliações cadastradas para a turma de um aluno
     * Agrupado por período letivo
     * NÃO SÃO AS NOTAS
     */
    public function getAvaliacoesTurma($id_tipo_turma)
    {
        return $this->select('periodo_letivo', 'tipo_avaliacao')
                    ->rightJoin('tb_avaliacoes', 'fk_id_avaliacao', 'id_avaliacao')
                    ->join('tb_tipos_avaliacao', 'fk_id_tipo_avaliacao', 'id_tipo_avaliacao')                    
                    ->join('tb_periodos_letivos', 'fk_id_periodo_letivo', 'id_periodo_letivo')
                    ->where('fk_id_tipo_turma', $id_tipo_turma) 
                    ->groupBy('periodo_letivo')                   
                    ->groupBy('tipo_avaliacao')
                    ->get();
    }

    public function getNotasAluno($id_matricula)
    {
        return $this->select('*')
                            ->join('tb_avaliacoes', 'fk_id_avaliacao', 'id_avaliacao')
                            ->join('tb_tipos_avaliacao', 'fk_id_tipo_avaliacao', 'id_tipo_avaliacao')
                            ->where('fk_id_matricula', $id_matricula)
                            ->get();

    }

    public function matricula()
    {
        return $this->belongsTo(Matricula::class, 'fk_id_matricula', 'id_matricula');
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class, 'fk_id_disciplina', 'id_disciplina');
    }

    public function avaliacao()
    {
        return $this->belongsTo(Avaliacao::class, 'fk_id_avaliacao', 'id_avaliacao');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'fk_id_user', 'id');
    }
   
}
