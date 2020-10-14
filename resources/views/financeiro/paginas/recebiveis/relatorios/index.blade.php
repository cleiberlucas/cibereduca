@extends('adminlte::page')

@section('title_postfix', ' Recebíveis')

@section('content_header')
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >            
            <a href="#" class=""> Financeiro</a>
        </li>
        <li class="breadcrumb-item active" >            
            <a href="{{ route('recebiveis.relatorios.index') }} " class=""> Relatórios Recebíveis</a>
        </li>
    </ol>    
@stop

@section('content')
    <div class="container-fluid">
        @include('admin.includes.alerts')
        <form action="{{ route('relatorios.recebiveis')}}" id="diario" name="diario" class="form" method="POST">
            @csrf            
            <div class="row">
                <div class="form-group col-sm-2 col-xs-1">
                    <label for="">Ano Letivo</label>
                    <select class="form-control" name="anoLetivo" id="anoLetivo" required>
                        <option value="0">Selecione</option>
                        @foreach ($anosLetivos as $anoLetivo)
                            <option value="{{$anoLetivo->id_ano_letivo}}">{{$anoLetivo->ano}}</option>                            
                        @endforeach
                    </select>
                </div>
            
                <div class="form-group col-sm-3 col-xs-1">
                    <label for="">Situação Recebível</label>
                    <select class="form-control" name="anoLetivo" id="anoLetivo" >
                        <option value="-1">Selecione</option>
                        <option value="0">Todos</option>
                        @foreach ($situacoesRecebivel as $sitRecebivel)
                            <option value="{{$sitRecebivel->id_situacao_recebivel}}">{{$sitRecebivel->situacao_recebivel}}</option>                            
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-5 col-xs-1">
                    <label for="">Nome do aluno</label>
                    <input type="text" name="nome_aluno" class="form-control"   value="">
                </div>
                <div class="form-group col-sm-5 col-xs-1">
                    <label for="">Nome do responsável</label>
                    <input type="text" name="nome_aluno" class="form-control"   value="">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-3 col-xs-2">            
                    <label>Vencimento de</label>
                    <input type="date" name="data_vencimento_inicio" class="form-control"   value="">
                </div> 
                <div class="form-group col-sm-3 col-xs-2">            
                    <label>Até</label>
                    <input type="date" name="data_vencimento_fim" class="form-control"   value="">
                </div> 
            </div>

            <div class="row">
                <div class="form-group col-sm-3 col-xs-2">            
                    <label>Pagamento de</label>
                    <input type="date" name="data_pagamento_inicio" class="form-control"   value="">
                </div> 
                <div class="form-group col-sm-3 col-xs-2">            
                    <label>Até</label>
                    <input type="date" name="data_pagamento_fim" class="form-control"   value="">
                </div> 
            </div>

            <div class="row">
                <div class="form-group col-sm-3 col-xs-1">
                    <label for="">Recebido por</label>
                    <select class="form-control" name="recebido_por" id="recebido_por" >
                        <option value="0">Selecione</option>                        
                        @foreach ($situacoesRecebivel as $sitRecebivel)
                            <option value="{{$sitRecebivel->id_situacao_recebivel}}">{{$sitRecebivel->situacao_recebivel}}</option>                            
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-3 col-xs-2">
                    Ordenação
                    <select name="ordem" id="ordem" class="form-control" required > 
                        <option value=""></option>
                        <option value="nome_aluno">Nome</option>
                        <option value="data_vencimento">Data Vencimento</option>                    
                        <option value="data_pagamento">Data Pagamento</option>                    
                        <option value="nome_turma">Turma</option>
                        <option value="name">Cadastrado por</option>
                    </select>
                </div> 
            </div>

            <hr>
            <div class="row">
                <h5>Tipos de relatórios</h5>            
            </div>
            
            <div class="row">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="tipo_relatorio" id="listagem" value="listagem" required>
                    <label for="listagem" class="form-check-label">Listagem</label>
                </div>
            </div>
            
            <hr>
            <div class="row">
                <button type="submit" class="btn btn-primary"><i class="fas fa-print"></i> Imprimir</button>
            </div>
        </form>

    </div>

    <script>
        $(document).ready(function(){
              $(".alert").slideDown(300).delay(5000).slideUp(300);
        });    
    </script>
    
@stop
