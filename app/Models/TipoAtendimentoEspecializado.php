<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoAtendimentoEspecializado extends Model
{
    protected $table = "tb_atendimentos_especializados";
    protected $primaryKey = 'id_atendimento_especializado';
    
    public $timestamps = false;
        
    protected $fillable = ['atendimento_especializado'];
   
    public function search($filtro = null)
    {
        $resultado = $this->where('atendimento_especializado', 'like', "%{$filtro}%")                                                         
                            ->paginate();
        
        return $resultado;
    }

    public function getTiposAtendimentoEspecializado(){
        return TipoAtendimentoEspecializado::select('*')->orderBy('atendimento_especializado')->get();
    }
}
