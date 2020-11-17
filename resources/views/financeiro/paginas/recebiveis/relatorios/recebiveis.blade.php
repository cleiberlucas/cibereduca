
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recebíveis</title>
</head>
<style>   
    .row {    
        margin-top: -0.25rem !important;
        /* margin-right: 5; */
        margin-bottom: 0;
        /* padding-right: 5; */
        padding-top: 0;
        padding-bottom: 0
    }
    p{
        font-size: 20px;
    }
    html {
        height: 96%;
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
    <font size=1.5px>
    {{-- início tabela --}}
    <table border=1 cellspacing=0 cellpadding=2 >
        {{-- cabecalho ficha de frequencia mensal --}}
        <thead class="report-header">
            <tr>
                <td colspan=9 align="center">
                    {{-- <strong>{{mb_strToUpper ($unidadeEnsino->nome_fantasia)}}</strong> --}}
                    <img src="/vendor/adminlte/dist/img/cabecalho.jpg" width="100%" height="80%" alt="logo"> 
                </td>
            </tr>
        </thead>
        <tr align="center">
            <td colspan=7><strong>RELATÓRIO DE RECEBÍVEIS</strong></td>
            <td colspan=2>
                <i>Gerado em {{date('d/m/Y H:i:s')}}</i>
            </td>
        </tr>

        @if (isset($filtroAplicado))
            <tr>
                <td colspan="9">
                    Filtro(s) aplicado(s):                    
                    <?php echo $filtroAplicado;?>
                </td>
            </tr>
            
        @endif
                
        <tr>
            <th><strong>N°</strong></th>            
            <th>Aluno / Responsável</th>
            <th>Turma</th>
            <th>Recebível</th>
            <th>Valor</th>
            <th>Vencimento</th>
            <th>Situação</th>
            <th>Pagamento</th>
            <th>Valor Pago</th>
        </tr>

        <?php $totalRecebido = 0;?>
        {{-- Lista de alunos --}} 
        @foreach ($recebiveis as $index => $recebivel)
            <tr>               
                <td>
                    {{$index+1}}
                </td>                
                <td>
                    {{$recebivel->nome_aluno}} 
                    <br>
                    {{$recebivel->nome_responsavel}}                
                </td>
                <td>
                    {{$recebivel->nome_turma}}-{{$recebivel->ano}}
                </td> 
                <td>
                    <a href="{{route('financeiro.show', $recebivel->id_recebivel)}}" target="_blanck" class="">{{$recebivel->descricao_conta}} {{$recebivel->parcela}}</a>
                </td>                
                <td align="right">{{number_format($recebivel->valor_total, 2, ',', '.')}}</td>
                <td>{{date('d/m/Y', strtotime($recebivel->data_vencimento)) ?? ''}}</td>
                <td>{{$recebivel->situacao_recebivel}}</td>
                <td>
                    @if ($recebivel->data_recebimento)
                        {{date('d/m/Y', strtotime($recebivel->data_recebimento)) ?? ''}}    
                    @endif
                </td>
                <td align="right">{{number_format($recebivel->valor_recebido, 2, ',', '.')}}</td>
            </tr>
            <?php $totalRecebido += $recebivel->valor_recebido;?>
        @endforeach        
        <tr>
            <td colspan="8" align="center"><strong>TOTAL RECEBIDO</strong></td>
            <td align="right"><strong><?php echo 'R$ '.number_format($totalRecebido, 2, ',', '.')?></strong></td>
        </tr>

    </table>{{-- fim tabela --}}
        
    <br>
</font>
    @include('secretaria.paginas._partials.rodape_redeeduca')

 {{-- 
    <footer class="footer">        
        <div class="row my-0 py-0 mr-0 ">            
            <div class="col-sm-11 col-xs-2 ml-5 my-0 py-0 text-right">
                <font size="1px">CiberEduca - Plataforma de Gestão Escolar</font>
            </div>         
        </div>
      
        <div class="row mx-0 my-0 py-0">
            <div class="col-sm-12 text-center my-0 py-0 mx-0">        
                <img src="/vendor/adminlte/dist/img/rodape.jpg" width="100%" height="90%" alt="logo">
            </div>
        </div> --}}
        {{-- <div class="row my-0">
            <div class="col-sm-12 col-xs-2 ml-5 my-0 py-0" align="center">
                <font size="1px">CiberSys - Sistemas Inteligentes</font>
            </div>            
        </div> --}}

   {{--  </footer>  --}}

</body>
</html>
