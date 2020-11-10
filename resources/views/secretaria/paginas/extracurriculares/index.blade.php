
@extends('adminlte::page')

@section('title_postfix', ' Atividades ExtraCurriculares')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" >
            <a href="{{ route('extracurriculares.index') }} " class="">Atividades ExtraCurriculares</a>
        </li>
    </ol>
    
    <div class="row">
        <div class="form-group col-sm-9 col-sx-2">
            <h3>Atividades ExtraCurriculares <a href="{{ route('extracurriculares.create') }}" class="btn btn-success"> <i class="fas fa-plus-square"></i> Cadastrar</a></h3>    
        </div>        
    </div>
@stop

@section('content')
    <div class="container-fluid">
        
        @include('admin.includes.alerts')
        {{-- <div class="card-header">
            <form action="{{ route('extracurriculares.search') }}" method="POST" class="form form-inline">
                @csrf
                <input type="text" name="filtro" placeholder="Turma" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
            </form>
        </div> --}}
        <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <th>#</th>
                        <th>Ano</th>                        
                        <th>Atividade</th>
                        <th>Valor Padrão R$</th>                        
                        <th>Situação</th>                        
                        <th>Ações</th>
                    </thead>
                    <tbody>                        
                        @foreach ($extraCurriculares as $index => $extraCurricular)
                            <tr>
                                <th scope="row">{{$index+1}}</th>
                                <td>
                                    {{$extraCurricular->ano}}
                                </td>
                                <td>
                                    {{$extraCurricular->tipo_atividade_extracurricular}}
                                </td>                                 
                                <td>
                                    {{ number_format($extraCurricular->valor_padrao_atividade, 2, ',', '.')}}
                                </td>                                                                
                                <td>
                                    @if ($extraCurricular->situacao_atividade == 1)
                                        Ativa
                                    @else
                                        Inativa
                                    @endif
                                </td>                                    
                                <td style="width=10px;">                                    
                                    <a href="{{ route('extracurriculares.edit', $extraCurricular->id_tipo_atividade_extracurricular) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>                                    
                                    <a href="{{ route('extracurriculares.show', $extraCurricular->id_tipo_atividade_extracurricular) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                                </td>                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">
                    @if (isset($filtros))
                    {!! $extraCurriculares->appends($filtros)->links()!!}
                    @else
                        {!! $extraCurriculares->links()!!}    
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
