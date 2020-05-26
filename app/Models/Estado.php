<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table = "tb_estados";
    protected $primaryKey = 'id_estado';
    
    public $timestamps = false;
        
    protected $fillable = ['estado', 'sigla'];
   
    public function search($filtro = null)
    {
        $resultado = $this->where('estado', 'like', "%{$filtro}%")                             
                            ->where('sigla', 'like', "%{$filtro}%")                             
                            ->paginate();
        
        return $resultado;
    }
}
