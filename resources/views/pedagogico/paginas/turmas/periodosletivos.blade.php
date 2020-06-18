@extends('adminlte::page')

@section('title_postfix', ' Diários de Turmas')

@section('content_header')
    <ol class="breadcrumb">       
        <li class="breadcrumb-item active" >
            <a href="{{url('pedagogico/turmas')}}" class="">Pedagógico</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{url('pedagogico/turmas')}} " class="">Diários</a>
        </li>  
        <li class="breadcrumb-item active" >
            <a href="#" class="">Controle de Lançamentos</a>
        </li>       
    </ol>              
    <h3>Turma: {{$turma->nome_turma}} {{$turma->tipoTurma->subNivelEnsino->sub_nivel_ensino}} - {{$turma->turno->descricao_turno}}</h3>
    <h4>Controle de lançamentos em Períodos Letivos</h4>
    {{-- <h3><strong>{{$user->name}}</strong>     </h3> --}}
    
    {{-- <a href="{{ route('matriculas.permissoes.add', $user->id_matricula) }}" class="btn btn-dark">ADD permissão</a></h1> --}}

@stop

@section('content')
    {{-- Unidades vinculadas --}}
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
                    <th>Períodos Letivos</th>    
                    <th>Situação</th>                        
                    <th width="470">Ações</th>
                </thead> 
                <tbody>                        
                    @foreach ($periodosLetivos as $periodoLetivo)
                        <form action="{{ route('turmas.periodosletivos.update', $periodoLetivo->id_turma_periodo_letivo) }}" method="POST">
                            @csrf
                            <tr>
                                <td>
                                    @if (isset($periodoLetivo->situacao_turma_periodo_letivo) && $periodoLetivo->situacao_turma_periodo_letivo == 1)
                                        <font color="green">
                                            {{$periodoLetivo->periodo_letivo}} 
                                        </font>
                                    @else
                                        <font color="grey">
                                            {{$periodoLetivo->periodo_letivo}}
                                        </font>
                                    @endif
                                </td>         
                                <td>                                
                                    @if (isset($periodoLetivo->situacao_turma_periodo_letivo) && $periodoLetivo->situacao_turma_periodo_letivo == 1)
                                        <input type="checkbox"  id="situacao" name="situacao" value="1" checked> 
                                    @else
                                        <input type="checkbox"  id="situacao" name="situacao" value="0"> 
                                    @endif
                                    Abrir                                 
                                </td>                                                       
                                <td style="width=10px;">       
                                    <button type="submit" class="btn btn-sm btn-outline-success"><i class="fas fa-save"></i></button>                                                                                                 
                                </td>                            
                            </tr>
                        </form>
                    @endforeach
                </tbody>
            </table>
            <div>
                <strong>Importante para a segurança dos dados:<br></strong>
                Período Letivo aberto: permite lançamento de conteúdo lecionado, frequência e avaliações.<br>
                Período Letivo fechado: bloqueia esses lançamentos, evitando alterações por engano.
            </div>

        </div>
    </div>

    {{-- Períodos não vinculados --}}
    @if (count($periodosLetivosLivres) > 0)
        <div class="card">        
            <div class="card-body">
                    <table class="table table-condensed">
                        <thead>
                            <th width="50px">#</th>
                            <th width="350px">Períodos fechados</th>                               
                        </thead>
                        <tbody>                        
                            <form action="{{ route('turmas.periodoletivo.vincular', $turma->id_turma) }}" method="POST">
                                @csrf

                                @foreach ($periodosLetivosLivres as $periodoLetivo)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="periodosLetivos[]" value={{$periodoLetivo->id_periodo_letivo}}>
                                        </td>                                   
                                        <td>
                                            {{$periodoLetivo->periodo_letivo}}    
                                        </td>                                                       
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan=500>
                                        @include('admin.includes.alerts')
                                        <button type="submit" class="btn btn-success"> Abrir Período Letivo</button>
                                    </td>
                                </tr>
                            </form>
                        </tbody>
                    </table>  
                </div>              
            </div>
        </div>
        @endif
@stop