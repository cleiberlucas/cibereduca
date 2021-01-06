@extends('adminlte::page')

@section('title_postfix', ' Opção Educacional')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" >
            <a href="{{ route('opcaoeducacional.responsavel') }} " class="">Opção Educacional</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="#" class="">Cadastrar</a>
        </li>
    </ol>
    
@stop

@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.js"></script>
    
    <div class="card">
        <form action="{{ route('opcaoeducacional.store')}}" class="form" method="POST">
            @csrf
                  
            
            <div class="container-fluid">
                @include('admin.includes.alerts')
            <div class="row">

                <div class="form-group col-sm-8 col-xs-2">            
                    <label>* Aluno:</label>
                    <select name="fk_id_matricula" id="fk_id_matricula"  class="form-control" required>
                        <option value=""></option>
                        @foreach ($alunos as $aluno)                            
                            <option value="{{$aluno->id_matricula}}">{{$aluno->aluno}} - {{$aluno->nome_turma}} - {{$aluno->ano}}</option>                            
                        @endforeach
                    </select>
                </div>        
                
            </div>

            @include('secretaria.paginas.opcaoeducacional._partials.form_resp')
        </form>
    </div>
@endsection