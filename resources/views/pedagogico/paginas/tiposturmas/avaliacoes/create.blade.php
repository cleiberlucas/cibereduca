@extends('adminlte::page')

@section('title_postfix', ' Avaliações')

@section('content_header')
    <ol class="breadcrumb"> 
        <li class="breadcrumb-item active" >
            <a href="#" class="">Pedagógico</a>
        </li>       
        <li class="breadcrumb-item active" >
            <a href="{{ url('pedagogico/tiposturmas') }} " class="">Padrões de Turmas</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{ route('tiposturmas.avaliacoes', $tipoTurma) }}" class="">Avaliações</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="#" class="">Cadastrar</a>
        </li>
    </ol>
    <h1>Cadastrar Avaliação</h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('tiposturmas.avaliacoes.store')}}" class="form" method="POST">
            @csrf            

            @include('pedagogico.paginas.tiposturmas.avaliacoes._partials.form')
        </form>
    </div>
@endsection