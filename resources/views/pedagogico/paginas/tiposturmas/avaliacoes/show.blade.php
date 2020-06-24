@extends('adminlte::page')

@section('title_postfix', ' Turma')

@section('content_header')
<ol class="breadcrumb">
    <li class="breadcrumb-item active" >
        <a href="{{ route('turmas.index') }} " class="">Turmas</a>
    </li>
    <li class="breadcrumb-item active" >
        <a href="#" class="">Dados da Turma</a>
    </li>
</ol>

    <h1><strong>{{$turma->tipoTurma->anoLetivo->unidadeEnsino->nome_fantasia}}</strong></h1>
    <h1><b>{{$turma->tipoTurma->anoLetivo->ano}} - {{ $turma->nome_turma}} - {{$turma->turno->descricao_turno}}</b></h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card-header">
            <ul>
                <li>
                    <strong>Ano:</strong> {{$turma->tipoTurma->anoLetivo->ano}}
                </li>
                <li>
                    <strong>Turma:</strong> {{ $turma->nome_turma}} - {{$turma->turno->descricao_turno}}
                </li>
                <li>
                    <strong>Localização:</strong>  {{ $turma->localizacao}}
                </li>
                <li>
                    <strong>Limite alunos:</strong> {{ $turma->limite_alunos}}
                </li>                                
                <li><strong>Situação: </strong>
                    @if ($turma->situacao_turma == 1)
                        Aberta
                    @else
                        Encerrada
                    @endif
                </li>
                <li>
                    <strong>Cadastrado por:</strong> {{$turma->usuario->name}}
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