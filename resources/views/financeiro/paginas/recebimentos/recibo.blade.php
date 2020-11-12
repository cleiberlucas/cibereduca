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
        height: 95%;
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
            
        @for ($i = 0; $i < 3; $i++)            
            @include('secretaria.paginas._partials.cabecalho_redeeduca')            
                <?php 
                    $total_recebido = 0;
                ?>
                @foreach ($recebimento as $index => $recibo)                
                    @if ($index == 0)
                        <div class="row my-0 py-0 justify-content-center">
                            CNPJ: {{mascaraCpfCnpj('##.###.###/####-##',$recibo->cnpj)}}                    
                        </div>
                        <div class="row my-0 py-0 justify-content-center">
                            Endereço: {{$recibo->endereco}}                    
                        </div>
                        <div class="row my-0 py-0 justify-content-center">
                            <h5>RECIBO
                            @if (!empty($recibo->numero_recibo))
                                N° {{$recibo->numero_recibo}}
                            @endif
                            - {{mb_strToUpper($recibo->conta_receb_principal)}} Parcela: {{$recibo->parcela}}
                        
                            &nbsp;&nbsp;&nbsp;&nbsp;<i>Código Validação: {{$recibo->codigo_validacao}}</i></h5>                                                
                        </div>
                        <div class="row my-0 py-0">
                            <div class="col-sm-12 col-xs-2 my-0 py-0" >  
                                <h6><strong>Aluno:</strong> {{$recibo->nome_aluno}} 
                                    &nbsp;&nbsp;<strong>Resp.:</strong> {{$recibo->nome_resp}}
                                    &nbsp;&nbsp;<strong>Turma:</strong> {{$recibo->nome_turma}} - {{$recibo->ano}}</h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-xs-2 my-0 py-0" >  
                                <h6><strong>Pagamento: </strong> {{date('d/m/Y', strtotime($recibo->data_recebimento))}}                            
                                    &nbsp;&nbsp;<strong>Vencimento: </strong> {{date('d/m/Y', strtotime($recibo->data_vencimento))}} 
                                    @foreach ($formasPagamento as $forma_pagto)    
                                        <?php
                                            $total_recebido += $forma_pagto->valor_recebido;                        
                                        ?>
                                        @endforeach
                                    &nbsp;&nbsp;<strong>Valor Total Recebido: R$ <?php echo number_format($total_recebido, 2, ',', '.');?> </strong>
                                </h6>
                            </div>
                        </div>
                                        
                        {{-- Imprimindo formas de pagamento --}}                        
                        @foreach ($formasPagamento as $ind => $forma_pagto)    
                            <?php
                                $total_recebido += $forma_pagto->valor_recebido;                        
                            ?>  
                            <div class="row">
                                <div class="col-sm-5 col-xs-2 my-0 py-0" >  
                                    <h6> <strong>Forma Pagamento: </strong> {{$forma_pagto->forma_pagamento}} </h6>
                                </div>
                                <div class="col-sm-5 col-xs-2 my-0 py-0" >  
                                    <h6><strong>Valor Recebido: R$ {{number_format($forma_pagto->valor_recebido, 2, ',', '.')}} </strong></h6>
                                </div>
                            </div>
                        @endforeach
                    
                        {{-- <div class="row">
                            <div class="col-sm-11 col-xs-2 mt-1" >  
                                <h6>Detalhamento</h6>
                            </div>
                        </div> --}}
                        {{-- Imprimindo contas contábeis --}}
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    {{-- <th scope="col">#</th> --}}
                                    <th scope="col" style="text-align: center;">Principal</th>   
                                    @if (count($acrescimos) > 0)                                                                                                                       
                                        <th scope="col" style="text-align: center;">Multa</th>
                                        <th scope="col" style="text-align: center;">Juros</th>
                                    @endif                     
                                    {{-- <th scope="col" style="text-align: center;">Total (R$)</th> --}}
                                </thead>
                                 
                                    {{-- imprimindo conta principal --}}
                                    <tr>
                                        {{-- <th scope="row">{{$index+1}}</th> --}}
                                        <td> {{$recibo->conta_receb_principal}}:                                                         
                                        R$ {{number_format($recibo->valor_principal, 2, ',', '.')}}
                                        Desc: R$ {{number_format($recibo->valor_desconto_principal, 2, ',', '.')}}
                                        Total: R$ {{number_format($recibo->valor_total, 2, ',', '.')}}</td>
                                    
                                    {{-- imprimindo acréscimos --}}
                                    
                                    @foreach ($acrescimos as $acrescimo)                                        
                                            <td> {{-- {{$acrescimo->descricao_conta}}                                         --}}
                                             Valor: R$ {{number_format($acrescimo->valor_acrescimo, 2, ',', '.')}}
                                             Desc: R$ {{number_format($acrescimo->valor_desconto_acrescimo, 2, ',', '.')}}
                                             Total: R$ {{number_format($acrescimo->valor_total_acrescimo, 2, ',', '.')}}</td>
                                        
                                    @endforeach
                                </tr>
                                
                            </table>
                        </div>
                        
                        <div class="row justify-content-center mt-3 ">                
                            <div class="col-sm-6" align="right">
                                {{$recibo->name}}                
                            </div>
                            <div class="col-sm-6" align="right">
                                <font size="1px"><i>Recebto lançado em {{date('d/m/Y H:i:s', strtotime($recibo->data_registra_recebimento))}} &nbsp;&nbsp;- &nbsp;&nbsp;CiberEduca - Plataforma de Gestão Escolar</i></font>
                                
                            </div>
                        </div>
                        <br>
                        
                    @endif
                    
                @endforeach
            @endfor

        </div> {{-- fim div content --}}
       
    </div>
    {{-- @include('secretaria.paginas._partials.rodape_redeeduca') --}}
    
</body>
</html>
