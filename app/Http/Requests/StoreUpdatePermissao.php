<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdatePermissao extends FormRequest
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
            'permissao' => "required|min:3|max:45|unique:tb_permissoes,permissao,{$id},id_permissao",
            'descricao_permissao' => "nullable|min:3|max:255",
            
        ];    
    }
}
