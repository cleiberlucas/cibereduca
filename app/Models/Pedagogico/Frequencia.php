<?php

namespace App\Models\Pedagogico;

use App\Models\PeriodoLetivo;
use App\Models\Secretaria\Disciplina;
use App\Models\Secretaria\Matricula;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Frequencia extends Model
{
    protected $table = "tb_frequencias";
    protected $primaryKey = 'id_frequencia';
    
    public $timestamps = false;
        
    protected $fillable = ['fk_id_periodo_letivo', 'fk_id_matricula', 'fk_id_disciplina', 'data_aula', 'fk_id_tipo_frequencia', 'fk_id_user', 'data_cadastro'];
   
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
     * Retorna as disciplinas que o aluno tem registro de frequencia em um período
     */
    public function getFrequenciasAlunoDisciplinasPeriodo($id_periodo_letivo, $id_matricula)
    {
        $frequenciasAlunoDisciplinasPeriodo = $this->select('id_disciplina', 'sigla_disciplina')
                                                    ->join('tb_disciplinas', 'fk_id_disciplina', 'id_disciplina')
                                                    ->where('fk_id_periodo_letivo', '=', $id_periodo_letivo)
                                                    ->where('fk_id_matricula', '=', $id_matricula)
                                                    ->orderBy('disciplina')
                                                    ->groupBy('id_disciplina')
                                                    ->groupBy('disciplina')
                                                    ->get();
        return $frequenciasAlunoDisciplinasPeriodo;
    }

    /**
     * Retorna as datas que o aluno tem registro de frequencia em todas as disciplinas em um período
     */
    public function getFrequenciasAlunoDatasPeriodo($id_periodo_letivo, $id_matricula)
    {
        $frequenciasAlunoDatasPeriodo = $this->select('data_aula')                                                    
                                                    ->where('fk_id_periodo_letivo', '=', $id_periodo_letivo)
                                                    ->where('fk_id_matricula', '=', $id_matricula)                                                    
                                                    ->groupBy('data_aula')
                                                    ->get();
        return $frequenciasAlunoDatasPeriodo;
    }

    /**
     * Retorna os meses que o aluno tem registro de frequencia em todas as disciplinas em um período
     */
    public function getFrequenciasAlunoMesesPeriodo($id_periodo_letivo, $id_matricula)
    {
        $frequenciasAlunoMesesPeriodo = DB::table('tb_frequencias')
                                            ->select(DB::raw('month(data_aula) as mes'))
                                            ->where('fk_id_periodo_letivo', '=', $id_periodo_letivo)
                                            ->where('fk_id_matricula', '=', $id_matricula)
                                            ->groupBy(DB::raw('month(data_aula)'))
                                            ->get();
        return $frequenciasAlunoMesesPeriodo;
    }

    /**
     * 
     * Retorna as frequências de todas as disciplinas de um ALUNO X PERIODO LETIVO
     */
    public function getFrequenciasAlunoPeriodo($id_periodo_letivo, $id_matricula, $id_turma)
    {
        $frequenciaAlunoPeriodo =  $this->select('tb_frequencias.*',
                                                'nome_turma',
                                                'sub_nivel_ensino',
                                                'descricao_turno',
                                                'periodo_letivo',
                                                'nome',
                                                'sigla_frequencia',
                                                'tb_turmas_periodos_letivos.situacao'
                                                )
                                        ->join('tb_matriculas', 'fk_id_matricula', 'id_matricula')
                                        ->join('tb_turmas_periodos_letivos', 'tb_frequencias.fk_id_periodo_letivo', 'tb_turmas_periodos_letivos.fk_id_periodo_letivo')
                                        ->join('tb_periodos_letivos', 'tb_periodos_letivos.id_periodo_letivo', 'tb_frequencias.fk_id_periodo_letivo')
                                        ->join('tb_turmas', 'tb_matriculas.fk_id_turma', 'id_turma')
                                        ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
                                        ->join('tb_sub_niveis_ensino', 'fk_id_sub_nivel_ensino', 'id_sub_nivel_ensino')
                                        ->join('tb_turnos', 'fk_id_turno', 'id_turno')                                        
                                        ->join('tb_tipos_frequencia', 'fk_id_tipo_frequencia', 'id_tipo_frequencia')                                        
                                        ->join('tb_pessoas', 'fk_id_aluno', 'id_pessoa')                                        
                                        ->where('tb_frequencias.fk_id_matricula', '=', $id_matricula)
                                        ->where('tb_frequencias.fk_id_periodo_letivo', '=', $id_periodo_letivo)     
                                        ->where('tb_turmas_periodos_letivos.fk_id_turma', $id_turma)                                                
                                        ->orderBy('tb_frequencias.data_aula')
                                        ->get();
        return $frequenciaAlunoPeriodo;
        
    }

    /**
     * Retorna a quantidade de faltas de um aluno X período X disciplina
     */
    public function getFaltasAlunoPeriodoDisciplina($id_matricula, $id_periodo_letivo, $id_disciplina)
    {
        return $this->join('tb_tipos_frequencia', 'fk_id_tipo_frequencia', 'id_tipo_frequencia')
                    ->where('reprova', '1')
                    ->where('fk_id_matricula', $id_matricula)
                    ->where('fk_id_periodo_letivo', $id_periodo_letivo)
                    ->where('fk_id_disciplina', $id_disciplina)
                    ->count();
    }

    public function periodoLetivo()
    {
        return $this->belongsTo(PeriodoLetivo::class, 'fk_id_periodo_letivo', 'id_periodo_letivo');
    }

    public function matricula()
    {
        return $this->belongsTo(Matricula::class, 'fk_id_matricula', 'id_matricula');
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
