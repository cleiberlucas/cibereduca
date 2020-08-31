<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Acompanhamento Aprendizagem</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>

<style>
    html {
        height: 96%;
    }

    body {
        min-height: 100%;
        display: grid;
        grid-template-rows: 1fr auto;
    }
    
    .centro-vertical{
        line-height:200px;
    }

    .foo { 
        writing-mode: vertical-lr; 
        transform: rotate(180deg);
        text-align: center;
    }

    .font-cabecalho{
        font-size:12px;
    }

</style>
<body>
    <div class="container-fluid">
        <div class="container">    
            @include('secretaria.paginas._partials.cabecalho')    
        </div>

        <div class="container">
            
            <div class="mt-3 text-center ">                
                <h6>Acompanhamento da Aprendizagem / Rendimento Bimestral do Aluno</h6>
            </div>
            <div class="text-center">
                <h6>{{$turma->nome_turma}} - {{$turma->tipoTurma->subNivelEnsino->sub_nivel_ensino}} &nbsp;&nbsp;&nbsp; {{$periodoLetivo->periodo_letivo}}/{{$periodoLetivo->anoLetivo->ano}}</h6>
            </div>

            <div class="row mt-3">
                Disciplina: {{$disciplina->disciplina}}
            </div>
            <div class="row">
                Professor(a):
            </div>

            <div class="row mt-3 text-center">
                <div class="centro-vertical border border-dark">
                    <strong>Nº</strong>
                </div>

                <div class="col-sm-5 centro-vertical border border-dark">
                    <strong>Alunos</strong>
                </div>

                <div class="col-sm-4 border border-dark">
                    <div class="row ">
                        <div class="col-sm-12 border border-dark">
                            Aspectos Descritivos                            
                        </div>
                    </div>
                    <div class="row border border-dark">
                        @for ($i = 0; $i < 11; $i++)
                            <div class="col-sm-1 px-0 pt-4 pb-1 border border-dark"> </div>                            
                        @endfor
                        <div class="foo font-cabecalho col-sm-1 px-2 mt-1  ">
                            Estudos de Recuperação Paralela
                        </div>

                    </div>
                </div>

                <div class="col-sm-2  border border-dark">
                    <div class="row font-cabecalho">
                        <div class="foo col-sm-3 pb-0 border border-dark ">
                            Avaliação Oficial (Prova) (50,0)
                        </div>
                        <div class="foo col-sm-3 py-3 border border-dark ">
                            Aspectos Descritivos (50,0)
                        </div>
                        <div class="foo col-sm-3 py-3 border border-dark ">
                            Média (100)
                        </div>
                        <div class="foo col-sm-3 py-3 border border-dark ">
                            Média após recuperação: _______
                        </div>
                    </div>
                </div>
            </div>

            @foreach ($matriculas as $index => $matricula)
                <div class="row py-0 " >
                    <div class="font-cabecalho  pl-2 pr-1 text-center border border-dark ">
                        {{str_pad($index+1, 2, '0', STR_PAD_LEFT)}}
                    </div>
                    <div class="font-cabecalho col-sm-5  border border-dark ">
                        {{$matricula->aluno->nome}}
                    </div>
                    
                    <div class="col-sm-4 border border-dark border-top-0 border-right-0">
                        <div class="row ">
                            <div class="col-sm-12 ">
                    
                            </div>
                        </div>
                        <div class="row ">
                            @for ($i = 0; $i < 12; $i++)
                                <div class="col-sm-1 px-0 pt-4 pb-1 border border-dark border-top-0 border-left-0 border-bottom-0">
                                            
                                </div>
                            @endfor
                        </div>
                    </div>

                    <div class="col-sm-2 border border-dark border-top-0">
                        <div class="row ">
                            <div class="col-sm-3 pb-0 border border-dark  border-top-0 border-left-0 border-bottom-0">
                                
                            </div>
                            <div class="col-sm-3 py-3 border border-dark border-top-0 border-left-0 border-bottom-0 ">
                                
                            </div>
                            <div class="col-sm-3 py-3 border border-dark border-top-0 border-left-0 border-bottom-0">
                                
                            </div>
                            <div class="col-sm-3 py-3  ">
                                
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach {{-- fim matriculas --}}

            <div class="row mt-4">
                <div>
                    Assinatura do professor(a): _________________________________________________
                </div>
            </div>
        </div>
        
    </div>
    @include('secretaria.paginas._partials.rodape_cibereduca')
    
</body>
</html>
