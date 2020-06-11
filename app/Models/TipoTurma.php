<?php

namespace App\Models;

use App\Models\Secretaria\Disciplina;
use App\User;
use Illuminate\Database\Eloquent\Model;

class TipoTurma extends Model
{
    protected $table = "tb_tipos_turmas";
    protected $primaryKey = 'id_tipo_turma';
        
    public $timestamps = false;
        
    protected $fillable = ['tipo_turma',  'fk_id_ano_letivo', 'fk_id_sub_nivel_ensino', 'valor_curso', 'fk_id_user'];
   
    public function search($filtro = null)
    {
        $resultado = $this->join('tb_anos_letivos', 'fk_id_ano_letivo', 'id_ano_letivo')
                            ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
                            ->where('tipo_turma', 'like', "%{$filtro}%") 
                            ->paginate();
        
        return $resultado;
    }

    /**
     * Ler todas permissões
     */
    public function disciplinas()
    {
        //join M:M perfis X permissoes
        return $this->belongsToMany(Disciplina::class, 'tb_grades_curriculares', 'fk_id_tipo_turma', 'fk_id_disciplina');
    }

    /**
     * Ler disciplinas livres para um tipo de turma
     */
    public function disciplinasLivres($filtro = null) 
    {
        $disciplinas = Disciplina::whereNotIn('id_disciplina', function($query){
            $query->select('tb_grades_curriculares.fk_id_disciplina');
            $query->from('tb_grades_curriculares');            
            $query->whereRaw("tb_grades_curriculares.fk_id_tipo_turma = {$this->id_tipo_turma}");        
            })
            ->where(function ($queryFiltro) use ($filtro){
                if ($filtro)
                    $queryFiltro->where('tb_disciplinas.disciplina', 'LIKE', "%{$filtro}%");
            })
            ->orderBy('disciplina')
            ->where('situacao_disciplina', 1)
            ->paginate();
        //dd($disciplinas);
        return $disciplinas;
    }

    public function anoLetivo()
    {      
        return $this->belongsTo(AnoLetivo::class, 'fk_id_ano_letivo', 'id_ano_letivo');
    }

    public function subNivelEnsino()
    {       
        return $this->belongsTo(SubNivelEnsino::class, 'fk_id_sub_nivel_ensino', 'id_sub_nivel_ensino');
    }

    public function usuario()
    {       
        return $this->belongsTo(User::class, 'fk_id_user', 'id');
    }
}
