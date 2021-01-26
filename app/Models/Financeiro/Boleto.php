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
   
}
