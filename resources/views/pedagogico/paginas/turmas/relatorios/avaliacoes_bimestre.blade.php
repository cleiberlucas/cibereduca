<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Avaliações {{$periodoLetivo->periodo_letivo}} - {{$turma->nome_turma}}</title>
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
    
    .centro-vertical{
        line-height:200px;
    }

    .foo { 
        writing-mode: vertical-rl; 
        transform: rotate(180deg);
         max-height: 100vw; 
        height: 150px;
        width: 50px;
        /* text-align: center; */
    }

    .font-cabecalho{
        font-size:12px;
    }

    .larg-div-n {
      max-width: 100vw;
      width:30px;
    }

</style>
<body>
        
    <div class="container-fluid">        
  
        @foreach ($disciplinas as $disciplina)


      
            @include('secretaria.paginas._partials.cabecalho_redeeduca')        
            <div class="container">        
            
            
            <div class="mt-0 pt-0 text-center">                
                <h5>Avaliações Bimestrais</h5> 
            </div>
            
            <div class="row">
                <div class="col-sm-5">
                    <h6>{{$turma->nome_turma}} - {{$turma->sub_nivel_ensino}} - {{$turma->descricao_turno}}</h6>
                    
                    <h6>{{$periodoLetivo->periodo_letivo}}/{{$periodoLetivo->anoLetivo->ano}}</h6>
                    
                    <h6>Disciplina: {{$disciplina->disciplina}}</h6>
                </div>
            </div>            
           
            <div class="row mt-3 text-center ">

                <div class="larg-div-n  border border-dark">
                    <strong>Nº</strong>
                </div>

                <div class="col-sm-5 border border-dark">
                    <strong>Alunos</strong>
                </div>
                {{-- imprimindo tipos de avaliações --}}
                @foreach ($avaliacoes as $avaliacao)
                    
                        @if ($disciplina->id_disciplina == $avaliacao->fk_id_disciplina)
                            <div class="  foo font-cabecalho  border border-dark" > 
                            {{$avaliacao->tipo_avaliacao}}
                            <br>
                            ({{number_format($avaliacao->valor_avaliacao, 1, ',', '.')}})     
                        </div>  
                        @endif                        
                                                  
                @endforeach
                
                <div class="foo font-cabecalho  border border-dark" style="width: 50px;">
                    Média
                </div>

                <div class="foo font-cabecalho  border border-dark" style="width: 50px;">
                    Média Após Recuperação
                </div>

                <div class="foo font-cabecalho  border border-dark" style="width: 50px;">
                    Faltas Bimestre
                </div>
            </div>
            @foreach ($matriculas as $index => $matricula)
                <div class="row py-0 " >
                    <div class="larg-div-n font-cabecalho  pl-2 pr-1 text-center border border-dark "> {{str_pad($index+1, 2, '0', STR_PAD_LEFT)}} </div>
                        <div class="font-cabecalho col-sm-5  border border-dark "> {{$matricula->nome}} </div>                    
                        {{-- varrendo array p imprimir nota da avaliação de um aluno --}}
                        @foreach ($avaliacoes as $avaliacao)
                            
                            @if($disciplina->id_disciplina == $avaliacao->fk_id_disciplina)
                                <div class="font-cabecalho border border-dark text-center" style="width: 50px;">
                                <?php
                                    $mediaAprovaAvaliacao = $mediaAprovacao * $avaliacao->valor_avaliacao / 100;
                                ?>
                            
                                @foreach ($notas as $nota)                           
                                    @if ($nota->fk_id_avaliacao == $avaliacao->id_avaliacao
                                        and $nota->fk_id_matricula == $matricula->id_matricula)
                                        @if ($nota->nota > 0)
                                            @if ($nota->nota >= $mediaAprovaAvaliacao)
                                                {{number_format($nota->nota, 1, ',', '.')}}
                                            @else
                                                <font color="red">{{number_format($nota->nota, 1, ',', '.')}}</font>
                                            @endif
                                        @endif
                                        @break;
                                    @endif                                                
                                @endforeach
                                </div>    
                            @endif   
                            
                        @endforeach
                    
                        {{-- varrendo array p imprimir media e falta de um aluno --}}
                        @foreach ($resultados as $resultado)
                            @if ($resultado->fk_id_matricula == $matricula->id_matricula
                                and $disciplina->id_disciplina == $resultado->fk_id_disciplina)
                                <div class="font-cabecalho  border border-dark text-center " style="width: 50px;">
                                    @if ($resultado->nota_media > 0)
                                        @if($resultado->nota_media >= $mediaAprovacao)
                                            <strong> {{number_format($resultado->nota_media, 1, ',', '.')}} </strong> 
                                        @else
                                            <font color="red"><strong> {{number_format($resultado->nota_media, 1, ',', '.')}} </strong> </font>
                                        @endif
                                    @endif
                                </div>            
                                <div class="font-cabecalho  border border-dark text-center" style="width: 50px;"> </div>            
                                <div class="font-cabecalho  border border-dark text-center" style="width: 50px;">{{$resultado->total_faltas}} </div>
                                @break;
                            @endif
                            
                        @endforeach
                    </div>

                @endforeach {{-- fim matriculas --}}

                <div class="row mt-4">
                    <div class="col-sm-5 text-center">
                        ________ Aulas previstas
                    </div>
                    <div class="col-sm-5 text-center">
                        ________ Aulas dadas
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-sm-5 text-center">
                        _________________________________________________
                        <br>
                        Professor(a)
                    </div>
                    <div class="col-sm-5 text-center">
                        _________________________________________________
                        <br>
                        Coordenador(a)
                    </div>
                </div>
            </div>
        
            
            <br><br><br><br><br><br><br><br><br><br><br><br><br>

        
        @include('secretaria.paginas._partials.rodape_redeeduca')
            
        <div style="page-break-after: always"></div>         
    
        @endforeach            
    </div>
    </body>
</html>
