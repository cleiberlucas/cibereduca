<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Portal do Aluno - Declarações</title>
</head>
<body>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">    
@foreach ($documentosEscola as $index => $documento)
    @if ($index == 0)    
    <div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-xs-2">
            <img width="30%" height="" src="/vendor/adminlte/dist/img/logo.png" alt="">
        </div>
        <div class="form-group col-sm-6 col-xs-2" align="center"> 
            <h3>Portal do Aluno</h3>
            <h3>Documentos gerados para o(a) Aluno(a)</h3>
            <h4>{{$documento->matricula->aluno->nome}}</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-xs-2" align="center">
            <h5>Declarações {{$documento->matricula->turma->tipoTurma->anoLetivo->ano}}-{{$documento->matricula->turma->nome_turma}}</h5>
        </div>
    </div>
    </div>
        @break;
    @endif
@endforeach

    <div class="container-fluid">
        @include('admin.includes.alerts')     
        <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <th>#</th>                        
                        <th>Documento</th>
                        <th>Data</th>                        
                        <th>Situação</th>
                        <th>Ver</th>
                    </thead>
                    <tbody>                        
                        @foreach ($documentosEscola as $index => $documento)
                            <tr>
                                <th scope="row">{{$index+1}}</th>                                    
                                <td>
                                    @if ($documento->situacao_documento == 1)
                                        <a href="{{ route('matriculas.documentos_escola.show', $documento->id_documento_escola) }}" target="_blank" class="href">{{$documento->tipoDocumentoEscola->tipo_documento}}</a>
                                    @else
                                        {{$documento->tipoDocumentoEscola->tipo_documento}}
                                    @endif                                    
                                </td>                                
                                <td>
                                    {{date('d/m/Y H:i:s', strtotime($documento->data_geracao))}}
                                </td>                                
                                <td>
                                    @if ($documento->situacao_documento == 1)
                                        <b> Válido</b>
                                    @else
                                        <font color="red">Inválido</font>
                                    @endif
                                </td>                               
                                <td style="width=10px;">                                
                                    @if ($documento->situacao_documento == 1)
                                        <a href="{{ route('matriculas.documentos_escola.show', $documento->id_documento_escola) }}" target="_blank" class="btn btn-sm btn-outline-info">Imprimir</a>                                    
                                    @endif
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">
                    @if (isset($filtros))
                    {!! $documentosEscola->appends($filtros)->links()!!}
                    @else
                        {!! $documentosEscola->links()!!}    
                    @endif
                    
                </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
              $(".alert").slideDown(300).delay(5000).slideUp(300);
        });    
    </script>
</body>
</html>
