@extends('adminlte::page')

@section('title_postfix', ' Turma')

@section('content_header')
    <h1><b>Dados da Matrícula - {{$matricula->ano}} </b></h1>
    <h1><b>{{ $matricula->nome_aluno}} - {{$matricula->nome_turma}} - {{$matricula->descricao_turno}}</b></h1>
    <h3>Responsável: {{$matricula->nome_responsavel}} - Fone: {{$matricula->telefone_1}}</h3>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card-header">
            <ul>
                <li>
                    <strong>Matrícula:</strong> R$ {{$matricula->valor_matricula}}
                        Pago em: {{$matricula->data_pagto_matricula}}
                        Limite Desistência: {{$matricula->data_limite_desistencia}}
                </li>
                <li>
                    <strong>Curso:</strong> R$ {{$matricula->valor_curso}}
                        Desconto de R$: {{$matricula->valor_desconto}} - 
                        Parcelas: {{$matricula->qt_parcelas_curso}}
                     
                </li>
                <li>
                    <strong>Material Didático:</strong> R$ {{$matricula->valor_material_didatico}}
                        Pago em: {{$matricula->data_pagto_mat_didatico}} - 
                        Parcelas: {{$matricula->qt_parcelas_mat_didatico}}
                </li>
                <li>
                    <strong>Situação:</strong>
                </li>
                <li>
                    <strong>Cadastro em:</strong> {{$matricula->data_cadastro}} - por 
                </li>                                
                <li>
                    <strong>Última alteração em:</strong> {{$matricula->data_alteracao}} - por
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