<!DOCTYPE html> 
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$unidadeEnsino->nome_fantasia}}-Ficha Individual</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    
    <script type="text/javascript" src="{!!asset('/js/utils.js')!!}"></script>
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
    
    .centro-vertical{
        line-height:200px;
    }

    .foo { 
        writing-mode: vertical-rl; 
        transform: rotate(180deg);
         max-height: 100vw; 
        height: 200px;
        width: 38px;
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
            @foreach ($matriculas as $indexMatricula => $matricula)
                @include('secretaria.paginas._partials.cabecalho_redeeduca')
                    <div class="container">
                        {{-- Dados aluno --}}
                        <div class="row border border-dark">                            
                            <div class="col-sm-12 border border-dark border-right-0 border-top-0 border-bottom-0">
                                <div class="row">
                                    <div class="mt-2 col-sm-12 text-center border border-dark border-top-0 border-right-0 border-left-0">
                                        <i><strong><h4>FICHA INDIVIDUAL - {{$matricula->turma->tipoTurma->subNivelEnsino->sub_nivel_ensino}}</h4></strong></i>
                                    </div>
                                </div>
                                <div class="row">          
                                    <div class="my-2 col-sm-12">                                     
                                        Aluno(a):<strong> {{$matricula->aluno->nome}}</strong>
                                        &nbsp;&nbsp;&nbsp;&nbsp; Sexo: {{$matricula->aluno->sexo->sexo}}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">Data nascimento: {{date('d/m/Y', strtotime($matricula->aluno->data_nascimento))}}</div>
                                </div>
                                <div class="row">
                                    <div class="my-2 col-sm-12">Naturalidade: {{$matricula->aluno->naturalidade}}</div>
                                </div>
                                <div class="row">
                                    <div class="mb-2 col-sm-12">Filiação: {{$matricula->aluno->pai}} e {{$matricula->aluno->mae}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 border border-dark border-bottom-0 border-right-0 border-left-0">
                                        <strong>Ano Letivo: {{$matricula->turma->tipoTurma->anoLetivo->ano}} &nbsp;&nbsp;&nbsp; Série: {{$matricula->turma->nome_turma}}                                         
                                            &nbsp;&nbsp;&nbsp;
                                            Turno: {{$matricula->turma->turno->descricao_turno}}</div></strong>
                                </div>
                            </div>
                        </div>  {{-- fim dados alunos --}}
                        
                        <div class="row ">
                            <div class="my-0 py-5 col-sm-2 border border-dark border-top-0  text-center"><strong><i>ATIVIDADES</i></strong>                                </div>                            
                            {{-- Cabeçalho disciplinas --}}
                            @foreach ($gradeCurricular as $disciplina)
                                <div class="col foo font-cabecalho text-center border border-dark  border-right-0" >{{$disciplina->disciplina}}</div>                                
                            @endforeach
                        </div>

                        {{-- Bimestres --}}
                        @foreach ($periodosLetivos as $periodoLetivo)
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="row">
                                        <div class="px-1 col-sm-5 font-cabecalho text-center border border-dark border-top-0 ">
                                            <i><strong>{{$periodoLetivo->periodo_letivo}}</strong></i>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="row font-cabecalho ">
                                                <div class="ml-0 col-sm-12 text-center border border-dark border-left-0 border-top-0 ">Aproveitamento</div>
                                            </div>    
                                            <div class="row font-cabecalho ">
                                                <div class="ml-0 col-sm-12 text-center border border-dark border-left-0 border-top-0 ">N° de Faltas</div>
                                            </div>                                        
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-10">
                                    <div class="row">
                                        {{-- Varrendo disciplinas e resultados --}}
                                        <?php
                                            foreach ($gradeCurricular as $disciplina) {
                                                echo '<div class="col text-center font-cabecalho border border-dark border-top-0 border-left-0">';
                                                foreach ($resultados as $resultado) {
                                                    $existeNota = false;
                                                    if($resultado->fk_id_disciplina == $disciplina->id_disciplina 
                                                        and $resultado->fk_id_periodo_letivo == $periodoLetivo->id_periodo_letivo 
                                                        and $resultado->fk_id_matricula == $matricula->id_matricula){
                                                            $existeNota = true;      
                                                            if ($resultado->nota_media < 10){
                                                                if (number_format($resultado->nota_media, 1, ',', '.') < $mediaAprovacao)
                                                                    echo '<font color="red">';
                                                                echo number_format($resultado->nota_media, 1, ',', '.');
                                                            }
                                                            else
                                                                echo $resultado->nota_media;
                                                                echo '</font>';
                                                        break;
                                                    }
                                                }
                                                if (!$existeNota)
                                                    echo '-';
                                                echo '</div>';                                                
                                            }
                                            
                                        ?>{{-- fim disciplinas e resultados --}}
                                    </div>
                                    <div class="row">
                                        {{-- Varrendo disciplinas e faltas --}}
                                        <?php
                                            foreach ($gradeCurricular as $disciplina) {
                                                echo '<div class="col text-center font-cabecalho border border-dark border-top-0 border-left-0">';
                                                foreach ($resultados as $resultado) {
                                                    $existeFalta = false;
                                                    if($resultado->fk_id_disciplina == $disciplina->id_disciplina 
                                                        and $resultado->fk_id_periodo_letivo == $periodoLetivo->id_periodo_letivo 
                                                        and $resultado->fk_id_matricula == $matricula->id_matricula){
                                                            $existeFalta = true;
                                                            if ($resultado->total_faltas > 0)
                                                                echo $resultado->total_faltas;                                                        
                                                            else {
                                                                echo '-';
                                                            }
                                                        break;
                                                    }
                                                }
                                                if (!$existeFalta)
                                                        echo '-';
                                                echo '</div>';                                                
                                            }
                                            
                                        ?>{{-- fim disciplinas e faltas --}}
                                    </div>
                                </div>
                            </div>
                        @endforeach {{-- fim periodos letivos --}}
                        <div class="row ">
                            <div class="mx-0 px-0 py-1 col-sm-2 border border-dark border-top-0 text-center"><strong><i>Média Anual</i></strong></div> 
                        
                            <div class=" col-sm-10">
                                <div class="row">
                                    {{-- Varrendo média anual --}}
                                    <?php
                                        foreach ($gradeCurricular as $disciplina) {
                                            echo '<div class="col py-2 text-center font-cabecalho border border-dark border-top-0 border-left-0">';
                                            foreach ($notasMedias as $notaMedia) {
                                                $existeFalta = false;
                                                if($notaMedia->fk_id_disciplina == $disciplina->id_disciplina                                             
                                                    and $notaMedia->fk_id_matricula == $matricula->id_matricula){
                                                        echo '<strong>';
                                                        if ($notaMedia->media < 10 ){                                          
                                                            if (number_format($notaMedia->media, 1, ',', '.') < $mediaAprovacao)
                                                                echo '<font color="red">';
                                                            echo number_format($notaMedia->media, 1, ',', '.');
                                                        }
                                                        else {
                                                            echo $notaMedia->media;
                                                        }
                                                        echo '</font>';
                                                        echo '</strong>';
                                                    break;
                                                }
                                            }
                                            echo '</div>';                                                
                                        }
                                        
                                    ?>{{-- fim disciplinas e médias --}}
                                </div>
                            </div>
                        </div>
                    
                        <div class="row ">
                            <div class="mx-0 px-0 my-0 py-1 col-sm-2  border border-dark border-top-0 text-center"><strong><i>Recuperação</i></strong></div> 
                            <div class=" col-sm-10">
                                <div class="row">                                    
                                    <?php
                                        foreach ($gradeCurricular as $disciplina) {
                                            echo '<div class="col py-2 text-center font-cabecalho border border-dark border-top-0 border-left-0">-</div>';                                                
                                        }                                        
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="mx-0 px-0 my-0 py-1 col-sm-2 border border-dark border-top-0 text-center"><strong><i>Média Final</i></strong></div> 
                            <div class=" col-sm-10">
                                <div class="row">
                                    {{-- Varrendo média anual --}}
                                    <?php
                                        foreach ($gradeCurricular as $disciplina) {
                                            echo '<div class="col py-2 text-center font-cabecalho border border-dark border-top-0 border-left-0">';
                                            foreach ($notasMedias as $notaMedia) {
                                                $existeFalta = false;
                                                if($notaMedia->fk_id_disciplina == $disciplina->id_disciplina                                             
                                                    and $notaMedia->fk_id_matricula == $matricula->id_matricula){     
                                                        echo '<strong>';
                                                            if ($notaMedia->media < 10 ){                                          
                                                            if (number_format($notaMedia->media, 1, ',', '.') < $mediaAprovacao)
                                                                echo '<font color="red">';
                                                            echo number_format($notaMedia->media, 1, ',', '.');
                                                        }
                                                        else {
                                                            echo $notaMedia->media;
                                                        }
                                                        echo '</font>';
                                                        echo '</strong>';                                                                                      ;
                                                    break;
                                                }
                                            }
                                            echo '</div>';                                                
                                        }
                                        
                                    ?>{{-- fim disciplinas e média --}}
                                </div>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="mx-0 px-0 my-0 py-1 col-sm-2 border border-dark border-top-0 text-center"><strong><i>Total de Faltas</i></strong></div> 
                            <div class=" col-sm-10">
                                <div class="row">
                                    {{-- Varrendo média anual --}}
                                    <?php
                                        foreach ($gradeCurricular as $disciplina) {
                                            echo '<div class="col py-2 text-center  font-cabecalho border border-dark border-top-0 border-left-0">';
                                            foreach ($notasMedias as $notaMedia) {
                                                $existeFalta = false;
                                                if($notaMedia->fk_id_disciplina == $disciplina->id_disciplina                                             
                                                    and $notaMedia->fk_id_matricula == $matricula->id_matricula){
                                                        if ($notaMedia->faltas > 0)
                                                            echo "<strong>$notaMedia->faltas</strong>";
                                                        else {
                                                            echo '-';
                                                        }
                                                    break;
                                                }
                                            }
                                            echo '</div>';                                                
                                        }
                                        
                                    ?>{{-- fim disciplinas e faltas --}}
                                </div>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="mx-0 px-0 my-0 py-1 col-sm-2 border border-dark border-top-0 text-center"><strong><i>Carga Horária</i></strong></div> 
                            <div class="py-1 col-sm-10 border border-dark border-top-0 border-left-0 text-right">{{$cargaHorariaAnual}}</div>
                        </div>
                        <div class="row ">
                            <div class="mx-0 px-0 my-0 py-1 col-sm-2 border border-dark border-top-0 text-center"><strong><i>Resultado Final</i></strong></div> 
                            <div class=" col-sm-10 border border-dark border-top-0 border-left-0 text-right">
                                @foreach ($resultadosFinais as $resultadoFinal)
                                    @if ($resultadoFinal->fk_id_matricula == $matricula->id_matricula)
                                        <strong>{{$resultadoFinal->tipo_resultado_final}}</strong>
                                        @break
                                    @endif                                
                                @endforeach    
                            </div>
                        </div>
                        <div class="row ">
                            <div class="my-0 py-5 col-sm-2 border border-dark border-top-0 border-bottom-0 text-center"><strong><i>OBS.:</i></strong></div> 
                            <div class=" col-sm-10 border border-dark border-top-0 border-left-0 border-bottom-0"> </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 text-right border  border-dark">{{$unidadeEnsino->cidade_uf}}, <?php echo date('d/m/Y'); ?></div>
                        </div>
                        <div class="row ">
                            <div class="mt-0 pt-5 col-sm-6 border border-dark border-top-0 border-rigth-0 text-center"><br><br><strong>Diretor(a)</strong></div> 
                            <div class="mt-0 pt-5 col-sm-6 border border-dark border-top-0 text-center"><br><br><strong>Secretário(a)</strong></div> 
                        </div>
                        <div class="row">
                            <div class="col">&nbsp;</div>
                        </div>
                    </div>                    
                <div style="page-break-after: always"></div>                    
            @endforeach {{-- Fim array alunos --}}   
            
        </div>
    </body>

</html>
