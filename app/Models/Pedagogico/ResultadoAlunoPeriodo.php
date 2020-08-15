<?php

namespace App\Models\Pedagogico;

use Illuminate\Database\Eloquent\Model;

class ResultadoAlunoPeriodo extends Model
{
    protected $table = "tb_resultados_alunos_periodos";
    protected $primaryKey = 'id_resultado_aluno_periodo';

    public $timestamps = false;

    protected $fillable = ['fk_id_matricula', 'fk_id_turma_periodo_letivo', 'fk_id_disciplina', 'total_faltas', 'nota_media'];

    /**
     * Consulta lançamento de resultado de FALTA OU NOTA para um aluno X periodo X disciplina
     * @param $id_matricula, $id_turma_periodo_letivo, $id_disciplina
     * @return int - quantidade = 1(existe lançamento) ou 0(não existe lançamento)
     */
    public function getResultadoAlunoPeriodoDisciplina($id_matricula, $id_turma_periodo_letivo, $id_disciplina)
    {
        return $this->where('fk_id_matricula', $id_matricula)
            ->where('fk_id_turma_periodo_letivo', $id_turma_periodo_letivo)
            ->where('fk_id_disciplina', $id_disciplina)
            ->count();
    }

    /**
     * Consulta lançamento de resultado de FALTA OU NOTA para uma turma
     * @param $id_turma
     * @return array
     */
    public function getResultadosTurma($id_turma)
    {
        return $this
            ->join('tb_turmas_periodos_letivos', 'fk_id_turma_periodo_letivo', 'id_turma_periodo_letivo')
            ->where('fk_id_turma', $id_turma)            
            ->get();
    }

    /**
     * Consulta lançamento de resultado de FALTA E NOTA para uma turma X periodo
     * @param $id_turma, $id_disciplina 
     * @return array
     */
    public function getResultadosTurmaPeriodo($id_turma, $id_periodo)
    {
        return $this
            ->join('tb_turmas_periodos_letivos', 'fk_id_turma_periodo_letivo', 'id_turma_periodo_letivo')
            ->where('fk_id_turma', $id_turma)            
            ->where('fk_id_periodo_letivo', $id_periodo)            
            ->get();
    }

    /**
     * Consulta lançamento de resultado de FALTA E NOTA para uma turma X periodo X uma disciplina
     * @param int id_turma
     * @param int id_periodo
     * @param int id_disciplina 
     * @return array
     */
    public function getResultadosTurmaPeriodoDisciplina($id_turma, $id_periodo, $id_disciplina)
    {
        return $this
            ->join('tb_turmas_periodos_letivos', 'fk_id_turma_periodo_letivo', 'id_turma_periodo_letivo')
            ->where('fk_id_turma', $id_turma)            
            ->where('fk_id_periodo_letivo', $id_periodo)
            ->where('fk_id_disciplina', $id_disciplina)
            ->get();
    }

     /**
     * Consulta lançamento de resultado de FALTA OU NOTA para um aluno em todos os periodos
     * @param $id_turma
     * @return array
     */
    public function getResultadosMatricula($id_matricula)
    {
        return $this
            ->join('tb_turmas_periodos_letivos', 'fk_id_turma_periodo_letivo', 'id_turma_periodo_letivo')
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

    public function turmaPeriodoLetivo()
    {
        return $this->belongsTo(TurmaPeriodoLetivo::class, 'fk_id_turma_periodo_letivo', 'id_turma_periodo_letivo');
    }
}
