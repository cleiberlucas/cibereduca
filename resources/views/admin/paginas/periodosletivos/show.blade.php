@extends('adminlte::page')

@section('title_postfix', ' Períodos Letivos')

@section('content_header')
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
                    <strong>Data início:</strong> {{ $periodoletivo->data_inicio}}
                </li>
                <li>
                    <strong>Data fim:</strong> {{ $periodoletivo->data_fim}}
                </li>
                <li>
                    <strong>Situação:</strong>
                    @if ($periodoletivo->situacao == 1)
                        Aberto
                    @else
                        Encerrado
                    @endif
                    {{ $periodoletivo->media_minima_aprovacao}}
                </li>
                <li>
                    <strong>Data última Alteração:</strong> {{ $periodoletivo->data_inicio}}
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