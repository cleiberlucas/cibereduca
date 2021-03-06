<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateUnidadeEnsino extends FormRequest
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
            'razao_social' => "required|min:3|max:150|unique:tb_unidades_ensino,razao_social,{$id},id_unidade_ensino",
            'nome_fantasia' => 'required|min:3|max:150',
        ];
    }

    public function messages()
    {     
        return [
            'razao_social.required' => "Informe a Razão Social.",
            'razao_social.min'      => "Informe, no mínimo, 3 caracteres para a Razão Social.",
            'nome_fantasia.required' => 'Informe o Nome Fantasia.',
            'nome_fantasia.min' => 'Informe, no mínimo, 3 caracteres para o Nome Fantasia.',
        ];
    }
}
