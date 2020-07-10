<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Conteúdo Lecionado</title>
</head>
<body>
    <table>
        <tr>
            <td>
                DIAS
            </td>
            <td>
                Disciplina: 
            </td>
            <td>
                Turma: {{$turma->nome_turma}} {{$turma->tipoTurma->subNivelEnsino->sub_nivel_ensino}} {{$turma->turno->descricao_turno}}
            </td>
            <td>
                Período: 
            </td>
        </tr>
    </table>
</body>
</html>