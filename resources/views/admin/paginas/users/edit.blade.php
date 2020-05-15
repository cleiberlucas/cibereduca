@extends('adminlte::page')

@section('title', 'Rede Educa')

@section('content_header')
    <h1>Editar Usuário </h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('users.update', $user->id)}}" class="form" method="POST">
            @csrf
            @method('PUT')
            
            @include('admin.paginas.users._partials.form')
        </form>
    </div>
@endsection