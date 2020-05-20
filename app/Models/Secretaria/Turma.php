<?php

namespace App\Models\Secretaria;

use App\Models\AnoLetivo;
use App\Models\TipoTurma;
use App\Models\Turno;
use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    protected $table = "tb_turmas";
    protected $primaryKey = 'id_turma';
    
    public $timestamps = false;
        
    protected $fillable = ['nome_turma',  'fk_id_tipo_turma', 'fk_id_turno', 'localizacao', 'limite_alunos'];
   
    public function search($filtro = null)
    {
        $resultado = $this->where('nome_turma', 'like', "%{$filtro}%") 
                            ->paginate();
        
        return $resultado;
    }

    public function tipoTurma()
    {       
        return $this->belongsTo(TipoTurma::class, 'fk_id_tipo_turma', 'id_tipo_turma');
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class, 'fk_id_turno', 'id_turno');
    }
}
