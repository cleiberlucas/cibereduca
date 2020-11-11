<?php

namespace App\Models\Secretaria;

use Illuminate\Database\Eloquent\Model;

class ContratoAtividadeExtraCurricular extends Model
{
    protected $table = "tb_contratos_atividades_extracurriculares";
    protected $primaryKey = 'id_contrato_atividade_extracurricular';
    
    public $timestamps = false;

    protected $fillable = ['fk_id_matricula',
         'fk_id_tipo_atividade_extracurricular',
          'data_contratacao', 
          'valor_curso',
          'quantidade_parcelas',
          'data_venc_parcela_um',
          'fk_id_forma_pagto_ativ',
          'valor_material',
          'fk_id_forma_pagto_material',
          'observacao',
          'data_cancelamento',
          'data_cadastro',
          'fk_id_usuario_cadastro',
          'data_lanca_cancelamento',          
          'fk_id_usuario_cancelamento'];
   
   /*  public function search($filtro = null)
    {
        $resultado = $this->where('contrato_atividade_extracurricular', 'LIKE', "%{$filtro}%")                            
            ->orderBy('contrato_atividade_extracurricular')
            ->paginate();
        
        return $resultado;
    } */

    /**
     * Retorna dados de uma atividade
     * @param int contrattipo_atividade_extracurricular
     * @return array dados
     */
   /*  public function getTipoAtividadeExtraCurricular(int $contrattipo_atividade_extracurricular)
    {
        $atividade = $this->where('contrattipo_atividade_extracurricular', $contrattipo_atividade_extracurricular)->first();

        return $atividade;
    } */
}
