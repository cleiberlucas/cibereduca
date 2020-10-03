<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateRecebivel extends FormRequest
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
            /* 'fk_id_unidade_ensino'  => 'required',
            'fk_id_matricula'       => 'required',
            'fk_id_conta_contabil_principal'        => "required", */
            'valor_principal'   => "required",
            'valor_total'       => "required",
            'data_vencimento' => "required",
            'parcela' => "required",            
            'fk_id_usuario_cadastro' => "required",            
        ];    
    }

    public function messages()
    {        
        return [
            'fk_id_unidade_ensino.required'  => 'Unidade de ensino não definida.',
            'fk_id_matricula.required'       => 'Matrícula não recebida.',
            'fk_id_conta_contabil_principal.required'        => "Escolha a conta contábil.",
            'valor_principal.required'             => "Informe o valor principal.",
            'valor_total.required'             => "Valor total não recebido.",
            'data_vencimento.required'       => "Informe a data de vencimento.",        
            'parcela.required'            => "Informe o número da parcela.",
            'fk_id_situacao_recebivel.required'     => "Situação do recebível não definida.",
            'fk_id_usuario_cadastro.required'     => "Usuário cadastro não definido.",
            
        ];    
    }
}
