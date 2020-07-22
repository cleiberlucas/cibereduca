

@extends('adminlte::page')

<section></section>

@section('title_postfix', ' Pessoas')

@section('content_header')
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >            
            <a href="{{ route('pessoas.index', $tipo_pessoa) }} " class=""> 
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
            <a href="{{ route('pessoas.create.aluno') }}" class="btn btn-success"><i class="fas fa-plus-square"></i> Cadastrar</a></h1>    
        @else
            Responsável
            <a href="{{ route('pessoas.create.responsavel') }}" class="btn btn-success"><i class="fas fa-plus-square"></i> Cadastrar</a></h1>    
        @endif
    
@stop

@section('content')
    <div class="container-fluid">
        @include('admin.includes.alerts')
        <div class="card-header">
           
            <form action="{{ route('pessoas.search') }}" method="POST" class="form form-inline">
                @csrf
                <input type="text" name="filtro" placeholder="Nome" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <input type="hidden" name="tipo_pessoa" value="{{$tipo_pessoa}}">
                <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
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
                                    {{-- ALUNO - link p pasta do aluno --}}
                                    @if ($tipo_pessoa == 1)
                                    <a href="{{ route('matriculas.pasta', $pessoa->id_pessoa) }}" class="btn btn-sm btn-link"> {{$pessoa->nome}}</a>    
                                    {{-- RESPONSAVEL - link p editar registro --}}
                                    @else
                                        <a href="{{ route('pessoas.edit', $pessoa->id_pessoa) }}" class="btn btn-link">{{$pessoa->nome}}</a>
                                    @endif
                                    
                                </td> 
                                <td>
                                    {{date('d/m/Y', strtotime($pessoa->data_nascimento))}}
                                </td>
                                <td>
                                    {{mascaraTelefone('(##) #####-####', $pessoa->telefone_1)}}
                                </td>                   
                                <td>
                                    @if ($pessoa->situacao_pessoa == 1)
                                        <b>Ativo</b>
                                    @else
                                        Inativo                                        
                                    @endif
                                    
                                </td>              
                                <td >
                                    {{-- Link para pasta do aluno --}}
                                    @if ($tipo_pessoa == 1)
                                        <a href="{{ route('matriculas.pasta', $pessoa->id_pessoa) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-archive"></i></a>    
                                    @endif
                                    
                                    <a href="{{ route('pessoas.edit', $pessoa->id_pessoa) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('pessoas.show', $pessoa->id_pessoa) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
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

    <script>
        $(document).ready(function(){
              $(".alert").slideDown(300).delay(5000).slideUp(300);
        });    
    </script>
    
@stop
