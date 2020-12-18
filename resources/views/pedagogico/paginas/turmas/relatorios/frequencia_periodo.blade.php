<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ficha Frequência Bimestral</title>
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
    {{-- início tabela --}}
    <div class="container-fluid">
        @foreach ($disciplinas as $disciplina)     
            @include('secretaria.paginas._partials.cabecalho_redeeduca')       
            
            <div class="container">            
                <div class="row">
                    <div class="col-sm-12"><strong>Ano Letivo {{$turma->ano}} - Período Letivo: {{$periodoLetivo->periodo_letivo}}</strong></div>
                </div>
                <div class="row">
                    <div class="col-sm-12"><strong>{{$turma->nome_turma}} - {{$turma->sub_nivel_ensino}} - {{$turma->descricao_turno}}</strong></div>
                </div>
                <div class="row">            
                    <div class="col-sm-12"><strong>Disciplina: {{$disciplina->disciplina}}</strong></div>
                </div>                            
                <table  align="center">
                    <tr>
                        <td><h5><strong>FREQUÊNCIA ÀS AULAS DADAS</strong></h5></td>
                    </tr>
                </table>
            
                <div class="row">
                    <div class="col-sm-12 text-center" >
                        <table border=1 cellspacing=0 cellpadding=2 align="center">
                            <tr>
                                <td><strong>N°</strong></td>
                                <td align="center"><strong>Nome do(a) Aluno(a)</strong></td>
                                
                                {{-- Mostrando dias que houve frequencia --}}
                                <?php foreach ($diasFrequencias as $diaFrequencia){
                                        if ($diaFrequencia->fk_id_disciplina == $disciplina->id_disciplina)
                                            echo '<td widht="10px" class="font-cabecalho" align="center"> '.$diaFrequencia->dia.'/'.$diaFrequencia->mes.'</td>';
                                } ?>
                                
                                {{-- Total de faltas --}}
                                <td class="font-cabecalho" >
                                    TF
                                </td>
                            </tr>

                            {{-- Lista de alunos e informações de frequencia --}}
                            @foreach ($alunos as $index => $aluno)
                                <tr> 
                                    <td>
                                        {{$index+1}}
                                    </td>
                                    <td>
                                        {{$aluno->nome}}
                                    </td> 
                                    <?php $totalFaltas = 0; ?>
                                    <?php foreach ($diasFrequencias as $diaFrequencia){ 
                                            if ($diaFrequencia->fk_id_disciplina == $disciplina->id_disciplina){                      
                                                echo '<td align="center" class="font-cabecalho">';                                
                                                foreach ($frequencias as $frequencia){
                                                    if ($diaFrequencia->dia == date('d', strtotime($frequencia->data_aula))
                                                        and $diaFrequencia->mes == date('m', strtotime($frequencia->data_aula))
                                                        and $diaFrequencia->fk_id_disciplina == $frequencia->fk_id_disciplina
                                                        and $aluno->id_matricula == $frequencia->fk_id_matricula){
                                                            echo $frequencia->sigla_frequencia.' ';
                                                        
                                                        if ($frequencia->sigla_frequencia == 'F')
                                                            $totalFaltas++;                                                                            
                                                    }
                                                }
                                                echo '</td>';
                                            }                        
                                        }
                                    echo '<td class="font-cabecalho" >';
                                        echo $totalFaltas;
                                    echo '</td>'; ?>                    
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan=" <?php echo count($diasFrequencias) + 3?>" align="right" ><font size='1px'>TF=Total de Faltas</font></td>
                            </tr>        
                        </table>{{-- fim tabela --}}
                    </div>
                </div>        
                
            </div>{{-- fim container --}}
            {{-- @include('secretaria.paginas._partials.rodape_redeeduca') --}}
            {{-- próxima página --}}
            <footer class="footer">        
                <div class="row my-0 py-0 mr-0 ">            
                    <div class="col-sm-11 col-xs-2 ml-5 my-0 py-0 text-right">
                        <font size="1px">CiberEduca - Plataforma de Gestão Escolar</font>
                    </div>         
                </div>
            </footer>
            
            <div style="page-break-after: always"></div>
        @endforeach
    </div>

</body>
</html>
