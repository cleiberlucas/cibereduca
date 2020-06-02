<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateTurma extends FormRequest
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
            'fk_id_tipo_turma'  => 'required',
            'fk_id_turno'       => 'required',
            'nome_turma'        => "required|min:2|max:45|",
            'localizacao'       => "required|min:2|max:45|",        
            'limite_alunos'     => "nullable|min:1|max:3",
        ];    
    }

    public function messages()
    {        
        return [
            'fk_id_tipo_turma.required'  => 'Escolha um padrão de turma.',
            'fk_id_turno.required'       => 'Escolha um turno.',
            'nome_turma.required'        => "Defina o nome da turma. Exemplo: 1º Ano A.",
            'nome_turma.min'             => "Informe, no mínimo, 2 caracteres para o nome da turma.",
            'localizacao.required'       => "Informe a localização da sala. Exemplo: 1º Andar - sala 101",        
            'localizacao.min'            => "Informe, no mínimo, 2 caracteres para o localização.",
            'limite_alunos.required'     => "Informe o limite de alunos",
        ];    
    }
}
