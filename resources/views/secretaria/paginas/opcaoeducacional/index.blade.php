@extends('adminlte::page')

@section('title_postfix', ' Opção Educacional')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcamb-item">
            <a href="">Opção Educacional</a>
        </li>
    </ol>
    
    <h3>Opção Educacional <a href="{{ route('opcaoeducacional.create') }}" class="btn btn-success"><i class="fas fa-plus-square"></i> Cadastrar</a></h3>
@stop

@section('content')

    @include('admin.includes.alerts')
    
    <div class="card">
        <div class="card-header">
            <form action="{{ route('opcaoeducacional.search') }}" method="POST" class="form form-inline">
                @csrf
            <input type="text" name="filtro" size="30" placeholder="Informe responsável ou aluno" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button type="submit"class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
            </form>
        </div>
        <div class="card-body">
            <table class="table table-responsive table-condensed table-hover">
                
                <thead>
                    <th>#</th>
                    <th>Ano Letivo</th>                        
                    <th>Aluno/Responsável</th>                                            
                    <th>Turma</th>
                    <th>Opção Educacional</th>                                 
                    <th>Ações</th>
                </thead>
                <tbody>   
                    @foreach ($opcoesEducacionais as $index => $opcaoEducacional)                                
                        <tr>                        
                            <td>{{$index+1}}</td>
                            <td>
                                {{$opcaoEducacional->ano}}
                            </td> 
                            <td>
                                {{$opcaoEducacional->aluno}}
                                <br>
                                {{$opcaoEducacional->responsavel}}
                            </td>                                               
                            <td>{{$opcaoEducacional->nome_turma}}</td>     
                            <td>
                            @if ($opcaoEducacional->opcao_educacional == 1)
                                Híbrido
                            @elseif ($opcaoEducacional->opcao_educacional == 2)
                                Remoto
                            @endif    
                            </td>                       
                            <td>                                
                                <a href="{{ route('opcaoeducacional.edit', $opcaoEducacional->id_opcao_educacional) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>
                                <a href="{{ route('opcaoeducacional.print', $opcaoEducacional->id_opcao_educacional) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-print"></i></a>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="{{ route('opcaoeducacional.destroy', $opcaoEducacional->id_opcao_educacional) }}" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></a>
                            </td>
                            
                        </tr>
                    @endforeach
                </tbody>
                
            </table>
            <div class="card-footer">
                @if (isset($filtros))
                {!! $opcoesEducacionais->appends($filtros)->links()!!}
                @else
                    {!! $opcoesEducacionais->links()!!}    
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
