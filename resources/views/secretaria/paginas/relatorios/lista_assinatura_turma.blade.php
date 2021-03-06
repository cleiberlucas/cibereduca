<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="favicons/favicon.ico" >
    <title>Turma</title>
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

    table {
        border-collapse: collapse;
    }

    table, th, td {
        border: 1px solid black;
    }

</style>

<body>
    <div class="container-fluid ">        
        
        <div class="content">
            @include('secretaria.paginas._partials.cabecalho_redeeduca')
            <div class="row mt-0 pt-0">
                <div class="col-sm-11 col-xs-2 my-0" >  
                    <h5><strong>Ano:</strong> {{$turma->tipoTurma->anoLetivo->ano}}</h5>                    
                </div>
            </div>
            <div class="row">
                <div class="col-sm-11 col-xs-2 my-0" >  
                    <h5><strong>Turma:</strong> {{ $turma->nome_turma}} - {{$turma->turno->descricao_turno}}                    </h5>
                </div>
            </div>
            @if ($tipoDescontoCurso != '' and $tipoDescontoCurso->id_tipo_desconto_curso != 99)
                <div class="row">
                    <div class="col-sm-11 col-xs-2 my-0" >  
                        <h5><strong>Tipo Desconto Curso:</strong>  {{ $tipoDescontoCurso->tipo_desconto_curso}}</h5>
                    </div>
                </div>
            @endif
            <br>
            <div class="row">
                <div class="col-sm-11" align="center">
                    <h5>{{$titulo}}</h5>
                </div>
            </div>
            <div class="table">
                <table class="table table-sm border-collapse">
                    <thead class="thead-light text-center border-collapse">
                        <th>N°</th>
                        <th>Nome</th>                        
                        <th width="450px">Assinatura</th>
                        <th width="150px">Data</th>
                    </thead>
                
                    <tbody>                        
                        @foreach ($matriculas as $index => $matricula)
                            <tr scope="row my-1 py-1 ">
                                <th scope="row">{{$index+1}}</th>
                                <td>{{$matricula->aluno->nome}}</td>
                                <td></td>
                                <td></td>
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