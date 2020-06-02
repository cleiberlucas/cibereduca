<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateTipoTurma extends FormRequest
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
            'fk_id_ano_letivo' =>'required',
            'tipo_turma' => "required|min:2|max:30|",
            'fk_id_sub_nivel_ensino' => 'required',
            'valor_curso' => "required",            
        ];    
    }

    public function messages()
    {            
        return [
            'fk_id_ano_letivo.required' =>'Escolha um Ano Letivo',
            'tipo_turma.min' => "Informe, no mínimo, 2 caracteres para o nome do Padrão de Turma.",
            'tipo_turma.required' => "Informe o nome do Padrão de Turma.",
            'fk_id_sub_nivel_ensino.required' => 'Escolha o Nível de Ensino.',
            'valor_curso.required' => 'Informe o valor do curso. Não utilize o ponto de "milhar".',            
        ];    
    }
}
