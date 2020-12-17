@extends('adminlte::page')

@section('title_postfix', ' Tipo de Avaliação')

@section('content_header')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <ol class="breadcrumb">    
        <li class="breadcrumb-item active" >
            <a href="#" class="">Pedagógico</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('recuperacaofinal.index') }}" class="">Recuperação Final</a>
        </li>
        <li class="breadcrumb-item">
            <a href="#" class="">Alterar</a>
        </li>
    </ol>
    <h4>Alterar Recuperação Final</h4>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('recuperacaofinal.update', $recuperacaoFinal->id_recuperacao_final)}}" class="form" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" class="" id="fk_id_user" name="fk_id_user" value="{{Auth::id()}}">
                        
            <div class="container-fluid">

                @include('admin.includes.alerts')
                @csrf
                <h6>Ano Letivo: {{$recuperacaoFinal->ano}}</h6>
                <h6>Turma: {{$recuperacaoFinal->nome_turma}}</h6>
                <h6>Aluno: {{$recuperacaoFinal->nome}}</h6>
                <h6>Disciplina: {{$recuperacaoFinal->disciplina}}</h6>
                
                <div class="row">
                    <div class="form-group col-sm-2 col-xs-2">
                        * Nota
                        <input type="number" name="nota"  step="0.010" min=0 max=10 class="form-control" value="{{$recuperacaoFinal->nota ?? old('nota')}}">
                    </div>
                    <div class="form-group col-sm-4 col-xs-2">
                        Data aplicação
                        <input type="date" name="data_aplicacao" class="form-control" value="{{$recuperacaoFinal->data_aplicacao ?? old('data_aplicacao')}}">
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

@endsection
