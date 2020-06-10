@extends('adminlte::page')

@section('title_postfix', ' Padrão de Turma')

@section('content_header')
    <ol class="breadcrumb">    
        <li class="breadcrumb-item active" >
            <a href="{{ route('tiposturmas.index') }} " class="">Padrões de Turmas</a>
        </li>
        <li class="breadcrumb-item">
            <a href="#" class="">Editar</a>
        </li>
    </ol>
    <h1>Editar Padrão de Turma</h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('tiposturmas.update', $tipoTurma->id_tipo_turma)}}" class="form" method="POST">
            @csrf
            @method('PUT')
            
            @include('admin.paginas.tiposturmas._partials.form')
        </form>
    </div>
@endsection