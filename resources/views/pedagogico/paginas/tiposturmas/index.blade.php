

@extends('adminlte::page')



@section('title_postfix', ' Cadastrar Avaliações')

@section('content_header')
    <ol class="breadcrumb"> 
        <li class="breadcrumb-item active" >
            <a href="#" class="">Pedagógico</a>
        </li>       
        <li class="breadcrumb-item active" >
            <a href="{{ url('pedagogico/tiposturmas/avaliacoes') }} " class="">Padrões de Turmas</a>
        </li>
    </ol>
    <h3>Avaliações</h3>
@stop

@section('content')
    <div class="container-fluid">        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <th>Ano</th>
                    <th>Padrão de Turma</th>
                    <th>Nível de Ensino</th>                        
                    <th width="200">Avaliações</th>
                </thead>
                <tbody>                        
                    @foreach ($tiposTurmas as $tipoTurma)
                        <tr>
                            <td>
                                {{$tipoTurma->anoLetivo->ano}}
                            </td> 
                            <td>
                                <a href="{{route('tiposturmas.avaliacoes', $tipoTurma->id_tipo_turma)}}">{{$tipoTurma->tipo_turma}}</a>                                
                            </td>                   
                            <td>
                                {{$tipoTurma->subNivelEnsino->sub_nivel_ensino}}
                            </td>                                                   
                            <td style="width=10px;">
                                 <a href="{{route('tiposturmas.avaliacoes', $tipoTurma->id_tipo_turma)}}" class="btn btn-sm btn-outline-success"><i class="fas fa-file-alt"></i></a>
                            </td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-footer">
                @if (isset($filtros))
                {!! $tiposTurmas->appends($filtros)->links()!!}
                @else
                    {!! $tiposTurmas->links()!!}    
                @endif
                
            </div>
        </div>
    </div>
@stop
