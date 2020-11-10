@extends('adminlte::page')

@section('title_postfix', ' Atividades ExtraCurriculares')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" >
            <a href="{{ route('extracurriculares.index') }} " class="">Atividades ExtraCurriculares</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="#" class="">Editar</a>
        </li>
    </ol>
    <h3>Editar Atividade ExtraCurricular</h3>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('extracurriculares.update', $extraCurricular->id_tipo_atividade_extracurricular)}}" class="form" method="POST">
            @csrf
            @method('PUT')
            
            @include('secretaria.paginas.extracurriculares._partials.form')

            <input type="hidden" name="fk_id_ano_letivo" value="{{$extraCurricular->fk_id_ano_letivo}}">
        </form>
    </div>
@endsection