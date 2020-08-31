<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Avaliações {{$turma->nome_turma}} {{$periodoLetivo->periodo_letivo}}</title>
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
        <div class="container">    
            @include('secretaria.paginas._partials.cabecalho')    
        </div>

        <div class="container">
            
            <div class="mt-3 text-center">                
                <h5>Avaliações Bimestre</h5>
            </div>
            
            <div class="row">
                <div class="col-sm-5">
                    <h6>{{$turma->nome_turma}} - {{$turma->tipoTurma->subNivelEnsino->sub_nivel_ensino}} - {{$turma->turno->descricao_turno}}</h6>
                    
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

                @foreach ($avaliacoes as $avaliacao)
                    <div class="  foo font-cabecalho  border border-dark" >
                        {{$avaliacao->tipo_avaliacao}}
                        <br>
                        ({{number_format($avaliacao->valor_avaliacao, 2, ',', '.')}})
                    </div>                                
                @endforeach
                
                <div class="foo font-cabecalho  border border-dark" style="width: 50px;">
                    Média
                </div>

                <div class="foo font-cabecalho  border border-dark" style="width: 50px;">
                    Média Após Recuperação
                </div>

                <div class="foo font-cabecalho  border border-dark" style="width: 50px;">
                    Faltas Bimestral
                </div>


            </div>

            @foreach ($matriculas as $index => $matricula)
                <div class="row py-0 " >
                    <div class="larg-div-n font-cabecalho  pl-2 pr-1 text-center border border-dark ">
                        {{str_pad($index+1, 2, '0', STR_PAD_LEFT)}}
                    </div>
                    <div class="font-cabecalho col-sm-5  border border-dark ">
                        {{$matricula->aluno->nome}}
                    </div>
                    
                    {{-- varrendo array p imprimir nota da avaliação de um aluno --}}
                    @foreach ($avaliacoes as $avaliacao)
                        <div class="font-cabecalho border border-dark text-center" style="width: 50px;">
                            @foreach ($notas as $nota)                           
                                @if ($nota->fk_id_avaliacao == $avaliacao->id_avaliacao
                                    and $nota->fk_id_matricula == $matricula->id_matricula)
                                    
                                    {{number_format($nota->nota, 2, ',', '.')}}                                    
                                    @break;
                                @endif                                                
                            @endforeach
                        </div>    
                    @endforeach
                    
                    {{-- varrendo array p imprimir media e falta de um aluno --}}
                    @foreach ($resultados as $indResult => $resultado)
                        @if ($resultado->fk_id_matricula == $matricula->id_matricula or $indResult == 0)
                            <div class="font-cabecalho  border border-dark text-center " style="width: 50px;">
                                <strong>{{number_format($resultado->nota_media, 2, ',', '.') ?? ''}}     </strong>
                            </div>
            
                            <div class="font-cabecalho  border border-dark text-center" style="width: 50px;">
                                
                            </div>
            
                            <div class="font-cabecalho  border border-dark text-center" style="width: 50px;">
                                {{$resultado->total_faltas ?? ''}}
                            </div>
                            @if ($resultado->fk_id_matricula == $matricula->id_matricula)
                                @break;    
                            @endif                            
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
        
    </div>
    @include('secretaria.paginas._partials.rodape_cibereduca')
    
</body>
</html>
