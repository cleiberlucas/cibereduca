<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Conteúdos Lecionados {{$turma->nome_turma}} {{$periodoLetivo->periodo_letivo}}</title>
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
            @foreach ($disciplinas as $disciplina)
        
            <div class="container">                    

                @include('secretaria.paginas._partials.cabecalho_redeeduca')
                <div class="row my-0 py-0">
                    <div class="col-sm-12 my-0 text-center">        
                        <h5>Conteúdos Lecionados</h5>
                    </div>            
                </div>
                
                <div class="row">
                    <div class="col-sm-5">
                        <h6>{{$turma->nome_turma}} - {{$turma->sub_nivel_ensino}} - {{$turma->descricao_turno}}</h6>
                        
                        <h6>{{$periodoLetivo->periodo_letivo}}/{{$periodoLetivo->anoLetivo->ano}}</h6>                    
                    </div>
                </div>
                  
                <br>         
                <strong><h6>Disciplina: {{$disciplina->disciplina}}</h6></strong>
                <div class="row mt-3 text-center ">

                    <div class="col-sm-1  border border-dark">
                        <strong>Dias</strong>
                    </div>

                    <div class="col-sm-11 border border-dark border-left-0">
                        <strong>Conteúdo Lecionado</strong>
                    </div>               
                </div>
           
                
                @foreach ($conteudosLecionados as $index => $conteudoLecionado)
                    @if ($disciplina->fk_id_disciplina == $conteudoLecionado->fk_id_disciplina)                                           
                        <div class="row py-0 " >                    
                            <div class="col-sm-1   border border-dark  border-top-0 ">
                                {{date('d/m', strtotime($conteudoLecionado->data_aula))}}
                            </div>
                            <div class="col-sm-11 border border-dark  border-top-0 border-left-0">
                                <?php echo  nl2br($conteudoLecionado->conteudo_lecionado); ?>
                            </div>   
                        </div>
                    @endif
                @endforeach {{-- fim conteudos --}}

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
            @include('secretaria.paginas._partials.rodape_redeeduca')
            {{-- Quebra de página em cada disciplina --}}
            <div style="page-break-after: always"></div>   
        
    @endforeach {{-- fim disciplinas --}}
        
</div>

         

</body>
</html>
