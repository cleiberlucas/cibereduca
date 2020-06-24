<?php

namespace App\Models\Pedagogico;

use Illuminate\Database\Eloquent\Model;

class TipoFrequencia extends Model
{
    protected $table = "tb_tipos_frequencia";
    protected $primaryKey = 'id_tipo_frequencia';
    
    public $timestamps = false;
        
    protected $fillable = ['tipo_frequencia', 'sigla_frequencia', 'reprova', 'padrao', 'situacao'];
   
    public function search($filtro = null)
    {
        $resultado = $this->where('tipo_frequencia', 'like', "%{$filtro}%")                                                         
                            ->paginate();
        
        return $resultado;
    }

    public function getTiposFrequencia(){
        return $this->select('*')->orderBy('tipo_frequencia')->get();
    }
}
