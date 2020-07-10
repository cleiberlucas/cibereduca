<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateTipoAvaliacao extends FormRequest
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
            'tipo_avaliacao'   => "required|min:3|max:45|unique:tb_tipos_avaliacao,tipo_avaliacao,{$id},id_tipo_avaliacao",
            'sigla_avaliacao'  => "required|min:1|max:3|unique:tb_tipos_avaliacao,sigla_avaliacao,{$id},id_tipo_avaliacao",            
            'situacao'          => 'nullable',
        ];
    }

}
