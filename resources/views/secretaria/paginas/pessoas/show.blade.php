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
                    <strong>Data Nascimento:</strong> {{ $pessoa->data_nascimento}}
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