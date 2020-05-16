@extends('adminlte::page')

@section('title_postfix', ' Disciplinas')

@section('content_header')
    <h1>Disciplina <b>{{ $disciplina->disciplina}}</b></h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <ul>
                <li>
                    <strong>Disciplina:</strong> {{ $disciplina->disciplina}}
                </li>
                <li>
                    <strong>Sigla:</strong> {{ $disciplina->sigla_disciplina}}
                </li>
                
            </ul>
            <form action="{{ route('disciplinas.destroy', $disciplina->id_disciplina) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"> <i class="fas fa-trash"></i> Apagar</button>
            </form>
        </div>
    </div>
@endsection