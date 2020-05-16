<?php

namespace App\Models\Secretaria;

use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    protected $table = "tb_pessoas";
    protected $primaryKey = 'id_pessoa';
    
    public $timestamps = false;
    
    //protected $attributes = ['situacao_disciplina' => '0'];

    protected $fillable = ['nome', 'cpf', 'doc_identidade', 'data_nascimento', 'telefone_1', 'telefone_2', 'email_1', 'email_2', 'fk_id_tipo_pessoa', 'fk_id_user'];
   
    public function search($filtro = null, $tipo_pessoa)
    {
        //dd($tipo_pessoa);
        $resultado = $this->where('nome', 'LIKE', "%{$filtro}%")
                            ->where('fk_id_tipo_pessoa', '=', "{$tipo_pessoa}") 
                            ->paginate();
        
        return $resultado;
    }

    public function tipoPessoa()
    {       
        return $this->belongsTo(TipoPessoa::class, 'fk_id_tipo_pessoa', 'id_tipo_pessoa');
    }
}
