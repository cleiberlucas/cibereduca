@extends('adminlte::page')

@section('title_postfix', ' Retornos')

@section('content_header')
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >            
            <a href="#" class=""> Financeiro</a>
        </li>
        <li class="breadcrumb-item active" >            
            <a href="{{ route('retorno.index') }} " class=""> Retornos</a>
        </li>
        <li class="breadcrumb-item active" >  
            <a href="#" class=""> Processar</a>
        </li>
    </ol>    
@stop

@section('content')
    <div class="container-fluid">
        @include('admin.includes.alerts')
        <div class="card-header">
            <h4>Processar retornos de boletos</h4>            
        </div>

        <form action="{{ route('retorno.store')}}" class="form" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" class="" id="fk_id_unidade_ensino" name="fk_id_unidade_ensino" value="{{$unidadeEnsino}}">
            <input type="hidden" class="" id="fk_id_usuario_retorno" name="fk_id_usuario_retorno" value="{{Auth::id()}}">
            <div class="form-group col-sm-4 col-xs-12">
                <label>Arquivo retorno:</label>
                <input type="file" name="nome_arquivo" class="form-control" required>
                <small>Baixe o arquivo no site do Banco e insira aqui.</small>
            </div>
            <div class="row">
                <div class="form-group col-sm-4 col-xs-6">     
                    <div>
                  
                        <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Processar</button>
                    </div>
                </div>
            </div>

        </form>
    </div>

    <script>
        $(document).ready(function(){
              $(".alert").slideDown(300).delay(5000).slideUp(300);
        });    
    </script>
    
@stop
