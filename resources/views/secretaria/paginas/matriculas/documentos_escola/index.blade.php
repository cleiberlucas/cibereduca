@extends('adminlte::page')

@section('title_postfix', ' Documentos')

@section('content_header')
    @foreach ($documentosEscola as $index => $documento)
        @if ($index == 0)    
    
            <ol class="breadcrumb">        
                <li class="breadcrumb-item active" >           
                    <a href="{{ route('pessoas.index', 1) }} " class=""> Alunos </a>        
                </li>
                <li class="breadcrumb-item active" >
                    <a href="{{route('matriculas.pasta', $documento->matricula->fk_id_aluno) }}" class="">Arquivo do Aluno</a>
                </li >
                <li class="breadcrumb-item active" >
                    <a href="#" class="href">Documentos Gerados</a>
                </li>
            </ol>

            <div class="row">       
                
                <div class="d-flex justify-content-between">
                    <div class="p-2">  
                        <h3>Documentos gerados Aluno(a)</h3>                          
                        <h3><b>{{$documento->matricula->aluno->nome}}</b></h3>
                    </div>
                    <div class="p-2">
                        <img src="{{url("storage/".$documento->matricula->aluno->foto)}}" alt="" width="100" heigth="200">
                    </div>
                    <div class="p-2"> </div>
                </div>
            
            </div>
            @break;
        @endif
    @endforeach
@stop

@section('content')
    <div class="container-fluid">
        @include('admin.includes.alerts')
        {{-- <div class="card-header">
            <form action="{{ route('matriculas.search') }}" method="POST" class="form form-inline">
                @csrf
                <input type="text" name="filtro" placeholder="Aluno" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
            </form>
        </div> --}}


        <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <th>#</th>
                        <th>Ano Letivo</th>                                                
                        <th>Documento</th>
                        <th>Data</th>
                        <th>Turma </th>
                        <th>Situação</th>
                        <th >Ações</th>
                    </thead>
                    <tbody>                        
                        @foreach ($documentosEscola as $index => $documento)
                            <tr>
                                <th scope="row">{{$index+1}}</th>    
                                <td>
                                    {{$documento->matricula->turma->tipoTurma->anoLetivo->ano}}
                                </td>                            
                                <td>
                                    @if ($documento->situacao_documento == 1)
                                        <a href="{{ route('matriculas.documentos_escola.show', $documento->id_documento_escola) }}" target="_blank" class="href">{{$documento->tipoDocumentoEscola->tipo_documento}}</a>
                                    @else
                                        {{$documento->tipoDocumentoEscola->tipo_documento}}
                                    @endif                                    
                                </td>                                
                                <td>
                                    {{date('d/m/Y H:i:s', strtotime($documento->data_geracao))}}
                                </td>
                                <td>
                                    {{$documento->matricula->turma->nome_turma}}
                                </td>
                                <td>
                                    @if ($documento->situacao_documento == 1)
                                        <b> Válido</b>
                                    @else
                                        <font color="red">Inválido</font>
                                    @endif
                                </td>                                                                                  
                                                                    
                                <td style="width=10px;">                                
                                    @if ($documento->situacao_documento == 1)
                                        <a href="{{ route('matriculas.documentos_escola.show', $documento->id_documento_escola) }}" target="_blank" class="btn btn-sm btn-outline-info"><i class="fas fa-print"></i></a>                                    
                                        <a href="{{ route('matriculas.documentos_escola.invalidar', [$documento->id_documento_escola, $documento->matricula->fk_id_aluno])}}" class="btn btn-sm btn-outline-danger"><i class="fas fa-ban"></i></a>
                                    @else
                                        <a href="{{ route('matriculas.documentos_escola.revalidar', [$documento->id_documento_escola, $documento->matricula->fk_id_aluno])}}" class="btn btn-sm btn-outline-success"><i class="far fa-check-circle"></i></a>
                                    @endif

                                    {{-- <a href="{{ route('matriculas.edit', $documento->id_matricula) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('matriculas.documentos', $documento->id_matricula) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-tasks"></i></a>                                                                                                       
                                    <a href="{{ route('matriculas.pasta', $documento->fk_id_aluno) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-archive"></i></a>  --}}
                                    
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">
                    @if (isset($filtros))
                    {!! $documentosEscola->appends($filtros)->links()!!}
                    @else
                        {!! $documentosEscola->links()!!}    
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
