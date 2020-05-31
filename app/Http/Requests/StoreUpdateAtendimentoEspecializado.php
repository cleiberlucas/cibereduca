<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateAtendimentoEspecializado extends FormRequest
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
            'atendimento_especializado' => "required|min:3|max:45|unique:tb_atendimentos_especializados,atendimento_especializado,{$id},id_atendimento_especializado",            
            
        ];    
    }
}
