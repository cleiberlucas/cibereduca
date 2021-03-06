@extends('adminlte::page')

@section('title_postfix', ' Boletos')

@section('content_header')
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >            
            <a href="#" class=""> Financeiro</a>
        </li>
        <li class="breadcrumb-item active" >            
            <a href="{{ route('financeiro.index') }} " class=""> Recebíveis</a>
        </li>
        <li class="breadcrumb-item active" >            
            <a href="{{ route('boleto.indexAluno', $aluno->id_pessoa) }}" class=""> Boletos</a>            
        <li class="breadcrumb-item active" >            
            <a href="#" class=""> Lançar</a>
        </li>
    </ol>    
@stop

<style>
    .acrescimo {font-size:80%; }
</style>

@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <div class="container-fluid">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-8">
                    <h4>Lançar Boletos</h4>
                    <h6>-» Antes de lançar os boletos é necessário cadastrar os recebíveis.</h6>
                    <h6>Informações para os boletos:</h6>
                    {{-- <div class="row">                
                        <div class="col-sm-12"><strong>1-Acréscimos:</strong> Multa: {{$dadoBancario->multa}}% e Juros: {{$dadoBancario->juros}}% ao mês</div>
                    </div> --}}
                
                    <div class="row">                        
                        <div class="col-sm-12"><strong>1-Texto instruções:</strong> {{$dadoBancario->instrucao_multa_juros}}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">2-Permite pagamento em até {{$dadoBancario->dias_baixa_automatica}} dia(s) após o vencimento.</div>                
                    </div>    
                    <div class="row">
                        <div class="col-sm-12">3-Desconto de R$ XXX,XX até a data do vencimento. (se houver)</div>
                    </div>
                </div>
                <div class="col sm-4">
                    <h5>IMPORTANTE:</h5>
                    <h6><strong>Vencimento:</strong> caso o vencimento original do recebível seja em final de semana ou feriado, o vencimento do boleto será recalculado para o próximo dia útil. 
                        Este recálculo é necessário para permitir o pagamento com desconto.</h6>
                    <h6><font color="red"><strong>Registro do boleto no Banco:</strong> o boleto poderá ser pago, somente, após uma hora do envio da remessa ao Banco.</font> </h6>
                </div>
            </div>
            
        </div>
        <div class="card-header">
            <div class="row">
                <div class="col-sm my-3">                                
                    <strong>Aluno(a): {{$aluno->nome}}</strong>
                </div>                
            </div> 
        </div>
        @include('admin.includes.alerts')
         {{-- Abas --}}
         <ul class="nav nav-tabs nav-pills nav-fill justify-content-center" role="tablist">            
            <li role="presentation" class="nav-item">
                <a class="nav-link " href="#recebivel_vencer" aria-controls="recebivel_vencer" role="tab" data-toggle="tab">RECEBÍVEIS A VENCER</a>                    
            </li>                        
            <li role="presentation" class="nav-item">
                <a class="nav-link{{--  disabled --}} " href="#recebivel_vencido" aria-controls="recebivel_vencido" role="tab" data-toggle="tab" >RECEBÍVEIS VENCIDOS</a>                    
            </li>                                    
        </ul>
        <div class="tab-content">
            {{-- Aba recebível a vencer --}}                
            <div role="tabpanel" class="tab-pane active" id="recebivel_vencer">     
                <form action="{{ route('boleto.store')}}" class="form" name="form" method="POST">
                    @csrf 
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <th scope="col">#</th>
                                <th scope="col">Recebível</th>                        
                                <th scope="col">Parcela</th>                        
                                <th scope="col">Valor R$</th>
                                <th scope="col">Desconto R$</th>
                                <th scope="col">Vencimento</th>
                                <th scope="col">Ações</th>                    
                            </thead>
                            <tbody>     
                                @foreach ($recebiveis as $index => $recebivel)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="fk_id_recebivel[]" value="{{$recebivel->id_recebivel}}" checked >
                                        </td>
                                        <td>{{$recebivel->descricao_conta}} - {{$recebivel->tipo_turma}} - {{$recebivel->ano}}</td>
                                        <td>{{$recebivel->parcela}}</td>
                                        <td>{{number_format($recebivel->valor_principal, 2, ',', '.')}}</td>
                                        <td>{{number_format($recebivel->valor_desconto_principal, 2, ',', '.')}}</td>
                                        <td>{{date('d/m/Y', strtotime($recebivel->data_vencimento))}}</td>
                                        <td></td>
                                    </tr>                            
                                @endforeach
                            </tbody>
                        </table>
                    </div>                
                    <div class="card-footer">
                        @if (isset($filtros))
                            {!! $recebiveis->appends($filtros)->links()!!}
                        @else
                            {!! $recebiveis->links()!!}     
                        @endif                    
                    </div>
        
                    <div class="row">
                        <div class="col-sm-6">
                            Forma de lançamento:
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="forma_geracao" id="boletos_vencimento" value="boletos_vencimento" required checked>
                            <label for="boletos_vencimento" class="form-check-label">Boletos mensais agrupados: curso e material didático, com mesmo vencimento, em 1 boleto.</label>
                        </div>
                    </div>
                    <br>
                    {{-- <div class="row">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="forma_geracao" id="boleto_unico" value="boleto_unico" required>
                            <label for="boleto_unico" class="form-check-label">Boleto único - todos recebíveis selecionados</label>
                        </div>
                    </div> --}}
        
                    <div class="row ">
                        <div class="form-group col-sm-4 col-xs-2">                         
                            <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Lançar</button>            
                        </div>
                    </div>
                </form>
            </div>
        
            {{-- Aba recebíves VENCIDOS--}}                
            <div role="tabpanel" class="tab-pane {{-- active --}}" id="recebivel_vencido" >     
                <form action="{{ route('boleto.store')}}" class="form" name="form" method="POST">
                    @csrf 
                    
                    @foreach ($correcoes as $index => $correcao)
                        <input type="hidden" class="" id="fk_id_conta_contabil_acrescimo[{{$index}}]" name="fk_id_conta_contabil_acrescimo[{{$index}}]" value="{{$correcao->fk_id_conta_contabil}}">  
                        <input type="hidden" class="" id="indice_correcao[{{$index}}]" name="indice_correcao[{{$index}}]" value="{{$correcao->indice_correcao}}">  
                        <input type="hidden" class="" id="aplica_correcao[{{$index}}]" name="aplica_correcao[{{$index}}]" value="{{$correcao->aplica_correcao}}">  
                    @endforeach

                    <div class="row">
                        <div class="col-sm-6"></div>
                        <div class="col-sm-3" class="form-group">
                            <label for="">Novo Vencimento</label>
                            <td><input type="date" class="form-control" id="novo_vencimento" min="{{date('Y-m-d')}}" name="novo_vencimento" value='<?php echo date("Y-m-d"); ?>'' required onBlur="desmarcarTodosChecks();"/></td>
                        </div>
                    </div>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>                                
                                <th scope="col">Recebível</th>                                                                              
                                <th scope="col">Vencimento</th>
                                <th scope="col">Valor R$</th>
                                <th scope="col">Desconto R$</th>
                                <th scope="col">Principal R$</th>                                
                            </thead>
                            <tbody>     
                                @foreach ($recebiveisVencidos as $indRecVencido => $recebivelVencido)                                    
                                    <tr bgcolor="#F8E0E0">
                                        <td>
                                            <input type="checkbox"  name="fk_id_recebivel[{{$indRecVencido}}]" id="fk_id_recebivel[{{$indRecVencido}}]" value="{{$recebivelVencido->id_recebivel}}" onClick="calcularAcrescimos({{$indRecVencido}}); somarRecebiveis({{$indRecVencido}});">
                                            <label class="form-check-label" for="fk_id_recebivel[{{$indRecVencido}}]">
                                                {{$recebivelVencido->descricao_conta}} - {{$recebivelVencido->tipo_turma}} - {{$recebivelVencido->ano}} - Parc. {{$recebivelVencido->parcela}}</td>
                                            </label>
                                        <td><input type="date" class="form-control" readonly id="data_vencimento[{{$indRecVencido}}]" name="data_vencimento[{{$indRecVencido}}]" value="{{$recebivelVencido->data_vencimento}}"/></td>                                                                                
                                        <td><input type="number" step="0.010" class="form-control" required readonly id="valor_principal[{{$indRecVencido}}]" name="valor_principal[{{$indRecVencido}}]" value="{{ $recebivelVencido->valor_principal ?? old('valor_principal') }}"/></td>
                                        <td><input type="number" step="0.010" class="form-control" name="valor_desconto_principal[{{$indRecVencido}}]" id="valor_desconto_principal[{{$indRecVencido}}]" value="{{$recebivelVencido->valor_desconto_principal ?? old('valor_desconto_principal')}}" onBlur="recalcularValor('valor_principal[{{$indRecVencido}}]', 'valor_desconto_principal[{{$indRecVencido}}]', 'valor_desconto_principal[{{$indRecVencido}}]', 'valor_total[{{$indRecVencido}}]'); calcularAcrescimos({{$indRecVencido}}); ; somarRecebiveis({{$indRecVencido}});" /></td>
                                        <td><input type="number" step="0.010" class="form-control" required readonly id="valor_total[{{$indRecVencido}}]" name="valor_total[{{$indRecVencido}}]" value="{{ $recebivelVencido->valor_total ?? old('valor_total') }}"/></td>                                        
                                    </tr>          
                                    <tr bgcolor="#F8E0E0">
                                        <td colspan=7>                                            
                                            <div class="row acrescimo " id="multa{{$indRecVencido}}"></div>
                                            <div class="row acrescimo" id="juro{{$indRecVencido}}"></div>
                                            <div class="row acrescimo" id="total_boleto{{$indRecVencido}}"></div>                                        
                                        </td>    
                                    </tr>                  
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer">
                        @if (isset($filtros))
                            {!! $recebiveisVencidos->appends($filtros)->links()!!}
                        @else
                            {!! $recebiveisVencidos->links()!!}     
                        @endif                    
                    </div>
        
                    <div class="row">
                        <div class="col-sm-6">
                            Forma de lançamento:
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="forma_geracao" id="recebivel_vencido" value="recebivel_vencido" required checked>
                            <label for="recebivel_vencido" class="form-check-label">Boleto com todos os recebíveis selecionados</label>
                        </div>
                    </div>
                    <br>
                    <div class="row ">
                        <div class="form-group col-sm-4 col-xs-2">                         
                            <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Lançar</button>            
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <?php $versao_rand = rand();?>

    <script type="text/javascript" src="/js/camposAcrescimosBoleto.js?v=<?php echo urlencode(base64_decode((str_shuffle('cibereduca'))))?>&<?php echo $versao_rand ?>"></script>
    <script type="text/javascript" src="/js/utils.js?v=<?php echo urlencode(base64_decode((str_shuffle('cibereduca'))))?>&<?php echo $versao_rand ?>"></script>

    <script>
        $(document).ready(function(){
              $(".alert").slideDown(300).delay(5000).slideUp(300);
        });          
    </script>
@stop