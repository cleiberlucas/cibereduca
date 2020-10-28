@extends('adminlte::page')

@section('title_postfix', ' Captação')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" >
            <a href="{{ route('captacao.index') }} " class="">Captações</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="#" class="">Dados da Captação</a>
        </li>
    </ol>

    <h4><strong>Interessado(a): {{$captacao->nome}} - {{$captacao->tipo_cliente}} - {{$captacao->ano}}</strong></h4>
    <h4><b>Aluno(a): {{$captacao->aluno}} - Série Pretendida: {{$captacao->serie_pretendida}}</b></h4>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card-header">
            <ul>
                <li><strong>Telefone: </strong> {{mascaraTelefone("(##) #####-####", $captacao->telefone_1)}} {{mascaraTelefone("(##) #####-####",$captacao->telefone2)}}
                </li>
                <li><strong>Email:</strong> {{$captacao->email_1}}  {{$captacao->email_2}}</li>
                <li>
                    <strong>Motivo Contato:</strong> {{$captacao->motivo_contato}}
                </li>
                <li>
                    <strong>Situação:</strong> {{ $captacao->tipo_negociacao}} 
                </li>
                <li>
                    <strong>1° Contato:</strong>  {{ date('d/m/Y', strtotime($captacao->data_contato))}}
                </li>
                <li>
                    <strong>Como conheceu a escola:</strong> {{ $captacao->tipo_descoberta}}
                </li>                                
                
                <li><strong>Observações</strong> {{$captacao->observacao}}</li>
                <li>
                    Cadastrado por: {{$captacao->name}} em {{ date('d/m/Y', strtotime($captacao->data_cadastro))}}
                </li>
                
            </ul>
            {{-- <form action="{{ route('captacao.destroy', $captacao->id_captacao) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"> <i class="fas fa-trash"></i> Apagar</button>
            </form> --}}
        </div>
    </div>
@endsection