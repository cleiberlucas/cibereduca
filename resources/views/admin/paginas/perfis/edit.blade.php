@extends('adminlte::page')

@section('title', 'Rede Educa')

@section('content_header')
    <h1>Editar Perfil </h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('perfis.update', $perfil->id_perfil)}}" class="form" method="POST">
            @csrf
            @method('PUT')
            
            @include('admin.paginas.perfis._partials.form')
        </form>
    </div>
@endsection