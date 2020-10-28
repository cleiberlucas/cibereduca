<?php

namespace App\Models\Captacao;

use Illuminate\Database\Eloquent\Model;

class MotivoContato extends Model
{
    protected $table = "tb_motivos_contato";
    protected $primaryKey = 'id_motivo_contato';
    
    public $timestamps = false;
        
    protected $fillable = ['motivo_contato'];
   
    public function search($filtro = null)
    {
        $resultado = $this
            ->where('motivo_contato', 'like', "%{$filtro}%")                             
            ->paginate();
        
        return $resultado;
    }
}
