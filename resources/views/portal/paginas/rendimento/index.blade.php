<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Portal do Aluno - Rendimento </title>
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
                <h3>Rendimento do(a) Aluno(a)</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-xs-2" align="center">
                <h4>Matrículas</h4>
            </div>
        </div>
        <br>
    </div>
    @include('admin.includes.alerts')
    <div class="container">
        <div class="table table-responsive"> 
            <table class="table table-hover">
                <thead>
                    <th scope="col">#</th>
                    <th scope="col">Aluno(a)</th>                        
                    <th scope="col">Turma</th>                        
                    <th scope="col" colspan=3>Acessos</th>
                </thead>
                <tbody>                     
                    @foreach ($matriculas as $index => $matricula)
                    <tr>
                        <td>{{$index+1}} </td>    
                        <td>{{$matricula->nome_aluno}}</td>
                        <td>{{$matricula->ano}}-{{$matricula->nome_turma}} {{$matricula->descricao_turno}}</td>
                        <td>
                            {{-- Boletim --}}
                            <form action="{{route('turmas.relatorios.diarios.filtros')}}" method="POST">
                                @csrf
                                <?php $hash = preg_replace("/[^a-zA-Z0-9\s]/", "", crypt($matricula->id_matricula, 'cs'));?>
                                <input type="hidden" name="hash" value="{{$hash}}">
                                <input type="hidden" name="anoLetivo" value="{{$matricula->id_ano_letivo}}">
                                <input type="hidden" name="tipo_relatorio" value="boletim_aluno">
                                <input type="hidden" name="fk_id_matricula" value="{{$matricula->id_matricula}}">
                                <input type="hidden" name="turma" value="{{$matricula->id_turma}}">                                
                                <button type="submit" class="btn btn-sm btn-outline-info">Boletim</button>
                            </form>
                        </td>
                        {{-- Avaliações --}}
                        <td>
                            <?php $hash = preg_replace("/[^a-zA-Z0-9\s]/", "", crypt($matricula->id_matricula, 'cs'));?>
                            <a href="{{route('portal.indexnotas', [$matricula->id_matricula, $hash])}}" class="btn btn-sm btn-outline-info">Avaliações</a>                            
                        </td>
                        {{-- Frequência --}}
                        {{-- <td>
                            <a href="{{route('portal.indexfrequencias', $matricula->id_matricula)}}" class="btn btn-sm btn-outline-info">Frequências</a>
                        </td> --}}
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

<script>
    $(document).ready(function(){
          $(".alert").slideDown(300).delay(5000).slideUp(300);
    });  
</script>
</html>