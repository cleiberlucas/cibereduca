@extends('adminlte::page')

@section('title_postfix', ' Padrão de Turma')

@section('content_header')
    
@stop

@section('content')
    <h1><strong>{{ $tipoturma->anoLetivo->unidadeEnsino->nome_fantasia}}</strong></h1>
    <h1><b>Padrão de Turma {{ $tipoturma->tipo_turma}} - {{ $tipoturma->anoLetivo->ano}}</b></h1>
    <div class="container-fluid">        
        <div class="card-header">
            <ul>
                <li>
                    <strong>Ano Letivo:</strong> {{ $tipoturma->anoLetivo->ano}}
                </li>
                <li>
                    <strong>Padrão Turma:</strong> {{ $tipoturma->tipo_turma}}
                </li>
                <li>
                    <strong>Nível Ensino:</strong> {{ $tipoturma->subNivelEnsino->sub_nivel_ensino}}
                </li>
                <li>
                    <strong>Valor Curso:</strong> R$ {{ number_format($tipoturma->valor_curso, 2, ',', '.')}}
                </li>                                
                <li>
                    <strong>Cadastrado por:</strong> {{ $tipoturma->usuario->name}}
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