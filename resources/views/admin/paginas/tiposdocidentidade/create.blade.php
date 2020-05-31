@extends('adminlte::page')

@section('title_postfix', 'Tipo Documento Identidade')

@section('content_header')
    <h1>Cadastrar Tipo Documento Identidade </h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('tiposdocidentidade.store')}}" class="form" method="POST">
            @csrf
            
            @include('admin.paginas.tiposdocidentidade._partials.form')
        </form>
    </div>
@endsection