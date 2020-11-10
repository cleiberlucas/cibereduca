@extends('adminlte::page')

@section('title_postfix', ' Atividades ExtraCurriculares')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" >
            <a href="{{ route('extracurriculares.index') }} " class="">Atividades ExtraCurriculares</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="#" class="">Cadastrar</a>
        </li>
    </ol>
    <h3>Cadastrar Atividade ExtraCurricular</h3>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('extracurriculares.store')}}" class="form" method="POST">
            @csrf
            
            @include('secretaria.paginas.extracurriculares._partials.form')
        </form>
    </div>
@endsection