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
            'ano' => "required|min:4|max:4|unique:tb_anos_letivos,ano,{$id},id_ano_letivo",
            'media_minima_aprovacao' => "required|min:2|max:3",           
            
        ];    
    }
}
