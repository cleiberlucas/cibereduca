@extends('adminlte::page')

@section('title_postfix', ' Atendimento Especializado')

@section('content_header')
    <h1>Editar Atendimento Especializado </h1>
    
@stop

@section('content')
    <div class="card">
        <form action="{{ route('atendimentosespecializados.update', $atendimentoEspecializado->id_atendimento_especializado)}}" class="form" method="POST">
            @csrf
            @method('PUT')
            
            @include('admin.paginas.atendimentosespecializados._partials.form')
        </form>
    </div>
@endsection