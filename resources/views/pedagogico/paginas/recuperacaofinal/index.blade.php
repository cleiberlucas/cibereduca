@extends('adminlte::page')

@section('title_postfix', ' Recuperação Final')

@section('content_header')

    <ol class="breadcrumb"> 
        <li class="breadcrumb-item active" >
            <a href="#" class="">Pedagógico</a>
        </li>               
        <li class="breadcrumb-item active" >
            <a href="{{ route('recuperacaofinal.index') }}" class="">Recuperação Final</a>
        </li>
    </ol>
    <h3>Recuperações Finais <a href="{{ route('recuperacaofinal.create')}}" class="btn btn-success"> <i class="fas fa-plus-square"></i> Cadastrar</a></h3>    
@stop

@section('content')
    <div class="container-fluid">
        @include('admin.includes.alerts')
        <div class="card-header">
            <form action="{{ route('recuperacaofinal.search') }}" method="POST" class="form form-inline">
                @csrf                
                <input type="text" name="filtro" placeholder="Nome Aluno" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
            </form>
        </div>
       
        <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <th>#</th>                                                
                        <th>Ano Letivo</th>                        
                        <th>Aluno</th>
                        <th>Turma</th>
                        <th>Disciplina</th>                        
                        <th>Nota</th>                        
                        <th>Data</th>
                        <th >Ações</th>
                    </thead>
                    <tbody>                        
                        @foreach ($recuperacoesFinais as $index => $recuperacaoFinal)
                            <tr>
                                <th scope="row">{{$index+1}}</th>                                                                
                                <td>
                                    {{$recuperacaoFinal->ano}}
                                </td> 
                                <td>
                                    {{$recuperacaoFinal->nome}}
                                </td>                           
                                <td>
                                    {{$recuperacaoFinal->nome_turma}}
                                    </td>       
                                <td>
                                    {{$recuperacaoFinal->disciplina}}
                                </td>
                                <td>
                                    {{number_format($recuperacaoFinal->nota, 1, ',', '.')}}                                    
                                </td> 
                                <td>
                                @if (isset($recuperacaoFinal->data_aplicacao))
                                    {{date('d/m/Y', strtotime($recuperacaoFinal->data_aplicacao)) }}</td>
                                @endif                                     
                                <td style="width=10px;">
                                    <a href="{{ route('recuperacaofinal.edit', $recuperacaoFinal->id_recuperacao_final) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>                                    
                                    <a href="{{ route('recuperacaofinal.destroy', $recuperacaoFinal->id_recuperacao_final) }} " class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></a>
                                </td>                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">
                    @if (isset($filtros))
                    {!! $recuperacoesFinais->appends($filtros)->links()!!}
                    @else
                        {!! $recuperacoesFinais->links()!!}    
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
