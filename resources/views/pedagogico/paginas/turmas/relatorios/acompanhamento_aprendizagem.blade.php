<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Acompanhamento Aprendizagem</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>
    <div class="container-fluid">
        <div class="container">    
            @include('secretaria.paginas._partials.cabecalho')    
        </div>

        <div class="container">
            
            <div class="mt-4 text-center ">                
                <h6>Acompanhamento da Aprendizagem / Rendimento Bimestral do Aluno</h6>
            </div>
            <div class="text-center">
                <h6>{{$turma->nome_turma}} </h6>
            </div>

            <div class="row mt-4">
                Disciplina: {{$disciplina->disciplina}}
            </div>
            <div class="row">
                Professor(a):
            </div>

            <div class="row border border-dark text-center">
                <div class="col-sm-1 border border-dark">
                    Nº
                </div>
                <div class="col-sm-3 border border-dark">
                    Alunos
                </div>
                <div class="col-sm-4 border border-dark">
                    <div class="row ">
                        <div class="col-sm-12">
                            Aspectos Descritivos
                        </div>
                    </div>
                    <div class="row">
                        @for ($i = 0; $i < 12; $i++)
                            <?php
                            echo '<div class="col-sm-1 px-0 py-3 border border-dark"></div>'; ?>
                            
                        @endfor

                    </div>
                </div>

                <div class="col-sm-4 mb-0">
                    <div class="row ">
                        <div class="col-sm-3 py-3 mb-0 border border-dark ">
                            Pr
                        </div>
                        <div class="col-sm-3">
                            AD
                        </div>
                        <div class="col-sm-3">
                            Md
                        </div>
                        <div class="col-sm-3">
                            MAR
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>
