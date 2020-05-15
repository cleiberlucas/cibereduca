@extends('adminlte::page')

@section('title', 'Rede Educa')

@section('content_header')
    <h1>Cadastrar Usuário </h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('users.store')}}" class="form" method="POST">
            @csrf
            
            @include('admin.paginas.users._partials.form')
        </form>
    </div>
@endsection