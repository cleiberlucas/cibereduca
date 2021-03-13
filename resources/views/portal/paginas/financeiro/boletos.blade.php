<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Portal do Aluno - Boletos</title>

</head>
<body>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">    
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    {{-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> --}}

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3 col-xs-2">
                <img width="30%" height="" src="/vendor/adminlte/dist/img/logo.png" alt="">
            </div>
            <div class="form-group col-sm-6 col-xs-2" align="center"> 
                <h3>Portal do Aluno</h3>
                <h3>Boletos do(a) Aluno(a)</h3>
                <h4>{{$aluno->nome}}</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-xs-2" align="center">
                <h5>Boletos</h5>
            </div>
        </div>
    </div>
    <div class="container">
        @include('admin.includes.alerts')     
        <div class="table table-responsive">
                <table class="table table-hover">
                    <thead>
                        <th scope="col">#</th>
                        <th scope="col">Boleto</th> 
                        <th scope="col">Data Vencimento</th>                               
                        <th scope="col">Valor</th>        
                        <th scope="col">Data Pagamento</th>                        
                        <th scope="col">Situação</th>
                        <th scope="col">Ações</th>                    
                    </thead>
                    <tbody>                        
                        @foreach ($boletos as $index => $boleto)
                        <?php
                            $hoje = date('Y-m-d');
                            /* Pago */
                            if ($boleto->fk_id_situacao_registro == 4)
                                echo '<tr bgcolor="#cef6d8">';
                            /* Em atraso */
                            elseif ($boleto->fk_id_situacao_registro <= 3 and strtotime($boleto->data_vencimento) < strtotime($hoje))
                                echo '<tr bgcolor="#F8E0E0">';
                            else 
                                echo '<tr>';
                            ?>
                                <th> {{$index+1}}</th>                                  
                                <td>                                   
                                    <a href="#" onclick="return false;" class="disabled" data-content="{{$boleto->instrucoes_recebiveis}}"{{--  title="Recebíveis" --}} data-toggle="popover" data-trigger="hover" role="button" aria-disabled="true">Detalhes Boleto</a>                                        
                                </td>
                                <td>
                                    {{date('d/m/Y', strtotime($boleto->data_vencimento))}}
                                </td>  
                                <td>
                                    {{number_format($boleto->valor_total, 2, ',', '.')}}    
                                </td>                             
                                <td>
                                    @if (isset($boleto->data_recebimento))
                                        {{date('d/m/Y', strtotime($boleto->data_recebimento))}}    
                                    @endif                                
                                </td>           
                                <td>
                                    {{$boleto->situacao_registro}}
                                </td>  
                                <td>
                                    <?php $hash = preg_replace("/[^a-zA-Z0-9\s]/", "", crypt($boleto->id_boleto, 'cs'));?>
                                    <a href="{{ route('boleto.imprimir', [$boleto->id_boleto, $hash])}}" class="btn btn-sm btn-outline-info">Imprimir</a>
                                </td>                                                                           
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">                    
                    {!! $boletos->links()!!}    
                </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
              $(".alert").slideDown(300).delay(5000).slideUp(300);
        });    
        $('[data-toggle="popover"]').popover();  
    </script>
</body>
</html>
