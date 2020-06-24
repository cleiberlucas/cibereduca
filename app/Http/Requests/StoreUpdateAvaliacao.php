<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateAvaliacao extends FormRequest
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
            'fk_id_tipo_turma'         => 'required', 
            'fk_id_periodo_letivo'     => 'required',
            'fk_id_disciplina'         => 'required',
            'fk_id_tipo_avaliacao'     => 'required',
            'valor_avaliacao'          => 'required',
        ];
    }

}
