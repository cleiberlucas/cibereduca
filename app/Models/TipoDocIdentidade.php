<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDocIdentidade extends Model
{
    protected $table = "tb_tipos_doc_identidade";
    protected $primaryKey = 'id_tipo_doc_identidade';
    
    public $timestamps = false;
        
    protected $fillable = ['tipo_doc_identidade'];
   
    public function search($filtro = null)
    {
        $resultado = $this->where('tipo_doc_identidade', 'like', "%{$filtro}%")                                                         
                            ->paginate();
        
        return $resultado;
    }
}
