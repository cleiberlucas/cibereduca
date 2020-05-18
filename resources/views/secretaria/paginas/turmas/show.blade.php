@extends('adminlte::page')

@section('title_postfix', ' Turma')

@section('content_header')
    <h1><b>{{ $turma->nome_turma}} - 2020</b></h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card-header">
            <ul>
                <li>
                    <strong>Ano:</strong>2020
                </li>
                <li>
                    <strong>Turma:</strong> {{ $turma->turma}} - {{$turma->turno->descricao_turno}}
                </li>
                <li>
                    <strong>Localização:</strong>  {{ $turma->localizacao}}
                </li>
                <li>
                    <strong>Limite alunos:</strong> {{ $turma->limite_alunos}}
                </li>                                
                <li>
                    <strong>Usuário cadastro:</strong> Fulano
                </li>
                
            </ul>
            <form action="{{ route('turmas.destroy', $turma->id_turma) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"> <i class="fas fa-trash"></i> Apagar</button>
            </form>
        </div>
    </div>
@endsection