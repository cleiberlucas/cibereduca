@extends('adminlte::page')

@section('title_postfix', ' Contratos Atividades ExtraCurriculares')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" >
            <a href="{{ route('matriculas.pasta', $contratoExtraCurricular->fk_id_aluno) }} " class="">Arquivo do Aluno</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="#" class="">Contratos Atividades ExtraCurriculares</a>
        </li>
    </ol>
    <h3>Contratar Atividade ExtraCurricular</h3>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('contratos.extracurricular.update', $contratoExtraCurricular->id_contrato_atividade_extracurricular)}}" class="form" method="POST">
            <input type="hidden" name="fk_id_aluno" value="{{$contratoExtraCurricular->fk_id_aluno}}">

            @include('secretaria.paginas.contratos_extracurriculares._partials.form')
        </form>
    </div>
@endsection