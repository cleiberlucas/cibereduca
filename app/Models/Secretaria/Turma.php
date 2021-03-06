<?php

namespace App\Models\Secretaria;

use App\Models\Pedagogico\TurmaPeriodoLetivo;
use App\Models\PeriodoLetivo;
use App\Models\TipoTurma;
use App\Models\Turno;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    protected $table = "tb_turmas";
    protected $primaryKey = 'id_turma';
    
    public $timestamps = false;
        
    protected $fillable = ['nome_turma',  'fk_id_tipo_turma', 'fk_id_turno', 'localizacao', 'limite_alunos', 'fk_id_user', 'situacao_turma'];
   
    public function search($filtro = null)
    {
        $resultado = $this->join('tb_turnos', 'fk_id_turno', 'id_turno')
                            ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
                            ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
                            ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')                                       
                            ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())                                    
                            ->where('nome_turma', 'like', "%{$filtro}%")
                            ->orWhere('ano', "{$filtro}")
                            ->orderBy('tb_anos_letivos.ano', 'desc')
                            ->orderBy('tb_sub_niveis_ensino.sub_nivel_ensino', 'asc')
                            ->orderBy('nome_turma', 'asc')
                            ->orderBy('tb_turnos.descricao_turno', 'asc')
                            ->paginate(25);
        
        return $resultado;
    }

    public function searchTurmaNotas($filtro = null)
    {
        $resultado = $this->select ('*')
                        ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma' )
                        ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
                        ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
                        ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno')                                                            
                        ->where('tb_anos_letivos.fk_id_unidade_ensino', '=', User::getUnidadeEnsinoSelecionada()) 
                        ->where('nome_turma', 'like', "%{$filtro}%") 
                        ->orderBy('tb_anos_letivos.ano', 'desc')
                        ->orderBy('tb_turnos.descricao_turno', 'asc')
                        ->orderBy('tb_sub_niveis_ensino.sub_nivel_ensino', 'asc')
                        ->orderBy('nome_turma', 'asc')
                        ->paginate(25);

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

    public function periodosLetivos() 
    {
        //join M:M turma X periodo letivo
        return $this->belongsToMany(TurmaPeriodoLetivo::class, 'tb_turmas_periodos_letivos', 'fk_id_turma', 'fk_id_periodo_letivo');
    }

    /**
     * Ler bimestres ainda não vinculados a uma turma
     */
    public function periodosLetivosLivres($filtro = null) 
    {
        $periodosLetivos = PeriodoLetivo::whereNotIn('id_periodo_letivo', function($query){
            $query->select('tb_turmas_periodos_letivos.fk_id_periodo_letivo');
            $query->from('tb_turmas_periodos_letivos');
            $query->join('tb_turmas', 'tb_turmas.id_turma', 'tb_turmas_periodos_letivos.fk_id_turma');
            $query->whereRaw("tb_turmas_periodos_letivos.fk_id_turma = {$this->id_turma}");                    
            })
            ->where(function ($queryFiltro) use ($filtro){
                if ($filtro)
                    $queryFiltro->where('tb_periodos_letivos.periodo_letivo', 'LIKE', "%{$filtro}%");
            })
            ->join("tb_anos_letivos", 'tb_periodos_letivos.fk_id_ano_letivo', 'id_ano_letivo')
            ->join('tb_tipos_turmas', 'tb_tipos_turmas.fk_id_ano_letivo', 'id_ano_letivo')
            ->join('tb_turmas', 'tb_turmas.fk_id_tipo_turma', 'tb_tipos_turmas.id_tipo_turma')
            ->where('tb_turmas.id_turma', '=', $this->id_turma )
            /* ->join('tb_anos_letivos') */
            ->orderBy('periodo_letivo')
            ->get();
        
        return $periodosLetivos;
    }   

     /**
     * Retorna o total da CARGA HORÁRIA DE UMA TURMA
     * Soma as cargas horárias de todas as disciplinas
     */
    public function getCargaHorariaTurma($id_turma)
    {
        return $this::                        
            join('tb_grades_curriculares', 'tb_grades_curriculares.fk_id_tipo_turma', 'tb_turmas.fk_id_tipo_turma')            
            ->where('id_turma', $id_turma)
            ->sum('carga_horaria_anual')            
            ;
    }
   
}
