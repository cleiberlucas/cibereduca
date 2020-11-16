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
                    <label class="col-form-label-sm py-0 my-0 " for="">Ano Letivo</label>
                    <select class="form-control form-control-sm" name="ano_letivo" id="ano_letivo" >
                        <option value=""></option>                        
                        @foreach ($anosLetivos as $anoLetivo)
                            <option value="{{$anoLetivo->id_ano_letivo}}">{{$anoLetivo->ano}}</option>                            
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-3 col-xs-1">
                    <label class="col-form-label-sm py-0 my-0" for="">Tipo Recebível</label>
                    <select class="form-control form-control-sm" name="tipo_recebivel" id="tipo_recebivel" >
                        <option value=""></option>                        
                        @foreach ($tiposRecebivel as $tipoRecebivel)
                            <option value="{{$tipoRecebivel->id_conta_contabil}}">{{$tipoRecebivel->descricao_conta}}</option>                            
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-3 col-xs-1">
                    <label class="col-form-label-sm py-0 my-0" for="">Situação Recebível</label>
                    <select class="form-control form-control-sm" name="situacao_recebivel" id="situacao_recebivel" >
                        <option value=""></option>                        
                        @foreach ($situacoesRecebivel as $sitRecebivel)
                            <option value="{{$sitRecebivel->id_situacao_recebivel}}">{{$sitRecebivel->situacao_recebivel}}</option>                            
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-sm-2 col-xs-1">
                    <label class="col-form-label-sm py-0 my-0" for="">Cód. Validação</label>
                    <input type="text" name="codigo_validacao" class="form-control form-control-sm" maxlength="7"  value="">
                </div>

                <div class="form-group col-sm-2 col-xs-1">
                    <label class="col-form-label-sm py-0 my-0" for="">Nº Recibo</label>
                    <input type="text" name="numero_recibo" class="form-control form-control-sm" maxlength="10"  value="">
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-5 col-xs-1 my-0">
                    <label class="col-form-label-sm py-0 my-0" for="">Nome Aluno(a)</label>
                    <input type="text" name="nome_aluno" minlength="3" maxlength="100" class="form-control form-control-sm"   value="">
                </div>
                <div class="form-group col-sm-5 col-xs-1">
                    <label class="col-form-label-sm py-0 my-0" for="">Nome Responsável</label>
                    <input type="text" name="nome_responsavel" minlength="3" maxlength="100" class="form-control form-control-sm"   value="">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-3 col-xs-2">            
                    <label class="col-form-label-sm py-0 my-0" >Vencimento de</label>
                    <input type="date" name="data_vencimento_inicio" class="form-control form-control-sm"   value="">
                </div> 
                <div class="form-group col-sm-3 col-xs-2">            
                    <label class="col-form-label-sm py-0 my-0" >Até</label>
                    <input type="date" name="data_vencimento_fim" class="form-control form-control-sm"   value="">
                </div> 
            </div>

            <div class="row">
                <div class="form-group col-sm-3 col-xs-2">            
                    <label class="col-form-label-sm py-0 my-0"  >Pagamento de</label>
                    <input type="date" name="data_recebimento_inicio" class="form-control form-control-sm"   value="">
                </div> 
                <div class="form-group col-sm-3 col-xs-2">            
                    <label class="col-form-label-sm py-0 my-0" >Até</label>
                    <input type="date" name="data_recebimento_fim" class="form-control form-control-sm"   value="">
                </div> 
            </div>

            <div class="row">
                <div class="form-group col-sm-3 col-xs-1">
                    <label class="col-form-label-sm py-0 my-0" for="">Forma Pagamento</label>
                    <select class="form-control form-control-sm" name="forma_pagamento" id="forma_pagamento" >
                        <option value="-1"></option>                        
                        @foreach ($formasPagamento as $formaPagto)
                            <option value="{{$formaPagto->id_forma_pagamento}}">{{$formaPagto->forma_pagamento}}</option>                            
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-sm-4 col-xs-1">
                    <label class="col-form-label-sm py-0 my-0" for="">Recebido por</label>
                    <select class="form-control form-control-sm" name="recebido_por" id="recebido_por" >
                        <option value="-1"></option>                                                
                        @foreach ($usuarios as $usuario)
                            <option value="{{$usuario->id}}">{{$usuario->name}}</option>                            
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-3 col-xs-2">
                    <label class="col-form-label-sm py-0 my-0" for="">*Ordenação</label>
                    <select name="ordem" id="ordem" class="form-control form-control-sm" required> 
                        <option value=""></option>
                        <option value="aluno.nome">Nome Aluno</option>
                        <option value="resp.nome">Nome Responsável</option>
                        <option value="data_vencimento">Data Vencimento</option>                    
                        <option value="data_recebimento">Data Pagamento</option>                    
                        <option value="situacao_recebivel">Situação</option>
                        <option value="name">Recebido por</option>
                    </select>
                </div> 
            </div>

            <hr>
            <div class="row">
                <h5>Tipos de relatórios</h5>            
            </div>
            
            <div class="row">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="tipo_relatorio" id="listagem" value="listagem" required selected>
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
