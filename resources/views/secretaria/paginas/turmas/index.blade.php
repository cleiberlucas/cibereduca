

@extends('adminlte::page')



@section('title_postfix', ' Turmas')

@section('content_header')
    {{-- <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >
            <a href="{{ route('turmas.index') }} " class="">Turmas</a>
        </li>
    </ol> --}} 
    
    <div class="row">
        <div class="form-group col-sm-9 col-sx-2">
            <h3>Turmas <a href="{{ route('turmas.create') }}" class="btn btn-success"> <i class="fas fa-plus-square"></i> Cadastrar</a></h3>    
            {{-- <h3><a href="{{ route('matriculas.gerarusertodos') }}" class="btn btn-success"> <i class="fas fa-plus-square"></i> Gerar senhas</a></h3>     --}}
        </div>
        <div class="form-group col-sm-3 col-sx-2">
            @if (isset($totalMatriculas) and $totalMatriculas > 0)
                @if (isset($filtros))
                    <b>Total matrículas {{$filtros['filtro']}}: {{ $totalMatriculas}}</b>    
                @else
                    <b>Total matrículas <?php echo date('Y');?>: {{ $totalMatriculas}}</b>    
                @endif
                
            @endif            
        </div>
    </div>
@stop

@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
    <div class="container-fluid">
        <div class="card-header">
            <form action="{{ route('turmas.search') }}" method="POST" class="form form-inline">
                @csrf
                <input type="text" name="filtro" placeholder="Turma" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
            </form>
        </div>
        @include('admin.includes.alerts')
        <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <th>#</th>
                        <th>Ano</th>                        
                        <th>Padrão Turma</th>
                        <th>Turma</th>                        
                        <th>Turno</th>
                        <th>Matrículas</th>                        
                        <th>Vagas</th>                        
                        <th>Localização</th>                        
                        <th >Ações</th>
                    </thead>
                    <tbody>                        
                        @foreach ($turmas as $index => $turma)
                            <tr>
                                <th scope="row">{{$index+1}}</th>
                                <td>
                                    {{$turma->tipoTurma->anoLetivo->ano}}
                                </td>
                                <td>
                                    {{$turma->tipoTurma->tipo_turma}}<br>{{$turma->tipoTurma->subNivelEnsino->sub_nivel_ensino}}
                                </td> 
                                <td>
                                    <a href="{{ route('matriculas.index', $turma->id_turma) }}" class="btn btn-link">{{$turma->nome_turma}}</a>                                    
                                </td>                                  
                                <td>
                                    {{$turma->turno->descricao_turno}}
                                </td>                                
                                <td>
                                    {{$turma->limite_alunos - $quantVagas[$turma->id_turma]}}
                                </td> 
                                <td>
                                    {{$quantVagas[$turma->id_turma]}}
                                </td>                                
                                <td>
                                    {{$turma->localizacao}}
                                </td>                                    
                                <td style="width=10px;">
                                    <a href="{{ route('matriculas.index', $turma->id_turma) }}" data-content="Matrículas" data-toggle="popover" data-trigger="hover"  class="btn btn-sm btn-outline-success"><i class="fas fa-users"></i></a>
                                    <a href="{{ route('turmas.edit', $turma->id_turma) }}" data-content="Editar Turma" data-toggle="popover" data-trigger="hover"  class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('turmasprofessor', $turma->id_turma) }}" data-content="Docentes" data-toggle="popover" data-trigger="hover"  class="btn btn-sm btn-outline-warning"><i class="far fa-user"></i></a>
                                    <a href="{{ route('turmas.show', $turma->id_turma) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
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
        $('[data-toggle="popover"]').popover(); 
    </script>
@stop
