<?php

namespace App\Models\Pedagogico;

use App\Models\Secretaria\Disciplina;
use Illuminate\Database\Eloquent\Model;

class Frequencia extends Model
{
    protected $table = "tb_frequencias";
    protected $primaryKey = 'id_frequencia';
    
    public $timestamps = false;
        
    protected $fillable = ['fk_id_matricula', 'fk_id_disciplina', 'data_aula', 'fk_id_tipo_frequencia', 'fk_id_user', 'data_cadastro'];
   
   /*  public function search($filtro = null)
    {
        $resultado = $this                           
                            ->where('conteudo_lecionado', 'like', "%{$filtro}%")
                            ->paginate();
        
        return $resultado;
    } */

    public function turmaPeriodoLetivo()
    {       
        return $this->belongsTo(TurmaPeriodoLetivo::class, 'fk_id_turma_periodo_letivo', 'id_turma_periodo_letivo');
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class, 'fk_id_disciplina', 'id_disciplina');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'fk_id_user', 'id');
    }

    /**
     * Retorna todos os conteúdos lecionados de uma turma, de toda a grade curricular (todas as disciplinas)
     */
    public function getConteudosLecionados($id_turma)
    {
        $conteudosLecionados = $this->join('tb_turmas_periodos_letivos', 'fk_id_turma_periodo_letivo', 'id_turma_periodo_letivo')
                                    ->join('tb_disciplinas', 'fk_id_disciplina', 'id_disciplina')
                                    ->where('fk_id_turma', '=', $id_turma)
                                    ->orderBy('disciplina')
                                    ->orderBy('data_aula', 'asc')
                                    ->get();
        return $conteudosLecionados;
    }
   
}
