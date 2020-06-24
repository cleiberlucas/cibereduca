

@extends('adminlte::page')

<section></section>

@section('title_postfix', ' Avaliações')

@section('content_header')
    <ol class="breadcrumb"> 
        <li class="breadcrumb-item active" >
            <a href="#" class="">Pedagógico</a>
        </li>       
        <li class="breadcrumb-item active" >
            <a href="{{ url('pedagogico/tiposturmas') }} " class="">Padrões de Turmas</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="#" class="">Avaliações</a>
        </li>
    </ol>
    
    <h1>Avaliações <a href="{{ route('tiposturmas.avaliacao.create', $tipoTurma) }}" class="btn btn-success"> <i class="fas fa-plus-square"></i> Cadastrar</a></h1>    
@stop

@section('content')
    <div class="container-fluid">
        <div class="card-header">
            {{-- <form action="{{ route('turmas.search') }}" method="POST" class="form form-inline">
                @csrf
                <input type="text" name="filtro" placeholder="Turma" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
            </form> --}}
        </div>
        <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <th>#</th>                        
                        <th>Padrão Turma</th>
                        <th>Período Letivo</th>                        
                        <th>Disciplina</th>
                        <th>Avaliação</th>
                        <th>Valor</th>                        
                        <th >Ações</th>
                    </thead>
                    <tbody>                        
                        @foreach ($avaliacoes as $index => $avaliacao)
                            <tr>
                                <th scope="row">{{$index+1}}</th>                                
                                <td>
                                    {{$avaliacao->tipoTurma->tipo_turma}} {{$avaliacao->tipoTurma->subNivelEnsino->sub_nivel_ensino}}
                                </td>
                                <td>
                                    {{$avaliacao->periodoLetivo->periodo_letivo}}
                                </td> 
                                <td>
                                    {{$avaliacao->disciplina->disciplina}}
                                </td>                                  
                                <td>
                                    {{$avaliacao->tipoAvaliacao->tipo_avaliacao}}
                                </td>
                                <td>
                                    {{$avaliacao->valor_avaliacao}}
                                </td> 
                                     
                                <td style="width=10px;">
                                    <a href="{{ route('tiposturmas.avaliacoes.edit', $avaliacao->id_avaliacao) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>                                    
                                    <a href="{{ route('tiposturmas.avaliacoes.show', $avaliacao->id_avaliacao) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">
                    @if (isset($filtros))
                    {!! $avaliacoes->appends($filtros)->links()!!}
                    @else
                        {!! $avaliacoes->links()!!}    
                    @endif
                    
                </div>
        </div>
    </div>
@stop
