<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateOpcaoEducacional extends FormRequest
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
        return [
            'fk_id_matricula'   => "required",                                    
            'opcao_educacional' => "required",
            'fk_id_usuario'        => 'required',            
                     
        ];    
    }

    public function messages()
    {        
        return [
            'fk_id_matricula.required'           => "Escolha um aluno.",                        
            'fk_id_tipo_cliente.required'           => "Defina uma Opção Educacional.",
            'fk_id_user.required'        => 'Usuário não identificado. Favor entrar em contato com o Colégio.',
            
        ];    
    }
}
