<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnidadeEnsino extends Model
{
    protected $table = "tb_unidades_ensino";
    
    public $timestamps = false;
    
    protected $fillable = ['razao_social', 'nome_fantasia', 'cnpj', 'telefone', 'email', 'nome_assinatura', 'cargo_assinatura', 'url_site'];
   
    public function search($filtro = null)
    {
        $resultado = $this->where('nome_fantasia', 'LIKE', "%{$filtro}%")
                            ->orWhere('razao_social', 'LIKE', "%{$filtro}%")
                            ->paginate(1);
        
        return $resultado;
    }

    public function Endereco()
    {
        return $this->hasMany(EnderecosUnidadeEnsino::class);
    }

    public function unidadesEnsino($situacao)
    {
        return $this->where('situacao', '=', $situacao)->get();
    }

}
