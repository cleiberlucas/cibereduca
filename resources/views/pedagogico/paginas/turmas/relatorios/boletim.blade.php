<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Boletim {{$turma->nome_turma}}</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>

<style>
    .font-cabecalho{
        font-size:12px;
    }

    .borda-solida{
        border-style: solid;
    }
</style>
<body>
    @foreach ($matriculas as $indexMatricula => $matricula)
        {{-- Cabeçalho --}}
        <div class="container border border-primary rounded">
            <div class="row">
                <div class="col-sm-2 col-xs-2 my-1 py-1"> 
                    <img src="/vendor/adminlte/dist/img/logo.png" width="60%" alt="logo">
                </div>
                <div class="col-sm-10 text-center">
                    <strong><h4>{{mb_strToUpper($unidadeEnsino->razao_social)}}</h4></strong>
                    <h5>BOLETIM ESCOLAR</h5>
                    
                    <h6>-{{$turma->ano}}-</h6>
                </div>
            </div>
        </div>
        {{-- Dados aluno --}}
        <div class="container border border-dark">
            <div class="row ">
                <div class="col-sm-12 col-xs-2 mt-2">
                    <strong>ALUNO(A): {{$matricula->nome}}</strong>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-5 col-xs-2">
                    <strong>Turma: {{$turma->nome_turma}} - {{$turma->descricao_turno}}</strong>
                </div>
               {{--  <div class="col-sm-3 col-xs-2">
                    <strong>Turno: {{$matricula->turma->turno->descricao_turno}}</strong>
                </div> --}}
                <div class="col-sm-4 col-xs-2">
                    <strong>Curso: {{$turma->sub_nivel_ensino}}</strong>
                </div>               
            </div>
        </div>
        {{-- Notas --}}
        <div class="container ">
            <div class="row font-cabecalho  text-center">
                <div class="col-sm-12 col-xs-2 border border-dark border-top-0 border-bottom-0">
                    <div class="row border-dark">
                        <div class="col-sm-3 col-xs-2 ">
                            <strong>Disciplinas</strong>
                        </div>                        
                        {{-- Colunas Períodos letivos --}}
                        @foreach ($periodosLetivos as $indexPeriodo => $periodoLetivo)                                                
                            <div class="col-sm-1 col-xs-2 border-dark border-bottom-0">                        
                                <div class="row text-center ">
                                    <div class="col-sm-12 col-xs-2 px-0 border border-dark border-right-0 border-top-0">
                                        <strong>{{$periodoLetivo->periodo_letivo}} </strong>
                                    </div>
                                </div>
                                <div class="row text-center border-dark">
                                    <div class="col-sm-6 col-xs-2 border border-dark border-top-0 border-right-0">
                                        <strong>N</strong>
                                    </div>
                                    <div class="col-sm-6 col-xs-2  border border-dark border-top-0 border-right-0 ">
                                        <strong>F</strong>
                                    </div>
                                </div>                   
                            </div> 
                            @if ($indexPeriodo == 1 )
                                <div class="col-sm-1 col-xs-2  border border-dark border-top-0 border-right-0"> 
                                    <div class="row  text-center">
                                        <div class="col-sm-12 col-xs-2 ">
                                            <div class="row  ">
                                            <div class="col-sm-6 col-xs-2 px-1 py-2  border border-dark border-top-0 border-left-0 border-bottom-0">
                                                <strong>RS1</strong>
                                            </div>
                                            <div class="col-sm-6 col-xs-2 px-1 py-2">
                                                <strong>MS1</strong>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>    
                            @endif

                            @if ($indexPeriodo == 3)                            
                                <div class="col-sm-1 col-xs-2 border border-dark border-top-0 border-right-0"> 
                                    <div class="row  ">
                                        <div class="col-sm-12 col-xs-2 ">
                                            <div class="row  ">
                                            <div class="col-sm-6 col-xs-2 px-1 py-2  border border-dark border-top-0 border-left-0 border-bottom-0">
                                                <strong>RS2</strong>
                                            </div>
                                            <div class="col-sm-6 col-xs-2 px-1 py-2">
                                                <strong>MS2</strong>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>                             
                            @endif
                        @endforeach {{-- fim colunas períodos letivos --}}
                        <div class="col-sm-1 col-xs-2 px-0 border border-dark border-top-0">
                            <strong>Média Final MRF</strong>
                        </div>
                        <div class="col-sm-1 col-xs-2 border border-dark border-top-0 border-left-0">
                            <strong>Total de Faltas</strong>
                        </div>
                        <div class="col-sm-1 col-xs-2 border border-dark border-top-0 border-left-0 border-right-0 ">
                            <strong>Resultado Final RF</strong>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Linhas disciplinas --}}
            @foreach ($disciplinas as $disciplina)
                <div class="row font-cabecalho border border-dark border-bottom-0 ">
                    <div class="col-sm-3 col-xs-2 ml-0 px-0 " > {{$disciplina->disciplina}} </div>
                    <?php 
                        $total_ms1 = 0;
                        $total_ms2 = 0;
                    ?>
                    @foreach ($periodosLetivos as $indexPeriodo => $periodoLetivo)
                        <?php
                            $achou_resultado = false;
                            $total_faltas_periodo = '-';
                        ?>
                        <div class="col-sm-1 col-xs-2 ">
                            <div class="row text-center border-dark">
                                <div class="col-sm-6 col-xs-2 px-0 border border-dark border-top-0 border-right-0 border-bottom-0">
                                {{-- Varrendo array p imprimir NOTA MÉDIA E FALTAS--}}   
                                <?php                                
                                foreach ($resultados as $indexResult => $resultado){
                                    //if ($indexResult == 0)
                                      
                                    if ($periodoLetivo->id_periodo_letivo == $resultado->fk_id_periodo_letivo
                                        and $disciplina->id_disciplina == $resultado->fk_id_disciplina
                                        and $matricula->id_matricula == $resultado->fk_id_matricula){     
                                        
                                        $achou_resultado = true;
                                        //IMPRIMINDO NOTA
                                        if ($resultado->nota_media == 10 or $resultado->nota_media == 0)
                                            echo $resultado->nota_media;                                        
                                        else
                                            if(number_format($resultado->nota_media, 1, '.', '.') >= $mediaAprovacao)
                                                echo number_format($resultado->nota_media, 1, ',', '.');
                                            else
                                                echo '<font color="red">'. number_format($resultado->nota_media, 1, ',', '.').'</font>';                                            
                                        
                                            $total_faltas_periodo = $resultado->total_faltas;
                                        
                                        if ($indexPeriodo <= 1)
                                            $total_ms1 = $total_ms1 + $resultado->nota_media;
                                        else 
                                            $total_ms2 = $total_ms2 + $resultado->nota_media;                                            
                                        
                                        break;
                                    }                                    
                                } 
                                if (!$achou_resultado )
                                    echo '-';
                                //FALTAS
                                echo '</div>
                                        <div class="col-sm-6 col-xs-2 border border-dark border-top-0 border-right-0  border-bottom-0">';                                
                                if ($total_faltas_periodo == 0)
                                    echo '-';
                                else
                                    echo $total_faltas_periodo;

                                echo '</div>';                                     
                                ?>                                
                            </div>
                        </div>
                        {{-- Mostrar RS1 E MS1 --}}
                        @if ($indexPeriodo == 1 )
                            <div class="col-sm-1 col-xs-2 border border-dark border-top-0 border-right-0 border-bottom-0"> 
                                <div class="row  ">
                                    <div class="col-sm-12 col-xs-2 ">
                                        <div class="row  ">
                                            <div class="col-sm-6 col-xs-2 border border-dark border-top-0 border-left-0 border-bottom-0"> {{-- RS1 --}} - </div>
                                            <div class="col-sm-6 col-xs-2 border-bottom-0">
                                                {{-- MS1 --}}                                                
                                                <?php 
                                                    if ($total_ms1 > 0)     
                                                        if ($total_ms1/2 >= $mediaAprovacao)                                                   
                                                            echo number_format(($total_ms1/2), 1, ',', '.'); 
                                                        else
                                                            echo '<font color="red">'.number_format(($total_ms1/2), 1, ',', '.').'</font>';
                                                    else {
                                                        echo '-';
                                                    }
                                                ?>                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                                
                        @endif
                        @if ($indexPeriodo == 3)                        
                            <div class="col-sm-1 col-xs-2 border border-dark border-top-0 border-right-0 border-bottom-0"> 
                                <div class="row  ">
                                    <div class="col-sm-12 col-xs-2 ">
                                        <div class="row  ">
                                            <div class="col-sm-6 col-xs-2 border border-dark border-top-0 border-left-0 border-bottom-0"> {{-- RS2 --}} - </div>
                                            <div class="col-sm-6 col-xs-2 border-bottom-0">
                                                {{-- MS2 --}}                                                
                                                <?php 
                                                    if ($total_ms2 > 0)
                                                        echo number_format(($total_ms2/2), 1, ',', '.'); 
                                                    else {
                                                        echo '-';
                                                    }
                                                ?>                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                          
                        @endif
                    @endforeach {{-- fim array periodos letivos --}}
                    <div class="col-sm-1 col-xs-2 border border-dark border-top-0 border-bottom-0 border-right-0"> </div>
                    <div class="col-sm-1 col-xs-2 border border-dark border-top-0 border-bottom-0 "> </div>
                    <div class="col-sm-1 col-xs-2 border-0"> Cursando </div>                    
                </div>
            @endforeach
            <div class="row border border-dark ">
                <div class="col-sm-12 col-xs-2">
                    <font size="2px">
                    Legenda: N: Nota &nbsp;&nbsp;&nbsp; F: Falta &nbsp;&nbsp;&nbsp; RS1: Resultado Semestral 1 &nbsp;&nbsp;&nbsp;MS1: Média Semestral 1 &nbsp;&nbsp;&nbsp;MS2: Média Semestral 2 &nbsp;&nbsp;&nbsp;RS2: Resultado Semestral 2                    
                    &nbsp;&nbsp;&nbsp;  MRF: Média Após Rec. Final &nbsp;&nbsp;&nbsp;RF: Resultado Final
                </font>
                </div>
            </div>              
            <div class="row ">
                <div class="col text-center mt-2">
                    <font color=blue>
                    <strong>CONHECIMENTO PARA O MUNDO. VALORES PARA A VIDA.</strong>
                    </font>
                </div>            
            </div>  
            <div class="row">
                <div class="col text-right">
                    <font size="1px">
                        <i>CiberEduca - Plataforma de Gestão Escolar</i>
                    </font>
                </div>        
            </div>
            <div class="row border-0">
                <div class="col border-0">                
                    <hr>
                    <br>
                </div>
            </div>              
        </div>
        {{-- Quebra página a cada 2 boletins --}}
        {{-- @if ($indexMatricula % 2 == 1) --}}
            <div style="page-break-after: always"></div>        
        {{-- @endif --}}
    @endforeach {{-- Fim array alunos --}}   
    
</body>
</html>
