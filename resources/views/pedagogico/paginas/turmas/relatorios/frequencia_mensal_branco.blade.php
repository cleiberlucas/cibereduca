
<!DOCTYPE html>
<html lang="pt-br">
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
        height: 100%;
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
        {{-- cabecalho ficha de frequencia mensal --}}
        @include('pedagogico.paginas.turmas.relatorios._partials.frequencia_mensal')

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

 
    <footer class="footer">        
        <div class="row my-0 py-0 mr-0 ">            
            <div class="col-sm-11 col-xs-2 ml-5 my-0 py-0 text-right">
                <font size="1px">CiberEduca - Plataforma de Gestão Escolar</font>
            </div>         
        </div>
      
        <div class="row mx-0 my-0 py-0">
            <div class="col-sm-12 text-center my-0 py-0 mx-0">        
                <img src="/vendor/adminlte/dist/img/rodape.jpg" width="100%" height="90%" alt="logo">
            </div>
        </div>
        {{-- <div class="row my-0">
            <div class="col-sm-12 col-xs-2 ml-5 my-0 py-0" align="center">
                <font size="1px">CiberSys - Sistemas Inteligentes</font>
            </div>            
        </div> --}}

    </footer> 

</body>
</html>
