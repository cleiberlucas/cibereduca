{{-- Cabeçalho da ficha de frequencia mensal --}}
{{-- <tr>
    <td colspan=26 align="center"> --}}
        {{-- <strong>{{mb_strToUpper ($unidadeEnsino->nome_fantasia)}}</strong> --}}
        {{-- <img src="/vendor/adminlte/dist/img/cabecalho.jpg" width="100%" height="80%" alt="logo"> 
    </td>
</tr> --}}
<tr>
    <td colspan=2>            
        <strong>{{$turma->ano}} - {{$turma->nome_turma}}</strong>
        <br>
        <strong>{{$turma->sub_nivel_ensino}} - {{$turma->descricao_turno}}</strong>
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
