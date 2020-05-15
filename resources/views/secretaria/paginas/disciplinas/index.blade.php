@extends('adminlte::page')

@section('title', 'Rede Educa - Disciplinas')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcamb-item">
            <a href="">Cadastrar</a> / 
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{ route('disciplinas.index') }} " class="">Disciplinas</a>
        </li>
    </ol>

    <h1>Disciplinas <a href="{{ route('disciplinas.create') }}" class="btn btn-dark">Cadastrar</a></h1>    
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <form action="{{ route('disciplinas.search') }}" method="POST" class="form form-inline">
                @csrf
            <input type="text" name="filtro" placeholder="Nome" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button class="submit" class="btn btn-dark">Filtrar</button>
            </form>
        </div>
        <div class="card-body">
                <table class="table table-condensed">
                    <thead>
                        <th>Disciplina</th>
                        <th>Sigla</th>
                        <th>Situação</th>
                        <th width="270">Ações</th>
                    </thead>
                    <tbody>                        
                        @foreach ($disciplinas as $disciplina)
                            <tr>
                                <td>
                                    {{$disciplina->disciplina}}
                                </td> 
                                <td>
                                    {{$disciplina->sigla_disciplina}}
                                </td>                   
                                <td>                                  
                                    {{-- <fieldset>
                                        <input id="on" type="checkbox" name="situacao_disciplina" checked={{$disciplina->situacao_disciplina}}>                                    
                                    </fieldset>
                                    <label for="on">
                                        <div class="chave"></div>
                                        <span class="off text">off</span>
                                        <span class="on text">on</span>
                                    </label> --}}

                                    <div class="col-sm-5">
                                        <button type="button" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                          <div class="handle"></div>
                                        </button>
                                      </div>
                                  
                                      <div class="col-sm-5">
                                        <button type="button" class="btn btn-lg btn-toggle active" data-toggle="button" aria-pressed="true" autocomplete="off">
                                          <div class="handle"></div>
                                        </button>
                                      </div>
                                      
                                </td>              
                                <td style="width=10px;">
                                    <a href="{{ route('disciplinas.edit', $disciplina->id_disciplina) }}" class="btn btn-info">Editar</a>
                                    <a href="{{ route('disciplinas.show', $disciplina->id_disciplina) }}" class="btn btn-warning">VER</a>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">
                    @if (isset($filtros))
                    {!! $disciplinas->appends($filtros)->links()!!}
                    @else
                        {!! $disciplinas->links()!!}    
                    @endif
                    
                </div>
        </div>
    </div>
@stop
