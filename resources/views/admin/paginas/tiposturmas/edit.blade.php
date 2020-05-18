@extends('adminlte::page')

@section('title_postfix', ' Padrão de Turma')

@section('content_header')
    <h1>Editar Padrão de Turma</h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('tiposturmas.update', $tipoturma->id_tipo_turma)}}" class="form" method="POST">
            @csrf
            @method('PUT')
            
            @include('admin.paginas.tiposturmas._partials.form')
        </form>
    </div>
@endsection