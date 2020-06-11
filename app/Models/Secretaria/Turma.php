<?php

namespace App\Models\Secretaria;

use App\Models\AnoLetivo;
use App\Models\TipoTurma;
use App\Models\Turno;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    protected $table = "tb_turmas";
    protected $primaryKey = 'id_turma';
    
    public $timestamps = false;
        
    protected $fillable = ['nome_turma',  'fk_id_tipo_turma', 'fk_id_turno', 'localizacao', 'limite_alunos', 'fk_id_user', 'situacao_turma'];
   
    public function search($filtro = null)
    {
        $resultado = $this
                            ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
                            ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')                                       
                            ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())                                    
                            ->where('nome_turma', 'like', "%{$filtro}%")
                            ->paginate();
        
        return $resultado;
    }

    public function quantLimiteAlunos($idTurma)
    {
        $quant = $this->select('limite_alunos')
                        ->where('id_turma', '=', $idTurma)->first();
        return $quant->limite_alunos;
    }

    public function tipoTurma()
    {       
        return $this->belongsTo(TipoTurma::class, 'fk_id_tipo_turma', 'id_tipo_turma')->with('anoLetivo', 'subNivelEnsino');
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class, 'fk_id_turno', 'id_turno');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'fk_id_user', 'id');
    }
   
}
