@extends('adminlte::page')

@section('title_postfix', ' Padrão de Turma')

@section('content_header')
    <h1>Cadastrar Padrão de Turma</h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('tiposturmas.store')}}" class="form" method="POST">
            @csrf
            
            @include('admin.paginas.tiposturmas._partials.form')
        </form>
    </div>
@endsection