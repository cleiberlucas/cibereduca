<?php

namespace App\Models\Secretaria;

use Illuminate\Database\Eloquent\Model;

class TipoDescontoCurso extends Model
{
    protected $table = "tb_tipos_desconto_curso";
    protected $primaryKey = 'id_tipo_desconto_curso';
    
    public $timestamps = false;
        
    protected $fillable = ['tipo_desconto_curso'];
   
    public function search($filtro = null)
    {
        $resultado = $this->where('tipo_desconto_curso', 'like', "%{$filtro}%") 
                            ->paginate();
        
        return $resultado;
    }

    public function getTiposDescontoCurso(){
        return TipoDescontoCurso::select('*')->orderBy('tipo_desconto_curso')->get();
    }
}
