@extends('adminlte::page')

@section('title_postfix', ' tipo_documentos')

@section('content_header')
    <h1>Tipo de Documento <b>{{ $tipoDocumento->tipo_documento}}</b></h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <ul>
                <li>
                    <strong>Nome do Documento:</strong> {{ $tipoDocumento->tipo_documento}}
                </li>
                <li>
                    <strong>Comentário:</strong> {{ $tipoDocumento->comentario}}
                </li>
                <li>
                    <strong>Obrigatório:</strong> 
                    @if ($tipoDocumento->obrigatorio == 1)
                        Sim
                    @else
                        Não
                    @endif
                </li>
                <li>
                    <strong>Situação:</strong>
                    @if ($tipoDocumento->situacao == 1)
                        Ativo
                    @else
                        Inativo
                    @endif
                </li>
                
            </ul>
            <form action="{{ route('tiposdocumentos.destroy', $tipoDocumento->id_tipo_documento) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"> <i class="fas fa-trash"></i> Apagar</button>
            </form>
        </div>
    </div>
@endsection