<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recibo</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    
</head>

<style>   
   
    html {
        height: 100%;
    }

    body {
        min-height: 100%;
        display: grid;
        grid-template-rows: 1fr auto;
    }

    .footer {
        grid-row-start: 2;
        grid-row-end: 3;
    }

</style>

<body>
    <div class="container-fluid ">        
        
        <div class="content">
            
        @for ($i = 0; $i < 2; $i++)            
            @include('secretaria.paginas._partials.cabecalho_redeeduca')            
                <?php 
                    $total_recebido = 0;
                ?>
                @foreach ($recebimento as $index => $recibo)                
                    @if ($index == 0)
                        <div class="row mt-0 pt-0 justify-content-center">
                            CNPJ: {{mascaraCpfCnpj('##.###.###/####-##',$recibo->cnpj)}}                    
                        </div>
                        <div class="row mt-0 pt-0 justify-content-center">
                            Endereço: {{$recibo->endereco}}                    
                        </div>
                        <div class="row mt-1 pt-0 justify-content-center">
                            <h4>RECIBO
                            @if (!empty($recibo->numero_recibo))
                                N° {{$recibo->numero_recibo}}
                            @endif
                            - {{mb_strToUpper($recibo->conta_receb_principal)}} Parcela: {{$recibo->parcela}}
                        </h4>
                        </div>
                        <div class="row mt-0 pt-0 text-right">
                            <div class="col-sm-12 col-xs-2 my-0" >  
                                <h5><i><strong>Código Validação:</strong> {{$recibo->codigo_validacao}}</i></h5>                    
                            </div>
                        </div>
                        <div class="row mt-0 pt-0">
                            <div class="col-sm-5 col-xs-2 my-0" >  
                                <h5><strong>Aluno:</strong> {{$recibo->nome_aluno}}</h5>                    
                            </div>
                            <div class="col-sm-5 col-xs-2 my-0" >  
                                <h5><strong>Responsável:</strong> {{$recibo->nome_resp}} </h5>                    
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-11 col-xs-2 my-0" >  
                                <h5><strong>Turma:</strong> {{$recibo->nome_turma}} - {{$recibo->ano}}</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-5 col-xs-2 my-0" >  
                                <h5><strong>Pagamento: </strong> {{date('d/m/Y', strtotime($recibo->data_recebimento))}} </h5>
                            </div>
                            <div class="col-sm-5 col-xs-2 my-0" >  
                                <h5><strong>Vencimento: </strong> {{date('d/m/Y', strtotime($recibo->data_vencimento))}} </h5>
                            </div>
                        </div>
                                        
                        {{-- Imprimindo formas de pagamento --}}                        
                        @foreach ($formasPagamento as $ind => $forma_pagto)    
                            <?php
                                $total_recebido += $forma_pagto->valor_recebido;                        
                            ?>  
                            <div class="row">
                                <div class="col-sm-5 col-xs-2 my-0" >  
                                    <h5> <strong>Forma Pagamento: </strong> {{$forma_pagto->forma_pagamento}} </h5>
                                </div>
                                <div class="col-sm-5 col-xs-2 my-0" >  
                                    <h5><strong>Valor Recebido: R$ {{number_format($forma_pagto->valor_recebido, 2, ',', '.')}} </strong></h5>
                                </div>
                            </div>
                        @endforeach
                    
                        <div class="row">
                            <div class="col-sm-11 col-xs-2 mt-0" >  
                                <h5><strong>Valor Total Recebido: R$ <?php echo number_format($total_recebido, 2, ',', '.');?> </h5>
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col-sm-11 col-xs-2 mt-1" >  
                                <h6>Detalhamento</h6>
                            </div>
                        </div> --}}
                        {{-- Imprimindo contas contábeis --}}
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm table-hover">
                                <thead>
                                    {{-- <th scope="col">#</th> --}}
                                    <th scope="col" style="text-align: center;">Detalhamento</th>                                                                   
                                    <th scope="col" style="text-align: center;">Valor (R$)</th>
                                    <th scope="col" style="text-align: center;">Desconto (R$)</th>
                                    <th scope="col" style="text-align: center;">Total (R$)</th>
                                </thead>
                                <tbody>    
                                    {{-- imprimindo conta principal --}}
                                    <tr>
                                        {{-- <th scope="row">{{$index+1}}</th> --}}
                                        <td> {{$recibo->conta_receb_principal}}</td>                                                                     
                                        <td style="text-align: right;"> {{number_format($recibo->valor_principal, 2, ',', '.')}}</td>
                                        <td style="text-align: right;"> {{number_format($recibo->valor_desconto_principal, 2, ',', '.')}}</td>
                                        <td style="text-align: right;"> {{number_format($recibo->valor_total, 2, ',', '.')}}</td>
                                    </tr>
                                    {{-- imprimindo acréscimos --}}
                                    @foreach ($acrescimos as $acrescimo)
                                        <tr>                                        
                                            <td> {{$acrescimo->descricao_conta}}</td>                                        
                                            <td style="text-align: right;"> {{number_format($acrescimo->valor_acrescimo, 2, ',', '.')}}</td>
                                            <td style="text-align: right;"> {{number_format($acrescimo->valor_desconto_acrescimo, 2, ',', '.')}}</td>
                                            <td style="text-align: right;"> {{number_format($acrescimo->valor_total_acrescimo, 2, ',', '.')}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="row justify-content-center mt-3 ">                
                            {{$recibo->name}}                
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-11" align="right">
                                <font size="1px"><i>Recebimento lançado em {{date('d/m/Y H:i:s', strtotime($recibo->data_registra_recebimento))}}</i></font>
                            </div>
                        </div>
                        
                    @endif
                    
                @endforeach
            @endfor

        </div> {{-- fim div content --}}
       
    </div>
    {{-- @include('secretaria.paginas._partials.rodape_redeeduca') --}}
    
</body>
</html>
