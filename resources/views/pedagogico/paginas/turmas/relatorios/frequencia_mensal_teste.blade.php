    {{-- início tabela --}}
    <table border=1 cellspacing=0 cellpadding=2 >
        {{-- cabecalho ficha de frequencia mensal --}}


        <tr>
            <td><strong>N°</strong></td>
            <td align="center"><strong>NOME DO(A) ALUNO(A)</strong></td>
            @for ($i = 0; $i < $qtColunasDias; $i++)
                <td width=11px></td>
            @endfor
        </tr>

        {{-- Lista de alunos --}}
        @foreach ($alunos as $index => $aluno)
            <tr> 
                <td>
                    {{$index+1}}
                </td>
                <td>
                    {{$aluno->nome}}
                </td> 
                @for ($i = 0; $i < $qtColunasDias; $i++)
                    <td></td>
                @endfor
            </tr>
        @endforeach
        
        {{-- Colunas dias --}}
        @for ($i = 1; $i <= 1; $i++)
            <tr>
                <td><br></td>
                <td></td>
                @for ($j = 0; $j < $qtColunasDias; $j++)
                    <td></td>
                @endfor
                
            </tr>       
        @endfor

    </table>{{-- fim tabela --}}
