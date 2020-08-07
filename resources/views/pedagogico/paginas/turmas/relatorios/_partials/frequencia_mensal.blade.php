{{-- Cabeçalho da ficha de frequencia mensal --}}
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
