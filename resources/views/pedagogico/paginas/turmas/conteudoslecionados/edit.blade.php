@extends('adminlte::page')

@section('title_postfix', ' Turma')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" >
            <a href="{{ route('turmas.index') }} " class="">Turmas</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="#" class="">Editar Turma</a>
        </li>
    </ol>

    <h1>Editar Turma</h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('turmas.update', $turma->id_turma)}}" class="form" method="POST">
            @csrf
            @method('PUT')
            
            @include('secretaria.paginas.turmas._partials.form')
        </form>
    </div>
@endsection