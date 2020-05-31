<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateTipoDocIdentidade extends FormRequest
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
            'tipo_doc_identidade' => "required|min:3|max:80|unique:tb_tipos_doc_identidade,tipo_doc_identidade,{$id},id_tipo_doc_identidade",
        ];
    }
}
