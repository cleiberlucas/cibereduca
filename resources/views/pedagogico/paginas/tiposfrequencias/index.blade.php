

@extends('adminlte::page')

<section></section>

@section('title_postfix', ' Tipo de Frequência')

@section('content_header')
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >
            <a href="{{ route('tiposfrequencias.index') }} " class="">Tipos de Frequência</a>
        </li>
    </ol>
    
    <h1>Tipo de Frequência <a href="{{ route('tiposfrequencias.create') }}" class="btn btn-success"><i class="fas fa-plus-square"></i> Cadastrar</a></h1>    
    <br>
    <strong>*Causa Reprovação:</strong> caso seja SIM, será utilizado para contabilizar reprovação do aluno por ausência. Exemplo: Faltas.<br>
    <strong>**Padrão: </strong> caso seja SIM, será utilizado para indicar o tipo de frequência, automaticamente, ao lançar frequência de uma aula.
@stop

@section('content')
    <div class="container-fluid">       
        <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <th>Tipo de Frequência</th>
                        <th>Sigla</th>
                        <th>*Causa reprovação</th>
                        <th>**Padrão</th>
                        <th>Situação</th>
                        <th width="270">Ações</th>
                    </thead>
                    <tbody>                        
                        @foreach ($tiposFrequencias as $tipoFrequencia)
                            <tr>
                                <td>
                                    {{$tipoFrequencia->tipo_frequencia}}
                                </td> 
                                <td>
                                    {{$tipoFrequencia->sigla_frequencia}}
                                </td>                   
                                <td>
                                    @if ($tipoFrequencia->reprova == 1)
                                        Sim
                                    @else
                                        Não
                                    @endif  
                                </td>                   
                                <td>
                                    @if ($tipoFrequencia->padrao == 1)
                                        Sim
                                    @else
                                        Não
                                    @endif  
                                </td>
                                <td>                                    
                                    @if ($tipoFrequencia->situacao == 1)
                                        Ativa
                                    @else
                                        Inativa
                                    @endif   
                                </td>                                 
                                <td style="width=10px;">                                    
                                    <a href="{{ route('tiposfrequencias.edit', $tipoFrequencia->id_tipo_frequencia) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>                                   
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">
                    @if (isset($filtros))
                    {!! $tiposFrequencias->appends($filtros)->links()!!}
                    @else
                        {!! $tiposFrequencias->links()!!}    
                    @endif
                    
                </div>
        </div>
    </div>
@stop
