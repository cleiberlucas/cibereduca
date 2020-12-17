<?php

namespace App\Models\Pedagogico;

use App\User;
use Illuminate\Database\Eloquent\Model;

class RecuperacaoFinal extends Model
{
    protected $table = "tb_recuperacoes_finais"; 
    protected $primaryKey = 'id_rerecuperacao_final';
    
    public $timestamps = false;
        
    protected $fillable = ['fk_id_matricula', 'fk_id_disciplina', 'nota', 'data_aplicacao', 'fk_id_user'];
   
    public function search($filtro = null)
    {
        $resultado = $this
            ->select('id_recuperacao_final', 'nome', 'disciplina', 'nome_turma', 'ano', 'nota', 'data_aplicacao' )
            ->join('tb_matriculas', 'fk_id_matricula', 'id_matricula')
            ->join('tb_turmas', 'fk_id_turma', 'id_turma')
            ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
            ->join('tb_anos_letivos', 'fk_id_ano_letivo', 'id_ano_letivo')
            ->join('tb_pessoas', 'fk_id_aluno', 'id_pessoa')
            ->join('tb_disciplinas', 'fk_id_disciplina', 'id_disciplina')
            ->where('tb_anos_letivos.fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
            ->where('nome', 'like', "%{$filtro}%")
            ->orderBy('ano', 'desc')
            ->orderBy('nome', 'asc')            
            ->paginate();
        
        return $resultado;
    }

    /**
     * Todas as recuperações finais lançadas
     */
    public function getTodosRecuperacoesFinais()
    {
        return $this
            ->select('id_recuperacao_final', 'nome', 'disciplina', 'nome_turma', 'ano', 'nota', 'data_aplicacao' )
            ->join('tb_matriculas', 'fk_id_matricula', 'id_matricula')
            ->join('tb_turmas', 'fk_id_turma', 'id_turma')
            ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
            ->join('tb_anos_letivos', 'fk_id_ano_letivo', 'id_ano_letivo')
            ->join('tb_pessoas', 'fk_id_aluno', 'id_pessoa')
            ->join('tb_disciplinas', 'fk_id_disciplina', 'id_disciplina')
            ->where('tb_anos_letivos.fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
            ->orderBy('ano', 'desc')
            ->orderBy('nome', 'asc')            
            ->paginate();
        
    }

    /**
     * Uma recuperação final
     */
    public function getRecuperacaoFinal($id_recuperacao_final)
    {
        return $this
            ->select('id_recuperacao_final', 'nome', 'disciplina', 'nome_turma', 'ano', 'nota', 'data_aplicacao' )
            ->join('tb_matriculas', 'fk_id_matricula', 'id_matricula')
            ->join('tb_turmas', 'fk_id_turma', 'id_turma')
            ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
            ->join('tb_anos_letivos', 'fk_id_ano_letivo', 'id_ano_letivo')
            ->join('tb_pessoas', 'fk_id_aluno', 'id_pessoa')
            ->join('tb_disciplinas', 'fk_id_disciplina', 'id_disciplina')
            ->where('id_recuperacao_final', $id_recuperacao_final)
            ->first();        
    }
}
