@extends('adminlte::page')

@section('title_postfix', ' Atendimento Especializado')

@section('content_header')
    <h1>Cadastrar Atendimento Especializado </h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('atendimentosespecializados.store')}}" class="form" method="POST">
            @csrf
            
            @include('admin.paginas.atendimentosespecializados._partials.form')
        </form>
    </div>
@endsection