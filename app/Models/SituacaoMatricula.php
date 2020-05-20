<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SituacaoMatricula extends Model
{
    protected $table = "tb_situacoes_matricula";
    protected $primaryKey = 'id_situacao_matricula';
    
    public $timestamps = false;
    
    //protected $attributes = ['situacao_disciplina' => '0'];

    protected $fillable = ['situacao_matricula', 'status_situacao_matricula'];
   
    public function search($filtro = null)
    {
        $resultado = $this->where('situacao_matricula', 'LIKE', "%{$filtro}%")
                            ->paginate();
        
        return $resultado;
    }
}
