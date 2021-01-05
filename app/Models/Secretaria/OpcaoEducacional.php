<?php

namespace App\Models\OpcaoEducacional;

use Illuminate\Database\Eloquent\Model;

class OpcaoEducacional extends Model
{
    protected $table = "tb_opcoes_educacionais";
    protected $primaryKey = 'id_opcao_educaional';
    
    public $timestamps = false;
        
    protected $fillable = ['fk_id_matricula', 'opcao_educacional', 'observacoes', 'fk_id_usuario', 'data_hora',
        ];
   
   /*  public function search($filtro = null)
    {
        $resultado = $this
            ->select('tb_captacoes.*',
                'tb_pessoas.nome',
                'ano',
                'tipo_negociacao')
            ->join('tb_pessoas', 'fk_id_pessoa', 'id_pessoa')
            ->leftJoin('tb_anos_letivos', 'fk_id_ano_letivo', 'id_ano_letivo')
            ->join('tb_unidades_ensino', 'tb_captacoes.fk_id_unidade_ensino', 'id_unidade_ensino')
            ->join('tb_tipos_negociacao', 'fk_id_tipo_negociacao', 'id_tipo_negociacao')
            ->where('tb_captacoes.fk_id_unidade_ensino', session()->get('id_unidade_ensino'))            
            ->where('aluno', 'like', "%{$filtro}%")                             
            ->orWhere('nome', 'like', "%{$filtro}%")                 
            ->orderBy('data_agenda', 'desc')
            ->orderBy('hora_agenda')
            ->orderBy('nome') 
            ->paginate(25);
        
        return $resultado;
    } */

   
}
