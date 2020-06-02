<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateMatricula extends FormRequest
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
            'fk_id_aluno'           => "required",
            'fk_id_responsavel'     => "required",
            'fk_id_turma'           => "required",
            'data_matricula'        => 'required',
            'data_limite_desistencia' => 'required|after:data_matricula',
            'valor_matricula'       => 'required',
            'valor_desconto'        => 'nullable',
            'qt_parcelas_curso'     => 'required',
            'data_venc_parcela_um'  => 'required',
            'fk_id_forma_pagto_curso' => 'required',
            'fk_id_situacao_matricula' => 'required',
        ];    
    }

    public function messages()
    {        
        return [
            'fk_id_aluno.required'           => "Escolha um aluno.",
            'fk_id_responsavel.required'     => "Escolha um responsável.",
            'fk_id_turma.required'           => "Erro - turma não definida.",
            'data_matricula.required'        => 'Informe a data da matrícula.',
            'data_limite_desistencia.required' => 'Informe a data limite para desistência da matrícula',
            'data_limite_desistencia.after'  => 'A data limite de desistência deve ser posterior à data da matrícula',
            'valor_matricula.required'       => 'Informe o valor da matrícula.',
            'valor_desconto.nullable'        => 'Informe o valor do desconto. Não utilize o ponto de "milhar".',
            'qt_parcelas_curso.required'     => 'Informe a quantidade de parcelas do curso.',
            'data_venc_parcela_um.required'  => 'Informe a data de vencimento da primeira parcela.',
            'fk_id_forma_pagto_curso.required' => 'Escolha a forma de pagamento do curso.',
            'fk_id_situacao_matricula.required' => 'Escolha a situação da matrícula.',
        ];    
    }
}
