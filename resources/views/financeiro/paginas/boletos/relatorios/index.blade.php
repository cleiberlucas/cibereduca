@extends('adminlte::page')

@section('title_postfix', ' Boletos')

@section('content_header')
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >            
            <a href="#" class=""> Financeiro</a>
        </li>
        <li class="breadcrumb-item active" >            
            <a href="# " class=""> Relatórios Boletos</a>
        </li>
    </ol>    
@stop

@section('content')
    <div class="container-fluid">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-8">
                    <h4>Impressão de Boletos por Turma</h4>
                </div>
            </div>
        </div>
        @include('admin.includes.alerts')
        <form action="{{ route('boletos.turma.imprimir')}}" id="diario" name="diario" class="form" method="POST">
            @csrf            
            <div class="row">
                <div class="form-group col-sm-2 col-xs-1">
                    <label class="col-form-label-sm py-0 my-0 " for="">Ano Letivo</label>
                    <select class="form-control form-control-sm" name="anoLetivo" id="anoLetivo" required>
                        <option value=""></option>                        
                        @foreach ($anosLetivos as $anoLetivo)
                            <option value="{{$anoLetivo->id_ano_letivo}}">{{$anoLetivo->ano}}</option>                            
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-5 col-xs-2">
                    <label class="col-form-label-sm py-0 my-0 " for="">Turma</label>
                    <select name="turma" id="turma" class="form-control form-control-sm" required> 
                        <option value=""></option>
                    </select>
                </div> 
            </div>
            <hr>
            <div class="row">
                <h5>Tipos de relatórios</h5>            
            </div>
            
            <div class="row">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="tipo_relatorio" id="imprimir_boletos" value="imprimir_boletos" required checked>
                    <label for="listagem" class="form-check-label">Imprimir Boletos</label>
                </div>
            </div>
            
            <hr>
            * Somente boletos não pagos e a vencer
            <br>
            <div class="row">
                <button type="submit" class="btn btn-primary"><i class="fas fa-print"></i> Imprimir</button>
            </div>
        </form>
    </div>

    <?php $versao_rand = rand();?>

    <script type="text/javascript" src="/js/populaTurmas.js?v=<?php echo urlencode(base64_decode((str_shuffle('cibereduca'))))?>&<?=$versao_rand?>"></script>
    
    <script>
        $(document).ready(function(){
              $(".alert").slideDown(300).delay(5000).slideUp(300);
        });    
    </script>

@stop