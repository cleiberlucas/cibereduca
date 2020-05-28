<?php

namespace App\Models;

use App\Models\Cidade;
use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    protected $table = "tb_enderecos";
    protected $primaryKey = 'id_endereco';
    
    public $timestamps = false;
        
    protected $fillable = ['fk_id_pessoa',  'endereco', 'complemento', 'numero', 'bairro', 'fk_id_cidade', 'cep'];
   
    public function search($filtro = null)
    {
        $resultado = $this->where('endereco', 'like', "%{$filtro}%") 
                            ->where('complemento', 'like', "%{$filtro}%") 
                            ->where('bairro', 'like', "%{$filtro}%") 
                            ->paginate();
        
        return $resultado;
    }

    public function cidade()
    {       
        return $this->belongsTo(Cidade::class, 'fk_id_cidade', 'id_cidade')->with('estado');
    }
}
