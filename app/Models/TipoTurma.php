<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoTurma extends Model
{
    protected $table = "tb_tipos_turmas";
    protected $primaryKey = 'id_tipo_turma';
        
    public $timestamps = false;
        
    protected $fillable = ['tipo_turma',  'fk_id_ano_letivo', 'fk_id_sub_nivel_ensino', 'valor_padrao_mensalidade', 'fk_id_user'];
   
    public function search($filtro = null)
    {
        $resultado = $this->where('tipo_turma', 'like', "%{$filtro}%") 
                            ->paginate();
        
        return $resultado;
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
