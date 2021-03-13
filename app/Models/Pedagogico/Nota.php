<?php

namespace App\Models\Pedagogico;

use App\Models\PeriodoLetivo;
use App\Models\Secretaria\Disciplina;
use App\Models\Secretaria\Matricula;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        return $this->select('periodo_letivo', 'id_periodo_letivo' )
                    ->rightJoin('tb_avaliacoes', 'fk_id_avaliacao', 'id_avaliacao')
                    ->join('tb_periodos_letivos', 'fk_id_periodo_letivo', 'id_periodo_letivo')                  
                    ->where('fk_id_tipo_turma', $id_tipo_turma)
                    ->groupBy('periodo_letivo')
                    ->groupBy('id_periodo_letivo')                    
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
        return $this
            ->select('*')
            ->join('tb_avaliacoes', 'fk_id_avaliacao', 'id_avaliacao')
            ->join('tb_tipos_avaliacao', 'fk_id_tipo_avaliacao', 'id_tipo_avaliacao')
            ->where('fk_id_matricula', $id_matricula)
            ->get();

    }
    
    public function getNotasPortalAluno($id_matricula){
        return $this
            ->join('tb_avaliacoes', 'fk_id_avaliacao', 'id_avaliacao')
            ->join('tb_tipos_avaliacao', 'fk_id_tipo_avaliacao', 'id_tipo_avaliacao')
            ->join('tb_periodos_letivos', 'tb_avaliacoes.fk_id_periodo_letivo', 'id_periodo_letivo')
            ->join('tb_disciplinas', 'fk_id_disciplina', 'id_disciplina')
            ->where('fk_id_matricula', $id_matricula)
            ->orderBy('periodo_letivo')
            ->orderBy('disciplina')
            ->paginate(20);
    }

    /**
     * Retorna a soma das avaliações de um aluno X período X disciplina
     */
    public function getNotasAlunoPeriodoDisciplina($id_matricula, $id_periodo_letivo, $id_disciplina) 
    {
        return $this
            ->join('tb_avaliacoes', 'fk_id_avaliacao', 'id_avaliacao')                                   
            ->where('fk_id_matricula', $id_matricula)
            ->where('fk_id_periodo_letivo', $id_periodo_letivo)
            ->where('fk_id_disciplina', $id_disciplina)
            ->sum('nota');
    }
/* 
    public function getPeriodoLetivo($id_nota_avaliacao, $id_matricula, $id_turma, $id_periodo_letivo)
    {
        return $this->select('tb_avaliacoes.id_periodo_letivo')
                    ->join('tb_matriculas', 'fk_id_matricula', 'id_matricula')
                    ->join('tb_avaliacoes', 'fk_id_avaliacao', 'id_avaliacao')                    
                    ->where("id_nota_avaliacao", $id_nota_avaliacao)
                    ->where("tb_matriculas.id_matricula", '=', $id_matricula)
                    ->where("tb_turmas_periodos_letivos.fk_id_turma", '=', $id_turma)
                    ->where("tb_turmas_periodos_letivos.fk_id_periodo_letivo", '=', $id_periodo_letivo)
                    ->first();
    } */

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
