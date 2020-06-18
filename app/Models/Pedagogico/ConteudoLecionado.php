<?php

namespace App\Models\Pedagogico;

use App\Models\Secretaria\Disciplina;
use App\Models\Secretaria\Turma;
use Illuminate\Database\Eloquent\Model;

class ConteudoLecionado extends Model
{
    protected $table = "tb_conteudos_lecionados";
    protected $primaryKey = 'id_conteudo_lecionado';
    
    public $timestamps = false;
        
    protected $fillable = ['fk_id_turma_periodo_letivo', 'fk_id_disciplina', 'data_aula', 'conteudo_lecionado', 'fk_id_user', 'data_cadastro'];
   
    public function search($filtro = null)
    {
        $resultado = $this                           
                            ->where('conteudo_lecionado', 'like', "%{$filtro}%")
                            ->paginate();
        
        return $resultado;
    }

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
   
}
