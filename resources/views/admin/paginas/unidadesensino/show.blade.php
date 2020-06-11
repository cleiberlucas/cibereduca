@extends('adminlte::page')

@section('title', 'Detalhes Unidade Ensino')

@section('content_header')
<ol class="breadcrumb">        
    <li class="breadcrumb-item active" >        
        <a href="{{ route('unidadesensino.index') }} " class="">Unidades Ensino</a>
    </li>
    <li class="breadcrumb-item active" >
        <a href="#" class="">Dados da Unidade de Ensino</a>
    </li>
</ol> 
    <h1>Unidade Ensino <b>{{ $UnidadeEnsino->razao_social}}</b></h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <ul>
                <li>
                    <strong>Nome Fantasia:</strong> {{ $UnidadeEnsino->nome_fantasia}}
                </li>
                <li>
                    <strong>CNPJ:</strong> {{ $UnidadeEnsino->cnpj}}
                </li>
                <li>
                    <strong>Assina documentos:</strong> {{$UnidadeEnsino->nome_assinatura}}
                </li>
                <li>
                    <strong>E-mail:</strong> {{$UnidadeEnsino->email}}
                </li>
                <li>
                    <strong>Situação:</strong> 
                        @if ($UnidadeEnsino->situacao == 1)
                            <b>Ativo</b>
                        @else
                            Inativo                                        
                        @endif
                </li>
            </ul>
            <form action="{{ route('unidadesensino.destroy', $UnidadeEnsino->id_unidade_ensino) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"> <i class="fas fa-trash"></i> Apagar</button>
            </form>
        </div>
    </div>
@endsection