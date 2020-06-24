<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateTipoFrequencia extends FormRequest
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
            'tipo_frequencia'   => "required|min:3|max:45|unique:tb_tipos_frequencia,tipo_frequencia,{$id},id_tipo_frequencia",
            'sigla_frequencia'  => "required|min:1|max:1|unique:tb_tipos_frequencia,sigla_frequencia,{$id},id_tipo_frequencia",
            'reprova'           => 'required',
            'padrao'            => 'required',
            'situacao'          => 'nullable',
        ];
    }

}
