

@extends('adminlte::page')

<section></section>

@section('title_postfix', ' Pessoas')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcamb-item">
            <a href="">Cadastrar</a> / 
        </li>
        <li class="breadcrumb-item active" >
            
            <a href="{{ route('pessoas.index', 1) }} " class=""> 
                @if ($tipo_pessoa == 1)
                    Alunos
                @else
                    Responsável
                @endif
            </a>
        </li>
    </ol>
    
    <h1> @if ($tipo_pessoa == 1)
            Alunos
            <a href="{{ route('pessoas.create.aluno') }}" class="btn btn-dark">Cadastrar</a></h1>    
        @else
            Responsável
            <a href="{{ route('pessoas.create.responsavel') }}" class="btn btn-dark">Cadastrar</a></h1>    
        @endif
    
@stop

@section('content')
    <div class="container-fluid">
        <div class="card-header">
            <form action="{{ route('pessoas.search') }}" method="POST" class="form form-inline">
                @csrf
                <input type="text" name="filtro" placeholder="Nome" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <input type="hidden" name="tipo_pessoa" value="{{$tipo_pessoa}}">
                <button class="submit" class="btn btn-dark">Filtrar</button>
            </form>
        </div>
        <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <th scope="col">#</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Data Nascimento</th>
                        <th scope="col">Telefone</th>
                        <th scope="col">Situação</th>
                        <th width="270">Ações</th>
                    </thead>
                    <tbody>                        
                        @foreach ($pessoas as $index => $pessoa)
                        
                            <tr>
                                <th scope="row">{{$index+1}}</th>
                                <td>
                                    {{$pessoa->nome}}
                                </td> 
                                <td>
                                    {{$pessoa->data_nascimento}}
                                </td>
                                <td>
                                    {{$pessoa->telefone_1}}
                                </td>                   
                                <td>
                                    @if ($pessoa->situacao_pessoa == 1)
                                        <b>Ativo</b>
                                    @else
                                        Inativo                                        
                                    @endif
                                    
                                </td>              
                                <td >
                                    <a href="{{ route('pessoas.edit', $pessoa->id_pessoa) }}" class="btn btn-sm btn-primary">Editar</a>
                                    <a href="{{ route('pessoas.show', $pessoa->id_pessoa) }}" class="btn btn-sm btn-warning">VER</a>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">
                    @if (isset($filtros))
                    {!! $pessoas->appends($filtros)->links()!!}
                    @else
                        {!! $pessoas->links()!!}    
                    @endif
                    
                </div>
        </div>
    </div>
@stop
