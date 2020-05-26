<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{
    protected $table = "tb_cidades";
    protected $primaryKey = 'id_cidade';
    
    public $timestamps = false;
        
    protected $fillable = ['fk_id_estado', 'cidade'];
   
    public function search($filtro = null)
    {
        $resultado = $this->where('endereco', 'like', "%{$filtro}%")                             
                            ->paginate();
        
        return $resultado;
    }

    public function cidade()
    {
        return $this->get();
    }

    public function estado()
    {       
        return $this->belongsTo(Estado::class, 'fk_id_estado', 'id_estado');
    }
}
