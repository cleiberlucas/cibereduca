
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    {{-- início tabela --}}
    <table border=1 cellspacing=0 cellpadding=2 >
    <tr>
        <td colspan=2>            
            <strong>{{$turma->tipoTurma->anoLetivo->ano}} - {{$turma->nome_turma}}</strong>
            <br>
            <strong>{{$turma->tipoTurma->subNivelEnsino->sub_nivel_ensino}} - {{$turma->turno->descricao_turno}}</strong>
        </td>
        <td colspan=25>
            <strong>Mês: {{$mes}}</strong>
        </td>
    </tr>
    <tr>
        <td colspan=2>
            <strong>{{$disciplina->disciplina}}</strong>
        </td>
        <td colspan=25 align="center">
            <strong>Frequência às aulas dadas</strong>
        </td>
    </tr>
    <tr>
        <td><strong>N°</strong></td>
        <td align="center"><strong>Aluno(a)</strong></td>
        @for ($i = 0; $i < 25; $i++)
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
            @for ($i = 0; $i < 25; $i++)
                <td></td>
            @endfor
        </tr>
    @endforeach
    @for ($i = 0; $i < 5; $i++)
        <tr>
            <td><br></td>
            <td></td>
            @for ($j = 0; $j < 25; $j++)
                <td></td>
            @endfor
            
        </tr>    
    
    @endfor

</table>{{-- fim tabela --}}
</body>
</html>
