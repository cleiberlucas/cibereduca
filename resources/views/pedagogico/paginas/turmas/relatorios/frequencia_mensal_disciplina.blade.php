
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
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
        height: 96%;
    }

    body {
        min-height: 100%;
        display: grid;
        grid-template-rows: 1fr auto;
    }

    .footer {
        grid-row-start: 2;
        grid-row-end: 3;
    }

</style>

<body>
    {{-- início tabela --}}
    <table border=1 cellspacing=0 cellpadding=2 >
        <tr>
            <td colspan=26 align="center">
                <strong>{{strToUpper($unidadeEnsino->nome_fantasia)}}</strong>
            </td>
        </tr>
        <tr>
            <td colspan=2>            
                <strong>{{$turma->tipoTurma->anoLetivo->ano}} - {{$turma->nome_turma}}</strong>
                <br>
                <strong>{{$turma->tipoTurma->subNivelEnsino->sub_nivel_ensino}} - {{$turma->turno->descricao_turno}}</strong>
            </td>
            <td colspan=24>
                <strong>Mês: <?php echo nomeMes($mes); ?></strong>
            </td>
        </tr>
        <tr>
            <td colspan=2>
                <strong>{{$disciplina->disciplina}}</strong>
            </td>
            <td colspan=24 align="center">
                <strong>FREQUÊNCIA ÀS AULAS DADAS</strong>
            </td>
        </tr>
        <tr>
            <td><strong>N°</strong></td>
            <td align="center"><strong>NOME DO ALUNO(A)</strong></td>
            @for ($i = 0; $i < 24; $i++)
                <td width=10px></td>
            @endfor
        </tr>
        @foreach ($alunos as $index => $aluno)
            <tr> 
                <td>
                    {{$index+1}}
                </td>
                <td>
                    {{$aluno->nome}}
                </td> 
                @for ($i = 0; $i < 24; $i++)
                    <td></td>
                @endfor
            </tr>
        @endforeach
        @for ($i = 1; $i <= 3; $i++)
            <tr>
                <td><br></td>
                <td></td>
                @for ($j = 0; $j < 24; $j++)
                    <td></td>
                @endfor
                
            </tr>    
        
        @endfor

</table>{{-- fim tabela --}}

@include('secretaria.paginas._partials.rodape_cibereduca')

</body>
</html>
