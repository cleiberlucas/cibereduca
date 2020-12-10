@extends('adminlte::page')

@section('title_postfix', ' Turmas')

@section('content_header')
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >
            <a href="#" class="">Pedagógico</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="#" class="">Turmas</a>
        </li>
    </ol>
    <h4>Resultado Final</h4>    
    
@stop

@section('content')
    <div class="container-fluid">
        
        <div class="card-header">
            <form action="{{ route('resultadofinal.search') }}" method="POST" class="form form-inline">
                @csrf
                <input type="text" name="filtro" placeholder="Turma" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                
                <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
            </form>
        </div>

        <div>@include('admin.includes.alerts')</div>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <th>#</th>
                    <th>Ano</th>                                                
                    <th>Turma</th>                        
                    <th>Turno</th>                                        
                </thead>
                <tbody>                        
                    @foreach ($turmas as $index => $turma)
                        <tr>
                            <th scope="row">{{$index+1}}</th>
                            <td>
                                {{$turma->ano}}
                            </td>
                            <td>
                                <a href="{{ route('resultadofinal.index', $turma->id_turma) }}" class="btn btn-link">{{$turma->nome_turma}}</a> {{$turma->sub_nivel_ensino}}
                            </td>                                 
                            <td>
                                {{$turma->descricao_turno}}
                            </td>                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-footer"> 
                @if (isset($filtros))
                    {!! $turmas->appends($filtros)->links()!!}
                @else
                    {!! $turmas->links()!!}    
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
