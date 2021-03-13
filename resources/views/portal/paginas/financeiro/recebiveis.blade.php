<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Portal do Aluno - Recebíveis</title>

</head>
<body>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">    
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    @include('portal.paginas._partials.cabecalho')    
    <div class="container">
        <div class="card border-success">
            <div class="card-footer bg-transparent border-success"> 
                <div class="col-sm-12 col-xs-2" align="center">
                    <h4>Recebíveis do(a) Aluno(a)</h4>
                    <h5>{{$aluno->nome}}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        @include('admin.includes.alerts')     
        <div class="table table-responsive">
                <table class="table table-hover">
                    <thead>
                        <th scope="col">#</th>
                        <th scope="col">Recebível</th>                        
                        <th scope="col">Parcela</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Vencimento</th>                                      
                        <th scope="col">Pagamento</th>                                      
                        <th scope="col">Valor Pago</th>                                      
                        {{-- <th scope="col">Ver</th> --}}
                    </thead>
                    <tbody>                        
                        @foreach ($recebiveis as $index => $recebivel)       
                        
                        <?php
                        $hoje = date('Y-m-d');
                        /* Recebido */
                        if ($recebivel->fk_id_situacao_recebivel == 2)
                            echo '<tr bgcolor="#cef6d8">';
                        /* Em atraso */
                        elseif ($recebivel->fk_id_situacao_recebivel == 1 and strtotime($recebivel->data_vencimento) < strtotime($hoje))
                            echo '<tr bgcolor="#F8E0E0">';
                        else 
                            echo '<tr>';
                        ?>                        
                            <th scope="row">{{$index+1}}</th>
                            <td>{{$recebivel->descricao_conta}} - {{$recebivel->tipo_turma}} - {{$recebivel->ano}}</td>
                            <td>{{$recebivel->parcela}}</td>
                            <td>{{number_format($recebivel->valor_total, 2, ',', '.')}}</td>
                            <td>{{date('d/m/Y', strtotime($recebivel->data_vencimento))}}</td>       
                            <td>
                                @if ($recebivel->data_recebimento)
                                    {{date('d/m/Y', strtotime($recebivel->data_recebimento))}}    
                                @endif
                            </td>
                            <td>
                                @if ($recebivel->valor_recebido)
                                    {{number_format($recebivel->valor_recebido, 2, ',', '.')}}
                                @endif
                                </td>
                            {{-- <td >     --}}
                                {{-- Recebido --}}
                                {{-- @if ($recebivel->fk_id_situacao_recebivel == 2) --}}
                                    {{-- Impressão recibo --}}
                                    {{-- <a href="{{ route('recebimento.recibo', $recebivel->id_recebivel) }}" data-content="Recibo" data-toggle="popover" data-trigger="hover" class="btn btn-sm btn-outline-info"><i class="fas fa-receipt"></i>Recibo</a> --}}
                                {{-- @endif--}}
                            {{-- </td>  --}}
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="card-footer">                    
                    {!! $recebiveis->links()!!}    
                </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
              $(".alert").slideDown(300).delay(5000).slideUp(300);
        });    
    </script>
</body>
</html>
