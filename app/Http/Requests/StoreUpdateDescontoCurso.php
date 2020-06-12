<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateDescontoCurso extends FormRequest
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
            'tipo_desconto_curso' => "required|min:3|max:45|unique:tb_tipos_desconto_curso,tipo_desconto_curso,{$id},id_tipo_desconto_curso",                        
        ];    
    }
}
