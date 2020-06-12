@extends('adminlte::page')

@section('title_postfix', ' Desconto Curso')

@section('content_header')
    <h1>Desconto Curso <b>{{ $descontoCurso->tipo_desconto_curso}}</b></h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <ul>
                <li>
                    <strong>Desconto Curso:</strong> {{ $descontoCurso->tipo_desconto_curso}}
                </li>
            </ul>
            <form action="{{ route('descontoscursos.destroy', $descontoCurso->id_tipo_desconto_curso) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"> <i class="fas fa-trash"></i> Apagar</button>
            </form>
        </div>
    </div>
@endsection