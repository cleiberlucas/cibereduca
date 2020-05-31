@extends('adminlte::page')

@section('title_postfix', ' Atendimento Especializado')

@section('content_header')
    <h1>Atendimento Especializado <b>{{ $atendimentoEspecializado->atendimento_especializado}}</b></h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <ul>
                <li>
                    <strong>Atendimento Especializado:</strong> {{ $atendimentoEspecializado->atendimento_especializado}}
                </li>
            </ul>
            <form action="{{ route('atendimentosespecializados.destroy', $atendimentoEspecializado->id_atendimento_especializado) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"> <i class="fas fa-trash"></i> Apagar</button>
            </form>
        </div>
    </div>
@endsection