<!DOCTYPE html> 
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$unidadeEnsino->nome_fantasia}}-Resultado Anual</title>
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
            @include('secretaria.paginas._partials.cabecalho_redeeduca')
            <strong><h4>Resultado Anual - {{$turma->tipoTurma->anoLetivo->ano}}</h4></strong>
            <strong><h5>Série: {{$turma->nome_turma}} 
                &nbsp;&nbsp;&nbsp;
                Turno: {{$turma->turno->descricao_turno}}</h5></strong>            
            <div class="container">                        
                <div class="row">
                    <div class="col-sm-3">Avaliação do Rendimento Escolar</div>
                     {{-- disciplinas --}}
                     <?php foreach ($gradeCurricular as $indexGradeCurricular => $disciplina){
                            if ($indexGradeCurricular > 4)
                                break;
                         ?>
                        <div class="colfoo-cabecalho border border-dark">{{$disciplina->disciplina}}</div>
                    <?php 
                        
                    } ?>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="row">
                            <div class="col-sm-1 px-0 border border-dark"><strong>Nº</strong></div>
                            <div class="col-sm-10 border border-dark border-left-0"><strong>Aluno(a)</strong></div>
                        </div>
                    </div>
                    {{-- cabeçalho períodos --}}
                    <?php foreach ($gradeCurricular as $indexGradeCurricular1 => $disciplina){     
                            if ($indexGradeCurricular1 > 4)
                                break;                      
                            foreach ($periodosLetivos as $periodoLetivo){ ?>
                                <div class="col px-0 foo foo-cabecalho text-center border border-dark border-left-0"><strong>{{$periodoLetivo->periodo_letivo}}</strong></div>                                
                            <?php } ?>
                        <div class="col px-0 foo foo-cabecalho text-center border border-dark border-left-0">Média Anual</strong></div>                        
                    <?php 
                        
                    } ?>
                </div>            
                <?php
                        foreach ($matriculas as $indMatricula => $matricula) {
                            $i = $indMatricula+1;
                            echo '<div class="row">';
                                echo '<div class="col-sm-3">';
                                    echo '<div class="row">';
                                        echo '<div class="col-sm-1 px-0 border border-dark border-top-0"><strong>'.$i.'</strong></div>';                                        
                                        echo '<div class="col-sm-10 border border-dark border-left-0 border-top-0">'.$matricula->nome.'</div>';
                                    echo '</div>';
                                echo '</div>';

                                foreach ($gradeCurricular as $indexGradeCurricular2 => $disciplina){                                   
                                    if ($indexGradeCurricular2 > 4)
                                    break;
                                    foreach ($periodosLetivos as $periodoLetivo){
                                        echo '<div class="col px-0 text-center border border-dark border-left-0 border-top-0">';
                                        /* imprimindo média do bimestre */
                                        foreach ($resultados as $resultado) {                                     
                                            if ($resultado->fk_id_matricula == $matricula->id_matricula 
                                                and $resultado->fk_id_periodo_letivo == $periodoLetivo->id_periodo_letivo
                                                and $resultado->fk_id_disciplina == $disciplina->id_disciplina){
                                                if ($resultado->nota_media < 10){
                                                    if (number_format($resultado->nota_media, 1, ',', '.') < $mediaAprovacao)
                                                        echo '<font color="red">';
                                                    echo '<strong>'.number_format($resultado->nota_media, 1, ',', '.').'</strong>';
                                                    echo '</font>';
                                                }
                                                else {
                                                    echo '<strong>'.$resultado->nota_media.'</strong>';
                                                }
                                                break;
                                                
                                            }
                                        }                                        
                                        echo '</div>';                                                                          
                                    }
                                    echo '<div class="col px-0 text-center border border-dark border-left-0 border-top-0">MA</div>';  
                                }                                
                                /* imprimindo média anual */
                               /*  echo '<div class="col text-center border border-dark border-left-0 border-top-0">';
                                    foreach ($notasMedias as $notaMedia) {
                                        if ($notaMedia->fk_id_matricula == $matricula->id_matricula
                                            and $notaMedia->fk_id_disciplina == $disciplina->id_disciplina) {
                                            if ($notaMedia->media < 10){
                                                if (number_format($notaMedia->media, 1, ',', '.') < 6)
                                                    echo '<font color="red">';
                                                echo '<strong>'.number_format($notaMedia->media, 1, ',', '.').'</strong>';
                                                echo '</font>';
                                            }
                                            else {
                                                echo '<strong>'.$notaMedia->media.'</strong>';
                                            }
                                            break;
                                        }
                                    }
                                echo '</div>'; */

                            echo '</div>';
                        }
                    ?>   
            </div>                
                {{-- @include('secretaria.paginas._partials.rodape_redeeduca')     --}}                
            
        </div>
    </body>
</html>
