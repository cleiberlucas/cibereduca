<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateTurma extends FormRequest
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
            'nome_turma' => "required|min:2|max:45|",
            'localizacao' => "required|min:2|max:45|",        
            'limite_alunos' => "nullable|min:1|max:3",
        ];    
    }
}
