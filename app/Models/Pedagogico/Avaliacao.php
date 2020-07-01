<?php

namespace App\Models\Pedagogico;


use App\Models\PeriodoLetivo;
use App\Models\Secretaria\Disciplina;
use App\Models\TipoTurma;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
    protected $table = "tb_avaliacoes";
    protected $primaryKey = 'id_avaliacao';
    
    public $timestamps = false;
        
    protected $fillable = ['fk_id_tipo_turma', 'fk_id_periodo_letivo', 'fk_id_disciplina', 'fk_id_tipo_avaliacao', 'valor_avaliacao', 'conteudo'];
   
    /**
     * Filtra avaliações por DISCIPLINA
     */
    public function searchAvaliacaoDisciplina($filtro = null, $id_tipo_turma)
    {
        $resultado = $this->select('*')
                            ->join('tb_tipos_avaliacao', 'fk_id_tipo_avaliacao', 'id_tipo_avaliacao')
                            ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')           
                            ->join('tb_sub_niveis_ensino', 'fk_id_sub_nivel_ensino', 'id_sub_nivel_ensino')                 
                            ->join('tb_periodos_letivos', 'tb_avaliacoes.fk_id_periodo_letivo', 'id_periodo_letivo')
                            ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', 'tb_anos_letivos.id_ano_letivo')                                       
                            ->join('tb_disciplinas', 'fk_id_disciplina', 'id_disciplina')
                            ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())   
                            ->where('id_tipo_turma', $id_tipo_turma) 
                            ->where('disciplina', 'like', "%{$filtro}%")                                                                        
                            ->orderBy('periodo_letivo')
                            ->orderBy('disciplina')
                            ->orderBy('tipo_avaliacao')
                            ->paginate();
        
        return $resultado;
    }

    public function getAvaliacoesTipoTurma($id_tipo_turma)
    {
        return $this->where('fk_id_tipo_turma', $id_tipo_turma)->get();
    }

    public function tipoTurma()
    {       
        return $this->belongsTo(TipoTurma::class, 'fk_id_tipo_turma', 'id_tipo_turma');
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class, 'fk_id_disciplina', 'id_disciplina');
    }

    public function tipoAvaliacao()
    {
        return $this->belongsTo(TipoAvaliacao::class, 'fk_id_tipo_avaliacao', 'id_tipo_avaliacao');
    }

    public function periodoLetivo()
    {
        //join M:M turma X periodo letivo
        return $this->belongsTo(PeriodoLetivo::class, 'fk_id_periodo_letivo', 'id_periodo_letivo');
    }
   
}
