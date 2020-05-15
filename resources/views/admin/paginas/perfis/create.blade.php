@extends('adminlte::page')

@section('title', 'Rede Educa')

@section('content_header')
    <h1>Cadastrar Perfil </h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('perfis.store')}}" class="form" method="POST">
            @csrf
            
            @include('admin.paginas.perfis._partials.form')
        </form>
    </div>
@endsection