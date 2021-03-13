<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Portal do Aluno - Declarações </title>
</head>
<body>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3 col-xs-2">
                <img width="30%" height="" src="vendor/adminlte/dist/img/logo.png" alt="">
            </div>
            <div class="form-group col-sm-6 col-xs-2" align="center"> 
                <h3>Portal do Aluno</h3>
                <h3>Recebíveis do(a) Aluno(a)</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-xs-2" align="center">
                <h4>Matrículas</h4>
            </div>
        </div>
        <br>
    </div>
    <div class="container">
        <div class="table table-responsive"> 
            <table class="table table-hover">
                <thead>
                    <th scope="col">#</th>
                    <th scope="col">Aluno(a)</th>                        
                    <th scope="col">Turma</th>                        
                    <th scope="col" colspan=2>Financeiro</th>
                </thead>
                <tbody>                     
                    @foreach ($matriculas as $index => $matricula)
                    <tr>
                        <td>{{$index+1}} </td>    
                        <td>{{$matricula->nome_aluno}}</td>
                        <td>{{$matricula->ano}}-{{$matricula->nome_turma}} {{$matricula->descricao_turno}}</td>   
                        <?php $hash = preg_replace("/[^a-zA-Z0-9\s]/", "", crypt($matricula->id_pessoa, 'cs'));?>                                             
                        <td><a href="{{route('portal.boletos', [$matricula->id_pessoa, $hash])}}" class="btn btn-sm btn-outline-info">Boletos</a></td>
                        <td><a href="{{route('portal.recebiveis', [$matricula->id_pessoa, $hash])}}" class="btn btn-sm btn-outline-info">Recebíveis</a></td>
                    </tr>    
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="row justify-content-center">     
            <img width="2%" src="vendor/cibersys/img/cubo_magico.gif" alt="">   
            CiberSys - Sistemas Inteligentes            
        </div>
    </div>
</body>
</html>