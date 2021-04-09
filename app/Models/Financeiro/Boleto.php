<?php

namespace App\Models\Financeiro;

use Illuminate\Database\Eloquent\Model;

class Boleto extends Model
{
    protected $table = "tb_boletos";
    protected $primaryKey = 'id_boleto';
    
    public $timestamps = false;
        
    protected $fillable = [
        'fk_id_dado_bancario', 
        'nome_pagador', 
        'cpf_cnpj_pagador',
        'cep_pagador',
        'endereco_pagador',
        'bairro_pagador',
        'uf_pagador',
        'cidade_pagador',
        'valor_desconto',
        'data_desconto',
        'valor_total',
        'data_vencimento',
        'fk_id_situacao_registro',
        'data_geracao',        
        'fk_id_usuario_cadastro',
        'instrucoes_dados_aluno',
        'instrucoes_recebiveis',
        'instrucoes_desconto',
        'instrucoes_multa_juros',
        'instrucoes_outros',
        'juros',
        'multa',
        'juros_apos',
        'dias_baixa_automatica',
    ];
   
    /**
     * Retorna todos os recebíveis vinculados a um boleto
     * para baixa de boleto
     */
    function getRecebiveisBoleto($id_boleto)
    {
        return $this
            ->select(
                'id_recebivel',
                'tb_recebiveis.valor_principal',
                'tb_recebiveis.valor_desconto_principal',
                'tb_recebiveis.valor_total',
                'tb_boletos.valor_total as valor_total_boleto',
                'tb_boletos.valor_desconto as valor_desconto_boleto'
                )            
            ->join('tb_boletos_recebiveis', 'fk_id_boleto', 'id_boleto')
            ->join('tb_recebiveis', 'fk_id_recebivel', 'id_recebivel')
            ->where('fk_id_boleto', $id_boleto)
            ->get();
    }

    function getSituacaoBoletoSicoob()
    {
        //situações de boleto
        //convertendo as situações do boleto do Sicoob para o Cibereduca
        $situacoesBoleto = Array(
            '02' => 3, // registrado
            '03' => 6, // rejeitado
            '06' => 4, // pago
        );
        return $situacoesBoleto;
    }

    /**
     * Retorna a quantidade de boletos em uma situação
     * @param situacao int
     * @return qtd int
     */
    function getCountBoletoSituacao($id_situacao)
    {
        return $this->where('fk_id_situacao_registro', $id_situacao)->count();
    }
}
