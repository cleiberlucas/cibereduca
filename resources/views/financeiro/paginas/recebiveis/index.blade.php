@extends('adminlte::page')

@section('title_postfix', ' Pessoas')

@section('content_header')
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >            
            <a href="#" class=""> Financeiro</a>
        </li>
        <li class="breadcrumb-item active" >            
            <a href="{{ route('financeiro.index') }} " class=""> Recebíveis</a>
        </li>
    </ol>    
@stop

@section('content')
    <div class="container-fluid">
        @include('admin.includes.alerts')
        <div class="card-header">
           
            <form action="{{ route('recebivel.aluno.search') }}" method="POST" class="form form-inline">
                @csrf
                <input type="text" name="filtro" placeholder="Nome" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">                
                <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <th scope="col">#</th>
                    <th scope="col">Aluno(a)</th>                        
                    <th scope="col">Situação</th>
                    {{-- <th scope="col">Ações</th> --}}
                </thead>
                <tbody>              
                    
                    @foreach ($pessoas as $index => $pessoa)
                    
                        <tr>
                            <th scope="row">{{$index+1}}</th>
                            <td>
                                {{-- ALUNO - link p pasta do aluno --}}                                    
                                <a href="{{ route('financeiro.indexAluno', $pessoa->id_pessoa) }}" class="btn btn-sm btn-link"> {{$pessoa->nome}}</a>    
                            </td>                                 
                            <td>
                                @if ($pessoa->situacao_pessoa == 1)
                                    <b>Ativo</b>
                                @else
                                    Inativo                                        
                                @endif                                    
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
