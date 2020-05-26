@extends('adminlte::page')

@section('title_postfix', ' Anos Letivos')

@section('content_header')
    <h1>Ano Letivo <b>{{ $anoletivo->ano}}</b></h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <ul>
                <li>
                    <strong>Ano:</strong> {{ $anoletivo->ano}}
                </li>
                <li>
                    <strong>Média aprovação:</strong> {{ $anoletivo->media_minima_aprovacao}}
                </li>
                <li>
                    <strong>Situação:</strong>
                    @if ($anoletivo->situacao == 1)
                        Aberto
                    @else
                        Encerrado
                    @endif                    
                </li>
                
            </ul>
            <form action="{{ route('anosletivos.destroy', $anoletivo->id_ano_letivo) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"> <i class="fas fa-trash"></i> Apagar</button>
            </form>
        </div>
    </div>
@endsection