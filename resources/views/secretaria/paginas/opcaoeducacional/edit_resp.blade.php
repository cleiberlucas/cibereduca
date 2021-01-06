@extends('adminlte::page')

@section('title_postfix', ' Opção Educacional')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" >
            <a href="{{ route('opcaoeducacional.responsavel') }} " class="">Opção Educacional</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="#" class="">Editar</a>
        </li>
    </ol>
    
@stop

@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.js"></script>
    
    <div class="card">
        <form action="{{ route('opcaoeducacional.update', $opcaoEducacional->id_opcao_educacional)}}" class="form" method="POST">
            <input type="hidden" name="fk_id_matricula" value={{$opcaoEducacional->fk_id_matricula}}>
            @csrf
            @method('PUT')

            <div class="container-fluid">
                @include('admin.includes.alerts')
            <div class="row">
                <div class="form-group col-sm-12 col-xs-2">
                    <h4><strong>Aluno:</strong> {{$opcaoEducacional->aluno}}</h4>
                    <h4><strong>Turma: </strong> {{$opcaoEducacional->nome_turma}} - {{$opcaoEducacional->ano}}</h4>
                </div>
            </div>
            
            @include('secretaria.paginas.opcaoeducacional._partials.form_resp')
        </form>
    </div>
@endsection