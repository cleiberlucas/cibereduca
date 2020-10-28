<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateHistoricoCaptacao extends FormRequest
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
            'fk_id_captacao'           => "required",
            'data_contato'     => "required",
            'historico'           => "required",
            'fk_id_motivo_contato'        => 'required',            
            
        ];    
    }

    public function messages()
    {        
        return [
            'fk_id_captacao.required'           => "Escolha um responsável.",
            'data_contato.required'     => "Escolha um aluno.",
            'historico.required'           => "Escolha um tipo de cliente.",
            'fk_id_motivo_contato.required'        => 'Escolha um motivo de contato.',            
            
        ];    
    }
}
