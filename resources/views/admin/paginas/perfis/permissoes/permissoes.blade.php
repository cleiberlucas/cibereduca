@extends('adminlte::page')

@section('title', 'Rede Educa')

@section('content_header')
    <ol class="breadcrumb">    
        <li class="breadcrumb-item active" >
            <a href="{{ route('perfis.index') }} " class="">Perfis de Usuários</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('perfis.permissoes', $perfil->id_perfil) }}">Permissões Vinculadas</a>
        </li>
    </ol>

    <h3>Permissões do Perfil <strong>{{$perfil->perfil}}</strong> 
    <a href="{{ route('perfis.permissoes.add', $perfil->id_perfil) }}" class="btn btn-success"><i class="fas fa-plus-square"></i> Adicionar permissão</a></h3>
    @include('admin.includes.alerts')

@stop

@section('content')
    <div class="card">
        <div class="card-header">
            {{-- <form action="{{ route('perfi s.search') }}" method="POST" class="form form-inline">
                @csrf
            <input type="text" name="filtro" placeholder="Nome" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button class="submit" class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
            </form> --}}
        </div>
        <div class="card-body">
            <table class="table table-condensed">
                <thead>
                    <th>Permissões</th>                        
                    <th width="270">Ações</th>
                </thead> 
                <tbody>                        
                    @foreach ($permissoes as $permissao)
                        <tr>
                            <td>
                                {{$permissao->permissao}} - <i>{{$permissao->descricao_permissao}}</i>
                            </td>                                
                            <td style="width=10px;">                                                                    
                                <a href="{{ route('perfis.permissoes.remover', [$perfil->id_perfil, $permissao->id_permissao]) }}" class="btn btn-sm btn-outline-danger"> <i class="fas fa-trash"></i> </a> 
                            </td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-footer">
                @if (isset($filtros))
                {!! $permissoes->appends($filtros)->links()!!}
                @else
                    {!! $permissoes->links()!!}    
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