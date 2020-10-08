

@extends('adminlte::page')



@section('title_postfix', ' Tipo de Avaliação')

@section('content_header')
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >
            <a href="{{ route('tiposavaliacoes.index') }} " class="">Tipos de Avaliação</a>
        </li>
    </ol>
    
    <h1>Tipo de Avaliação <a href="{{ route('tiposavaliacoes.create') }}" class="btn btn-success"><i class="fas fa-plus-square"></i> Cadastrar</a></h1>    
    <br>
    
@stop

@section('content')
    <div class="container-fluid">       
        <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <th>Tipo de Avaliação</th>
                        <th>Sigla</th>                        
                        <th>Situação</th>
                        <th width="270">Ações</th>
                    </thead>
                    <tbody>                        
                        @foreach ($tiposAvaliacoes as $tipoAvaliacao)
                            <tr>
                                <td>
                                    {{$tipoAvaliacao->tipo_avaliacao}}
                                </td> 
                                <td>
                                    {{$tipoAvaliacao->sigla_avaliacao}}
                                </td>                                                   
                                <td>                                    
                                    @if ($tipoAvaliacao->situacao == 1)
                                        Ativa
                                    @else
                                        Inativa
                                    @endif   
                                </td>                                 
                                <td style="width=10px;">                                    
                                    <a href="{{ route('tiposavaliacoes.edit', $tipoAvaliacao->id_tipo_avaliacao) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>                                   
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">
                    @if (isset($filtros))
                    {!! $tiposAvaliacoes->appends($filtros)->links()!!}
                    @else
                        {!! $tiposAvaliacoes->links()!!}    
                    @endif
                    
                </div>
        </div>
    </div>
@stop
