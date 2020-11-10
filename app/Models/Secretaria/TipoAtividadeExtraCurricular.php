<?php

namespace App\Models\Secretaria;

use Illuminate\Database\Eloquent\Model;

class TipoAtividadeExtraCurricular extends Model
{
    protected $table = "tb_tipos_atividades_extracurriculares";
    protected $primaryKey = 'id_tipo_atividade_extracurricular';
    
    public $timestamps = false;
    
    //protected $attributes = ['situacao_disciplina' => '0'];

    protected $fillable = ['tipo_atividade_extracurricular', 'titulo_contrato', 'fk_id_ano_letivo', 'valor_padrao_atividade', 'valor_padrao_material', 'situacao_atividade', 'data_cadastro', 'fk_id_usuario'];
   
    public function search($filtro = null)
    {
        $resultado = $this->where('tipo_atividade_extracurricular', 'LIKE', "%{$filtro}%")                            
            ->orderBy('tipo_atividade_extracurricular')
            ->paginate();
        
        return $resultado;
    }

    /**
     * Retorna dados de uma atividade
     * @param int id_tipo_atividade_extracurricular
     * @return array dados
     */
    public function getTipoAtividadeExtraCurricular(int $id_tipo_atividade_extracurricular)
    {
        $atividade = $this->where('id_tipo_atividade_extracurricular', $id_tipo_atividade_extracurricular)->first();

        return $atividade;
    }
}
