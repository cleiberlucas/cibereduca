<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateBoleto extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {        
        //$id = $this->segment(3);
    
        return [
            'fk_id_dado_bancario'  => 'required',
            'nome_pagador'       => 'required',
            'cpf_cnpj_pagador'        => "required",
            'cep_pagador'   => "required",
            'endereco_pagador'       => "required",
            'bairro_pagador'    => "required",
            'uf_pagador'  => "required",
            'cidade_pagador'  => "required",
            'valor_total'  => "required",            
            'data_vencimento' => "required",
            'fk_id_situacao_registro'  => "required",
            'data_geracao'  => "required",            
            'fk_id_usuario_cadastro' => "required",            
        ];    
    }

    public function messages()
    {        
        return [
            'fk_id_dado_bancario.required'  => 'Dados bancários da unidade de ensino não definidos.',
            'nome_pagador.required'       => 'Informe nome do pagado.',
            'cpf_cnpj_pagador.required'        => "Informe CPF pagador.",
            'cep_pagador.required'             => "Informe o CEP do pagador.",
            'endereco_pagador.required'             => "Informe o endereço do pagador.",
            'bairro_pagador.required'             => "Informe o bairro do pagador.",
            'uf_pagador.required'             => "Informe a UF do pagador.",
            'cidade_pagador.required'             => "Informe a cidade do pagador.",
            'valor_total.required'             => "Valor total não recebido.",
            'data_vencimento.required'       => "Informe a data de vencimento.",        
            'fk_id_situacao_registro.required'            => "Informe a situação do boleto.",
            'data_geracao.required'     => "Situação do recebível não definida.",
            'fk_id_usuario_cadastro.required'     => "Usuário cadastro não definido.",
            
        ];    
    }
}
