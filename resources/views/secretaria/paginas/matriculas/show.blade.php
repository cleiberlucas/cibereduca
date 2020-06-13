@extends('adminlte::page')

@section('title_postfix', ' Turma')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div class="p-2">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" >
                    <a href="{{ route('turmas.index') }} " class="">Turmas</a>
                </li>
                <li class="breadcrumb-item active" >
                    <a href="{{route('matriculas.index', $matricula->fk_id_turma)}}" class="">Matrículas</a>
                </li>
                <li class="breadcrumb-item active" >
                    <a href="#" class="">Dados da Matrícula</a>
                </li>
            </ol>
        
            <h1><b>Dados da Matrícula - {{$matricula->ano}} </b></h1>
            <h1><b>{{ $matricula->nome_aluno}} - {{$matricula->nome_turma}} - {{$matricula->descricao_turno}}</b></h1>
            <h3>Responsável: {{$matricula->nome_responsavel}} - Fone: {{$matricula->telefone_1}}</h3>
        </div>
        <div class="p-2">
            <img src="{{url("storage/$matricula->foto")}}" alt="" width="100" heigth="200">
        </div>
        <div class="p-2"> </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card-header">
            <ul>
                <li>
                    <strong>Matriculado em :</strong> {{date('d/m/Y', strtotime($matricula->data_matricula))}}
                        - Limite Desistência: {{date('d/m/Y', strtotime($matricula->data_limite_desistencia))}}
                        <br>
                        - Valor: R$ {{number_format($matricula->valor_matricula, 2, ',', '.')}}
                        - Pago em: {{date('d/m/Y', strtotime($matricula->data_pagto_matricula))}} - {{$matricula->formaPagamentoMatricula->forma_pagamento ?? ''}}
                        
                </li>
                <br>
                <li>
                    <strong>Curso:</strong> R$ {{number_format($matricula->turma->tipoTurma->valor_curso, 2, ',', '.')}}
                        - Desconto de R$: {{number_format($matricula->valor_desconto, 2, ',', '.')}} <br>
                        - Número de Parcelas: {{$matricula->qt_parcelas_curso}} 
                        - Data 1ª parcela: {{date('d/m/Y', strtotime($matricula->data_venc_parcela_um))}}
                        - Forma de pagamento: {{$matricula->formaPagamentoCurso->forma_pagamento}}                     
                </li>
                <br>
                <li>
                    <strong>Material Didático:</strong> R$ {{number_format($matricula->valor_material_didatico, 2, ',', '.')}}
                        - Pago em: {{date('d/m/Y', strtotime($matricula->data_pagto_mat_didatico))}}
                        - Número de Parcelas: {{$matricula->qt_parcelas_mat_didatico}} Forma de pagamento: {{$matricula->formaPagamentoMaterialDidatico->forma_pagamento ?? ''}}
                </li>
                <br>
                <li><strong>Atendimento Especializado:</strong></li> {{$matricula->atendimentoEspecializado->atendimentoEspecializado ?? ''}}
                <br>
                <li>
                    <strong>Situação:</strong> {{$matricula->situacaoMatricula->situacao_matricula}}
                </li>
                <br>
                <li><strong>Observações:</strong> {{$matricula->obs_matricula}}</li> 
                <br>
                <li>
                    <strong>Cadastro em:</strong> {{date('d/m/Y H:m:i', strtotime($matricula->data_cadastro))}} - por {{$matricula->nome_user_cadastro}}
                </li>                                
                <br>
                <li>
                    <strong>Última alteração em:</strong> {{date('d/m/Y H:m:i', strtotime($matricula->data_alteracao))}} - por {{$matricula->nome_user_altera}}
                </li>
                
            </ul>
            <form action="{{ route('matriculas.destroy', $matricula->id_matricula) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"> <i class="fas fa-trash"></i> Apagar</button>
            </form>
        </div>
    </div>
@endsection