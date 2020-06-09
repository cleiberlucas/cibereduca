@extends('adminlte::page')

@section('title', 'Documentos Matrícula')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcamb-item">
            <a href="">Cadastrar</a> / 
        </li>
        <li class="breadcrumb-item active" >
{{--             <a href="{{ route('matriculas.index') }} " class="">Matrículas</a> --}}
        </li>
    </ol>

    <h1><strong>{{$matricula->aluno->nome}}</strong> 
    <h2>{{$matricula->turma->nome_turma}} - {{$matricula->turma->tipoTurma->anoLetivo->ano}}</h2>
    <h2>Entrega de Documentos </h2>
    {{-- <a href="{{ route('matriculas.permissoes.add', $matricula->id_matricula) }}" class="btn btn-dark">ADD permissão</a></h1> --}}

@stop

@section('content')
    <div class="card">
        {{-- <div class="card-header">
            <form action="{{ route('matriculas.search') }}" method="POST" class="form form-inline">
                @csrf
            <input type="text" name="filtro" placeholder="Nome" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button class="submit" class="btn btn-dark">Filtrar</button>
            </form>
        </div> --}}
        <div class="card-body">
            <table class="table table-condensed">
                <thead>
                    <th>Documentos entregues</th>                        
                    <th width="570">Ações</th>
                </thead> 
                <tbody>                        
                    @foreach ($documentosEntregues as $documento)
                        <tr>
                            <td>
                                <font color="green">
                                    {{$documento->tipo_documento}}
                                </font>
                            </td>                                
                            <td style="width=10px;">                                                                    
                                <a href="{{ route('matriculas.documentos.remover', [$matricula->id_matricula, $documento->id_tipo_documento]) }}" class="btn btn-outline-danger"><i class="fas fa-trash"></i></a> 
                            </td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">        
        <div class="card-body">
                <table class="table table-condensed">
                    <thead>
                        <th width="50px">#</th>
                        <th>Documentos NÃO Entregues</th>                        
                        <th width="570">Obrigatório</th>
                    </thead>
                    <tbody>                        
                        <form action="{{ route('matriculas.documentos.vincular', $matricula->id_matricula) }}" method="POST">
                            @csrf

                            @foreach ($documentosNaoEntregues as $documento)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="documentos[]" value={{$documento->id_tipo_documento}}>
                                    </td>
                                    <td>
                                        @if ($documento->obrigatorio == 1)
                                            <font color="red">
                                        @endif
                                        {{$documento->tipo_documento}}
                                        </font>
                                    </td>  
                                    <td>
                                        @if ($documento->obrigatorio == 1)
                                            Sim
                                        @else
                                            Não
                                        @endif
                                    </td>                              
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan=500>
                                    @include('admin.includes.alerts')

                                    <button type="submit" class="btn btn-success">Receber Documentos</button>
                                </td>
                            </tr>
                        </form>
                    </tbody>
                </table>
                
        </div>
    </div>
@stop