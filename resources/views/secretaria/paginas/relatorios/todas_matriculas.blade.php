<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Turma</title>
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
        @include('secretaria.paginas._partials.cabecalho')
        <div class="content">
            <div class="row mt-5">
                <div class="col-sm-11 col-xs-2 my-0" >  
                    <h5><strong>Ano:</strong> {{$anoLetivo->ano}}</h5>                    
                </div>
            </div>           
            
            <div class="row">
                <div class="col-sm-11" align="center">
                    <h5>Todos os Alunos Matriculados</h5>
                </div>
            </div>
            <div class="table">
                <table class="table table-sm table-hover">
                    <thead class="thead-light">
                        <th>N°</th>
                        <th>Nome</th>                        
                        <th>Turma</th>
                        <th>Matriculado por</th>
                    </thead>
                
                    <tbody>                        
                        @foreach ($matriculas as $index => $matricula)
                            <tr scope="row my-1 py-1">
                                <th scope="row">{{$index+1}}</th>
                                <td>
                                    {{$matricula->aluno->nome}}
                                </td>
                                <td>
                                    {{ $matricula->turma->nome_turma}} - {{$matricula->turma->turno->descricao_turno}}
                                </td>
                                <td>
                                    {{$matricula->usuarioMatricula->name}}
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
    @include('secretaria.paginas._partials.rodape_cibereduca')
    
</body>
</html>