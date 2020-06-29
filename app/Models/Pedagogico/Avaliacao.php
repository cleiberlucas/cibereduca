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
   
    public function search($filtro = null)
    {
        $resultado = $this->join('tb_turnos', 'fk_id_turno', 'id_turno')
                            ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
                            ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')                                       
                            ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())                                    
                            ->where('nome_turma', 'like', "%{$filtro}%")
                            ->orderBy('descricao_turno')
                            ->orderBy('nome_turma')
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
