<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateAnoLetivo extends FormRequest
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
            'fk_id_unidade_ensino' => 'required',
            'ano' => "required|min:4|max:4",
            'media_minima_aprovacao' => "required|min:2|max:3",
        ];    
    }

    public function messages()
    { 
        return [
            'fk_id_unidade_ensino.required' => 'Escolha uma unidade de ensino.',
            'ano.min' => "Informe o Ano Letivo dom 4 caracteres.",
            'ano.unique' => "O ano letivo informado já está cadastrado.",
            'media_minima_aprovacao.min' => "Informa a média mínima para aprovação.",
        ];    
    }


}
