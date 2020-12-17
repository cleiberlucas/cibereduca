<?php

namespace App\Models\Pedagogico;

use Illuminate\Database\Eloquent\Model;

class RecuperacaoFinal extends Model
{
    protected $table = "tb_recuperacoes_finais"; 
    protected $primaryKey = 'id_rerecuperacao_final';
    
    public $timestamps = false;
        
    protected $fillable = ['fk_id_matricula', 'fk_id_disciplina', 'nota', 'data_aplicacao', 'fk_id_user'];
   /* 
    public function getTiposResultadoFinal(){
        return TipoResultadoFinal::select('*')
            ->orderBy('tipo_resultado_final')
            ->get();
    } */

    public function getTodosRecuperacoesFinais()
    {
        return $this
            ->select('nome', 'disciplina', 'nome_turma', 'ano', 'nota' )
            ->join('tb_matriculas', 'fk_id_matricula', 'id_matricula')
            ->join('tb_turmas', 'fk_id_turma', 'id_turma')
            ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
            ->join('tb_anos_letivos', 'fk_id_ano_letivo', 'id_ano_letivo')
            ->join('tb_pessoas', 'fk_id_aluno', 'id_pessoa')
            ->join('tb_disciplinas', 'fk_id_disciplina', 'id_disciplina')
            ->orderBy('ano', 'desc')
            ->orderBy('nome', 'asc')
            ->paginate();
        
    }
}
