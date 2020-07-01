

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
    @foreach ($avaliacoes as $index => $avaliacao)
        @if ($index == 0)
            <h3>{{$avaliacao->tipo_turma}} {{$avaliacao->sub_nivel_ensino}}</h3>
            @break;
        @endif        
    @endforeach
    <h3>Avaliações <a href="{{ route('tiposturmas.avaliacao.create', $tipoTurma) }}" class="btn btn-success"> <i class="fas fa-plus-square"></i> Cadastrar</a></h3>    
@stop

@section('content')
    <div class="container-fluid">
        @include('admin.includes.alerts')
        <div class="card-header">
            <form action="{{ route('tiposturmas.avaliacoes.search') }}" method="POST" class="form form-inline">
                @csrf
                <input type="hidden" name="id_tipo_turma" value="{{$tipoTurma}}">
                <input type="text" name="filtro" placeholder="Disciplina" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
            </form>
        </div>
       
        <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <th>#</th>                                                
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
                                    {{$avaliacao->periodo_letivo}}
                                </td> 
                                <td>
                                    {{$avaliacao->disciplina}}
                                </td>                                  
                                <td>
                                    {{$avaliacao->tipo_avaliacao}}
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

    <script>
        $(document).ready(function(){
              $(".alert").slideDown(300).delay(5000).slideUp(300);
        });       
    </script>
@stop
