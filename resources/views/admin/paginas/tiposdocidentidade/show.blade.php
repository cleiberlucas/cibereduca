@extends('adminlte::page')

@section('title_postfix', ' Tipo Documento Identidade')

@section('content_header')
    <h1>Tipo Documento Identidade <b>{{ $tipoDocIdentidade->tipo_doc_identidade}}</b></h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <ul>
                <li>
                    <strong>Tipo Documento Identidade:</strong> {{ $tipoDocIdentidade->tipo_doc_identidade}}
                </li>
            </ul>
            <form action="{{ route('tiposdocidentidade.destroy', $tipoDocIdentidade->id_tipo_doc_identidade) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"> <i class="fas fa-trash"></i> Apagar</button>
            </form>
        </div>
    </div>
@endsection