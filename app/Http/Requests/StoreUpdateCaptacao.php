<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateCaptacao extends FormRequest
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
        $id = $this->segment(3);
    
        return [
            'fk_id_pessoa'           => "required",                        
            'fk_id_ano_letivo'           => "required",
            'fk_id_tipo_cliente'           => "required",
            'fk_id_motivo_contato'        => 'required',
            'fk_id_tipo_negociacao' => 'required',            
                     
        ];    
    }

    public function messages()
    {        
        return [
            'fk_id_pessoa.required'           => "Escolha um responsável.",            
            'fk_id_ano_letivo' => "Escolha um ano letivo",
            'fk_id_tipo_cliente.required'           => "Escolha um tipo de cliente.",
            'fk_id_motivo_contato.required'        => 'Escolha um motivo de contato.',
            'fk_id_tipo_negociacao.required' => 'Escolha um tipo de negociação.',
            
            
        ];    
    }
}
