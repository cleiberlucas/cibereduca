<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ficha Frequência Mensal</title>
</head>
<style>   
    .row {    
        margin-top: -0.25rem !important;
        /* margin-right: 5; */
        margin-bottom: 0;
        /* padding-right: 5; */
        padding-top: 0;
        padding-bottom: 0
    }
    p{
        font-size: 20px;
    }
    html {
        height: 98%;
    }

    body {
        min-height: 98%;
        display: grid;
        grid-template-rows: 1fr auto;
    }

    .footer {
        grid-row-start: 2;
        grid-row-end: 3;
    }

</style>

<body>
    <div class="container-fluid">
        <div class="container">
            @include('secretaria.paginas._partials.cabecalho_redeeduca')
        
    {{-- início tabela --}}
    <br>
    <table border=1 cellspacing=0 cellpadding=2 >
           
        {{-- cabecalho ficha de frequencia mensal --}}
        @include('pedagogico.paginas.turmas.relatorios._partials.frequencia_mensal')

        <tr>
            <td><strong>N°</strong></td>
            <td align="center"><strong>NOME DO(A) ALUNO(A)</strong></td>
            
            {{-- Mostrando dias que houve frequencia --}}
            @foreach ($diasFrequencias as $diaFrequencia)
                <td widht="10px" align="center"> {{$diaFrequencia->dia}} </td>
             @endforeach
            
             {{-- Total de faltas --}}
             <td>
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
                @foreach ($diasFrequencias as $diaFrequencia)
                    
                    <td align="center">
                        @foreach ($frequencias as $frequencia)
                            @if ($diaFrequencia->dia == date('d', strtotime($frequencia->data_aula)) and $aluno->id_matricula == $frequencia->fk_id_matricula)
                                {{$frequencia->sigla_frequencia}}
                                
                                @if ($frequencia->sigla_frequencia == 'F')
                                    <?php $totalFaltas++; ?>
                                @endif    
                            @endif
                        @endforeach                        
                    </td>
                @endforeach

                <td>
                    <?php echo $totalFaltas;?>
                </td>
                
            </tr>
        @endforeach

        <tr>
            <td colspan=" <?php echo count($diasFrequencias) + 3?>" align="right" >
                <font size='1px'>TF=Total de Faltas</font>
            </td>
        </tr>
    
    </table>{{-- fim tabela --}}
    </div>
</div>
    @include('secretaria.paginas._partials.rodape_redeeduca')       
</body>
</html>
