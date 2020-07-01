@extends('adminlte::page')

@section('title_postfix', ' Turma')

@section('content_header')
    <ol class="breadcrumb"> 
        <li class="breadcrumb-item active" >
            <a href="#" class="">Pedagógico</a>
        </li>       
        <li class="breadcrumb-item active" >
            <a href="{{ url('pedagogico/tiposturmas') }} " class="">Padrões de Turmas</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{ route('tiposturmas.avaliacoes', $avaliacao->fk_id_tipo_turma) }}" class="">Avaliações</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="#" class="">Alterar</a>
        </li>
    </ol>

    <h1>Editar Avaliação {{$avaliacao->tipoTurma->tipo_turma}} - {{$avaliacao->tipoTurma->subNivelEnsino->sub_nivel_ensino}}</h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('tiposturmas.avaliacoes.update', $avaliacao->id_avaliacao)}}" class="form" method="POST">
            @csrf
            @method('PUT')
            
            @include('pedagogico.paginas.tiposturmas.avaliacoes._partials.form')
        </form>
    </div>
@endsection