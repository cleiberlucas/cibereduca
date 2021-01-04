<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="favicons/favicon.ico" >
    
    <title>Responsáveis cadastrados </title>    
    
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
            @include('secretaria.paginas._partials.cabecalho_redeeduca')            
            <br>
            <div class="row">
                <div class="col-sm-11" align="center">
                    <h5>Responsáveis cadastrados</h5>
                </div>
            </div>
            <div class="table">
                <table class="table table-sm table-hover">
                    <thead class="thead-light">
                        <th>N°</th>
                        <th>Nome</th>                        
                        <th>CPF</th>
                        <th>Email</th>
                        <th>Telefone</th>
                    </thead>
                
                    <tbody>                        
                        @foreach ($responsaveis as $index => $responsavel)
                            <tr scope="row my-1 py-1">
                                <th scope="row">{{$index+1}}</th>
                                <td>
                                    {{$responsavel->nome}}
                                </td>
                                <td>
                                    {{mascaraCpfCnpj('###.###.###-##', $responsavel->cpf)}}
                                </td>
                                <td>{{$responsavel->email_1}}</td>
                                <td>                                    
                                    @if (strlen($responsavel->telefone_1) == 11  )
                                        {{mascaraTelefone("(##) #####-####", $responsavel->telefone_1)}}
                                    @else
                                        {{mascaraTelefone("(##) ####-####", $responsavel->telefone_1)}}
                                    @endif

                                    @if (strlen($responsavel->telefone_2) == 11  )
                                         / {{mascaraTelefone("(##) #####-####", $responsavel->telefone_2)}}
                                    @else
                                        / {{mascaraTelefone("(##) ####-####", $responsavel->telefone_2)}}
                                    @endif                                     
                                </td>                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-sm-11" align="right">
                    <font size="1px"><i>Gerado em <?php echo date('d/m/Y H:m:i');?></i></font>
                </div>
            </div>

        </div> {{-- fim div content --}}
       
    </div>
    @include('secretaria.paginas._partials.rodape_redeeduca')
    
</body>
</html>