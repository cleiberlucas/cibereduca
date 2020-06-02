<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdatePeriodoLetivo extends FormRequest
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
            'fk_id_ano_letivo' => 'required',
            'periodo_letivo' => "required|min:3|max:45|",
            'data_inicio' => 'required|date_format:Y-m-d',
            'data_fim' => 'required|date_format:Y-m-d|after:data_inicio',
            
        ];    
    }

    public function messages()
    {        
        return [
            'fk_id_ano_letivo.required' => 'Escolha um Ano Letivo.',
            'periodo_letivo.required' => "Informe o Período Letivo",
            'periodo_letivo.min' => "Informe, no mínimo, 3 caracteres para o Período Letivo",
            'data_inicio.required' => "Informe a data de início.",
            'data_inicio.date_format' => "Verifique a data de início.", 
            'data_fim.required' => "Informe a data de fim.",
            'data_fim.date_format' => "Verifique a data de fim.", 
            'data_fim.after'        => "A Data de Fim de ser posterior à data de início."
        ];    
    }
}
