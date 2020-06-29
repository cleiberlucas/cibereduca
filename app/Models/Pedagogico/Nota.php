<?php

namespace App\Models\Pedagogico;

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

    /**
     * Retorna as disciplinas que o aluno tem registro de frequencia em um período
     */
    /* public function getFrequenciasAlunoDisciplinasPeriodo($id_turma_periodo_letivo, $id_matricula)
    {
        $frequenciasAlunoDisciplinasPeriodo = $this->select('id_disciplina', 'sigla_disciplina')
                                                    ->join('tb_disciplinas', 'fk_id_disciplina', 'id_disciplina')
                                                    ->where('fk_id_turma_periodo_letivo', '=', $id_turma_periodo_letivo)
                                                    ->where('fk_id_matricula', '=', $id_matricula)
                                                    ->orderBy('disciplina')
                                                    ->groupBy('id_disciplina')
                                                    ->groupBy('disciplina')
                                                    ->get();
        return $frequenciasAlunoDisciplinasPeriodo;
    } */

    /**
     * Retorna as datas que o aluno tem registro de frequencia em todas as disciplinas em um período
     */
    /* public function getFrequenciasAlunoDatasPeriodo($id_turma_periodo_letivo, $id_matricula)
    {
        $frequenciasAlunoDatasPeriodo = $this->select('data_aula')                                                    
                                                    ->where('fk_id_turma_periodo_letivo', '=', $id_turma_periodo_letivo)
                                                    ->where('fk_id_matricula', '=', $id_matricula)
                                                    ->groupBy('data_aula')
                                                    ->get();
        return $frequenciasAlunoDatasPeriodo;
    } */

    /**
     * Retorna os meses que o aluno tem registro de frequencia em todas as disciplinas em um período
     */
   /*  public function getFrequenciasAlunoMesesPeriodo($id_turma_periodo_letivo, $id_matricula)
    {
        $frequenciasAlunoMesesPeriodo = DB::table('tb_frequencias')
                                            ->select(DB::raw('month(data_aula) as mes'))
                                            ->where('fk_id_turma_periodo_letivo', '=', $id_turma_periodo_letivo)
                                            ->where('fk_id_matricula', '=', $id_matricula)
                                            ->groupBy(DB::raw('month(data_aula)'))
                                            ->get();
        return $frequenciasAlunoMesesPeriodo;
    } */

    /**
     * 
     * Retorna as frequências de todas as disciplinas de um ALUNO X PERIODO LETIVO
     */
    /* public function getFrequenciasAlunoPeriodo($id_turma_periodo_letivo, $id_matricula)
    {
        $frequenciaAlunoPeriodo =  $this->select('tb_frequencias.*',
                                                'nome_turma',
                                                'sub_nivel_ensino',
                                                'descricao_turno',
                                                'periodo_letivo',
                                                'nome',
                                                'sigla_frequencia',
                                                )
                                        ->join('tb_turmas_periodos_letivos', 'fk_id_turma_periodo_letivo', 'id_turma_periodo_letivo')
                                        ->join('tb_turmas', 'fk_id_turma', 'id_turma')
                                        ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
                                        ->join('tb_sub_niveis_ensino', 'fk_id_sub_nivel_ensino', 'id_sub_nivel_ensino')
                                        ->join('tb_turnos', 'fk_id_turno', 'id_turno')
                                        ->join('tb_periodos_letivos', 'fk_id_periodo_letivo', 'id_periodo_letivo')
                                        ->join('tb_tipos_frequencia', 'fk_id_tipo_frequencia', 'id_tipo_frequencia')
                                        ->join('tb_matriculas', 'fk_id_matricula', 'id_matricula')
                                        ->join('tb_pessoas', 'fk_id_aluno', 'id_pessoa')                                        
                                        ->where('tb_frequencias.fk_id_matricula', '=', $id_matricula)
                                        ->where('tb_turmas_periodos_letivos.id_turma_periodo_letivo', '=', $id_turma_periodo_letivo)                                                     
                                        ->orderBy('tb_frequencias.data_aula')
                                        ->get();
        return $frequenciaAlunoPeriodo;
        
    } */
    

    public function matricula()
    {
        return $this->belongsTo(Matricula::class, 'fk_id_matricula', 'id_matricula');
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
