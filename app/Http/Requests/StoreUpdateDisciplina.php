<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateDisciplina extends FormRequest
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
            'disciplina' => "required|min:3|max:100|unique:tb_disciplinas,disciplina,{$id},id_disciplina",
            'sigla_disciplina' => "required|min:2|max:20",          
        ];    
    }

    public function messages()
    {
        return [
            'disciplina.min'    => 'Informe, no mínimo, 3 caracteres para o nome da disciplina',
            'disciplina.required'    => 'Informe o nome da disciplina',
            'sigla_disciplina.required'         => 'Informe a sigla.',
            'sigla_disciplina.min'         => 'Informe, no mínimo, 2 caracteres para a sigla.',
            'sigla_disciplina.max'         => 'Informe, no máximo, 20 caracteres para a sigla.',

        ];
    }
}
