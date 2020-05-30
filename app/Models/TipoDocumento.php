<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    protected $table = "tb_tipos_documentos";
    protected $primaryKey = 'id_tipo_documento';
    
    public $timestamps = false;
        
    protected $fillable = ['tipo_documento', 'comentario', 'situacao', 'obrigatorio'];   

    public function search($filtro = null)
    {
        $resultado = $this->where('tipo_documento', 'LIKE', "%{$filtro}%")
                            ->orWhere('comentario', 'LIKE', "%{$filtro}%")
                            ->paginate();
        
        return $resultado;
    }
}
