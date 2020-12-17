@extends('adminlte::page')

@section('title_postfix', ' Tipo de Avaliação')

@section('content_header')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <ol class="breadcrumb">    
        <li class="breadcrumb-item active" >
            <a href="{{ route('recuperacaofinal.index') }} " class="">Pedagógico</a>
        </li>
        <li class="breadcrumb-item">
            <a href="#" class="">Recuperação Final</a>
        </li>
    </ol>
    <h1>Cadastrar Recuperação Final</h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('recuperacaofinal.store')}}" class="form" method="POST">
            @csrf
                        
            <div class="container-fluid">

                @include('admin.includes.alerts')
                @csrf

                <div class="row">        
                    <div class="form-group col-sm-2 col-xs-2">
                        * Ano Letivo
                        <select class="form-control" name="anoLetivo" id="anoLetivo" required>
                            <option value="0">Selecione</option>
                            @foreach ($anosLetivos as $anoLetivo)
                                <option value="{{$anoLetivo->id_ano_letivo}}">{{$anoLetivo->ano}}</option>
                                
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-sm-5 col-xs-2">
                        * Turma
                        <select name="turma" id="turma" class="form-control"  required > 
                            <option value=""></option>
                        </select>
                    </div>                
                </div>                
                
                <div class="row">
                    <div class="form-group col-sm-4 col-xs-2">
                       *  Aluno
                        <select name="fk_id_matricula" id="fk_id_matricula" class="form-control"> 
                            <option value=""></option>
                        </select>
                    </div>

                    <div class="form-group col-sm-4 col-xs-2">
                        * Disciplina
                        <select name="disciplina" id="disciplina" class="form-control">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-2 col-xs-2">
                        * Nota
                        <input type="number" name="nota"  step="0.010" min=0 max=10 class="form-control">
                    </div>
                    <div class="form-group col-sm-4 col-xs-2">
                        Data aplicação
                        <input type="date" name="data_aplicacao" class="form-control">
                    </div>
                </div>
                
                <div class="row">
                    <div class="form-group col-sm-4 col-xs-2">            
                        Todos os Campos Obrigatórios<br>
                        <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Enviar</button>            
                    </div>
                </div>
            </div>

        </form>
    </div>

    <script type="text/javascript" src="{!!asset('/js/populaTurmas.js')!!}"></script>
    <script type="text/javascript" src="{!!asset('/js/populaAlunosTurma.js')!!}"></script>
    <script type="text/javascript" src="{!!asset('/js/populaDisciplinas.js')!!}"></script>

@endsection
