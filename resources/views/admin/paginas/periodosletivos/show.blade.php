@extends('adminlte::page')

@section('title_postfix', ' Períodos Letivos')

@section('content_header')
    <ol class="breadcrumb">   
        <li class="breadcrumb-item active" >
            <a href="{{ route('periodosletivos.index') }} " class="">Períodos Letivos</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#" class="">Dados Período Letivo</a> 
        </li>
    </ol>
    <h1><b>{{ $periodoletivo->periodo_letivo}} - {{ $periodoletivo->anoLetivo->ano}}</b></h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card-header">
            <ul>
                <li>
                    <strong>Ano:</strong> {{ $periodoletivo->anoLetivo->ano}}
                </li>
                <li>
                    <strong>Período Letivo:</strong> {{ $periodoletivo->periodo_letivo}}
                </li>
                <li>
                    <strong>Data início:</strong> {{date('d/m/Y', strtotime($periodoletivo->data_inicio))}}
                </li>
                <li>
                    <strong>Data fim:</strong> {{date('d/m/Y', strtotime($periodoletivo->data_fim))}}
                </li>
                <li>
                    <strong>Situação:</strong>
                    @if ($periodoletivo->situacao == 1)
                        Aberto
                    @else
                        Encerrado
                    @endif                    
                </li>
                <li>
                    <strong>Data última Alteração:</strong> {{date('d/m/Y', strtotime($periodoletivo->data_alteracao))}}
                </li>
                <li>
                    <strong>Alterado por:</strong> {{ $periodoletivo->usuario->name}}
                </li>
                
            </ul>
            <form action="{{ route('periodosletivos.destroy', $periodoletivo->id_periodo_letivo) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"> <i class="fas fa-trash"></i> Apagar</button>
            </form>
        </div>
    </div>
@endsection