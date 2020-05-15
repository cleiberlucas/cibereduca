@extends('adminlte::page')

@section('title', 'Rede Educa')

@section('content_header')
    <h1>Cadastrar Unidade Ensino </h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('unidadesensino.store')}}" class="form" method="POST">
            @csrf
            
            @include('admin.paginas.unidadesensino._partials.form')
        </form>
    </div>
@endsection