<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Portal do Aluno - Frequências </title>
</head>
<body>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3 col-xs-2">
                <img width="30%" height="" src="/vendor/adminlte/dist/img/logo.png" alt="">
            </div>
            <div class="form-group col-sm-6 col-xs-2" align="center"> 
                <h3>Portal do Aluno</h3>
                <h3>Frequências do(a) Aluno(a)</h3>
                <h4>{{$aluno->nome}}</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-xs-2" align="center">
                <h5>Frequências {{$aluno->ano}}-{{$aluno->nome_turma}} {{$aluno->descricao_turno}}</h5>
            </div>
        </div>
        <br>
    </div>
    <div class="container">
        <div class="table table-responsive"> 
            <table class="table table-hover">
                <thead>
                    <th scope="col">#</th>
                    <th scope="col">Período Letivo</th>
                    <th scope="col">Disciplina</th>
                    <th scope="col">Faltas</th>                    
                </thead>
                <tbody>                     
                    @foreach ($frequencias as $index => $frequencia)
                        <tr>
                            <td>{{$index+1}} </td>    
                            <td>{{$frequencia->periodo_letivo}}</td>
                            <td>{{$frequencia->disciplina}}</td>
                            <td>{{$frequencia->total_faltas}}</td>                            
                        </tr>    
                    @endforeach
                </tbody>
            </table>
        </div>
         <div class="card-footer">           
            {!! $frequencias->links()!!}
        </div>
        <div class="row justify-content-center">     
            <img width="2%" src="/vendor/cibersys/img/cubo_magico.gif" alt="">   
            CiberSys - Sistemas Inteligentes            
        </div>
    </div>
</body>
</html>
