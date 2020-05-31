

@extends('adminlte::page')

<section></section>

@section('title_postfix', ' Atendimento Especializado')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcamb-item">
            <a href="">Cadastrar</a> / 
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{ route('atendimentosespecializados.index') }} " class="">Atendimento Especializado</a>
        </li>
    </ol>
    
    <h1>Atendimento Especializado <a href="{{ route('atendimentosespecializados.create') }}" class="btn btn-success"><i class="fas fa-plus-square"></i> Cadastrar</a></h1>    
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <form action="{{ route('atendimentosespecializados.search') }}" method="POST" class="form form-inline">
                @csrf
            <input type="text" name="filtro" placeholder="Nome" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button type="submit"class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
            </form>
        </div>
        <div class="card-body">
                <table class="table table-condensed">
                    <thead>
                        <th>Atendimento Especializado</th>                        
                        <th width="270">Ações</th>
                    </thead>
                    <tbody>                        
                        @foreach ($atendimentosEspecializados as $atendimentoEspecializado)
                            <tr>
                                <td>
                                    {{$atendimentoEspecializado->atendimento_especializado}}
                                </td>                                 
                                <td style="width=10px;">
                                    <a href="{{ route('atendimentosespecializados.edit', $atendimentoEspecializado->id_atendimento_especializado) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('atendimentosespecializados.show', $atendimentoEspecializado->id_atendimento_especializado) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">
                    @if (isset($filtros))
                    {!! $atendimentosEspecializados->appends($filtros)->links()!!}
                    @else
                        {!! $atendimentosEspecializados->links()!!}    
                    @endif
                    
                </div>
        </div>
    </div>
@stop
