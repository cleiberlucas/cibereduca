<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rendimento Escolar {{$turma->nome_turma}} {{$periodoLetivo->periodo_letivo}}</title>
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
        <div class="container">
            @include('secretaria.paginas._partials.cabecalho_redeeduca')    
            <div class="mt-0 pt-0 text-center">                
                <h5>Avaliação do Rendimento Escolar</h5>
            </div>            
            <div class="row">
                <div class="col-sm-5">
                    <h6>{{$turma->nome_turma}} - {{$turma->sub_nivel_ensino}} - {{$turma->descricao_turno}}</h6>                    
                    <h6>{{$periodoLetivo->periodo_letivo}}/{{$periodoLetivo->anoLetivo->ano}}</h6>
                </div>
            </div>
        </div>            
        <div class="container">
            <div class="row mt-3 text-center">
                <div class="col border border-dark"><strong>Nº</strong></div>
                <div class="col-sm-2 border border-dark"><strong>Aluno(a)</strong></div>
                @foreach ($gradeCurricular as $disciplina)
                    <div class="col px-0 foo font-cabecalho  border border-dark text-center">{{$disciplina->disciplina}}</div>                                
                @endforeach
                <div class="col px-0  foo font-cabecalho  border border-dark">Faltas</div>
            </div>
            @foreach ($matriculas as $index => $matricula)
                <div class="row py-0">
                    <div class="col font-cabecalho  text-center border border-dark">{{str_pad($index+1, 2, '0', STR_PAD_LEFT)}}</div>
                    <div class="col-sm-2 px-0 font-cabecalho  border border-dark">{{$matricula->nome}}</div>                    
                    <?php $total_faltas; 
                    /* varrendo array p imprimir media e falta de um aluno */                                         
                        foreach ($gradeCurricular as $disciplina){
                            echo '<div px-0 class="col px-0 font-cabecalho  border border-dark text-center">';
                            foreach ($resultados as $resultado){
                                if ($resultado->fk_id_matricula == $matricula->id_matricula
                                    and $resultado->fk_id_disciplina == $disciplina->fk_id_disciplina){                                    
                                    if ($resultado->nota_media > 0) {
                                        if(number_format($resultado->nota_media, 1, ',', '.') >= $mediaAprovacao)
                                            echo number_format($resultado->nota_media, 1, ',', '.');
                                        else
                                            echo '<font color="red"> '.number_format($resultado->nota_media, 1, ',', '.').'  </font>';                                    
                                    }                                
                                $total_faltas = $resultado->total_faltas; 
                                break;                            
                                }
                            }
                        echo '</div>';
                        }
                    ?>
                    <div class="col px-0 font-cabecalho  border border-dark text-center">
                        <?php echo $total_faltas ?? ''; ?>
                    </div>
                </div>
            @endforeach {{-- fim matriculas --}}
            <div class="row mt-4">
                <div class="col-sm-6 text-center">________ Aulas previstas</div>
                <div class="col-sm-6 text-center">________ Aulas dadas</div>
            </div>
            <div class="row mt-4">
                <div class="col-sm-6 text-center">_________________________________________________
                    <br>
                    Professor(a)
                </div>
                <div class="col-sm-6 text-center">_________________________________________________
                    <br>
                    Coordenador(a)
                </div>
            </div>
        </div>        
    </div>
    @include('secretaria.paginas._partials.rodape_redeeduca')    
</body>
</html>
