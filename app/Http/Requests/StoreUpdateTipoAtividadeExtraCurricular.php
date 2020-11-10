<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateTipoAtividadeExtraCurricular extends FormRequest
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
            'tipo_atividade_extracurricular' => "required|min:3|max:100|unique:tb_tipos_atividades_extracurriculares,tipo_atividade_extracurricular,{$id},id_tipo_atividade_extracurricular",
            'fk_id_ano_letivo' => "required",          
        ];    
    }

    public function messages()
    {
        return [
            'tipo_atividade_extracurricular.min'    => 'Informe, no mínimo, 3 caracteres para o tipo de atividade.',
            'tipo_atividade_extracurricular.required'    => 'Informe a atividade.',            

        ];
    }
}
