@extends('adminlte::page')

@section('title_postfix', ' '.$tipoPessoa)

@section('content_header')
    <h1>Ficha cadastral - <b>{{ $pessoa->nome}}</b></h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <ul>
                <li>
                    <strong>Nome:</strong> {{ $pessoa->nome}}
                </li>
                <li>
                    <strong>CPF:</strong> {{ $pessoa->cpf}}
                </li>
                <li>
                    <strong>Documento identidade:</strong> {{ $pessoa->doc_identidade}}
                </li>
                <li>
                    <strong>Data Nascimento:</strong> {{ $pessoa->data_nascimento}}
                </li>
                <li>
                    <strong>Fone principal:</strong> {{ $pessoa->telefone_1}}
                </li>
                <li>
                    <strong>Fone:</strong> {{ $pessoa->telefone_2}}
                </li>
                <li>
                    <strong>Email principal:</strong> {{ $pessoa->email_1}}
                </li>
                <li>
                    <strong>Email:</strong> {{ $pessoa->email_2}}
                </li>
                <li>
                    <strong>Situação:</strong>  
                    @if ($pessoa->situacao_pessoa == 1)
                        Ativo
                    @else
                        Inativo
                    @endif
                </li>
                <li>
                    <strong>Cadastrado por:</strong> {{ $pessoa->usuario->name}}
                </li>
                
            </ul>
            <form action="{{ route('pessoas.destroy', $pessoa->id_pessoa) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"> <i class="fas fa-trash"></i> Apagar</button>
            </form>
        </div>
    </div>
@endsection