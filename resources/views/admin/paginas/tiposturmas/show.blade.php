@extends('adminlte::page')

@section('title_postfix', ' Padrão de Turma')

@section('content_header')
    <h1><b>{{ $tipoturma->tipo_turma}} - {{ $tipoturma->anoLetivo->ano}}</b></h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card-header">
            <ul>
                <li>
                    <strong>Ano:</strong> {{ $tipoturma->anoLetivo->ano}}
                </li>
                <li>
                    <strong>Padrão Turma:</strong> {{ $tipoturma->tipo_turma}}
                </li>
                <li>
                    <strong>Nível Ensino:</strong> 
                </li>
                <li>
                    <strong>Valor padrão mensalidade:</strong> {{ $tipoturma->valor_padrao_mensalidade}}
                </li>                                
                <li>
                    <strong>Usuário cadastro:</strong> 
                </li>
                
            </ul>
            <form action="{{ route('tiposturmas.destroy', $tipoturma->id_tipo_turma) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"> <i class="fas fa-trash"></i> Apagar</button>
            </form>
        </div>
    </div>
@endsection