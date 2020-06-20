@extends('adminlte::page')

<section></section>

@section('title_postfix', ' Frequências')

@section('content_header')

    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >
            <a href="#" class="">Pedagógico</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{url('pedagogico/turmas')}} " class="">Diários</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="#" class="">Frequências</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="#" class="">Alterar</a>
        </li>
    </ol>
    @foreach ($turmaPeriodosLetivos as $index => $turma)
        @if ($index == 0)
            <h2>Frequências - {{$turma->nome_turma}} {{$turma->sub_nivel_ensino}} - {{$turma->descricao_turno}} </h2>                
        @endif
    @endforeach
@stop

@section('content')