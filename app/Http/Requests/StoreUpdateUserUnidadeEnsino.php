<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateUserUnidadeEnsino extends FormRequest
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
            'fk_id_user'    => 'nullable',
            'fk_id_unidade_ensino' => 'nullable',
            'situacao_vinculo'  =>  'nullable'
        ];
    }

}
