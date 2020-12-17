@extends('adminlte::page')

@section('title_postfix', ' Recuperação Final')

@section('content_header')

    <ol class="breadcrumb"> 
        <li class="breadcrumb-item active" >
            <a href="#" class="">Pedagógico</a>
        </li>               
        <li class="breadcrumb-item active" >
            <a href="#" class="">Recuperação Final</a>
        </li>
    </ol>
    <h3>Recuperações Finais <a href="{{ route('recuperacaofinal.create')}}" class="btn btn-success"> <i class="fas fa-plus-square"></i> Cadastrar</a></h3>    
@stop

@section('content')
    <div class="container-fluid">
        @include('admin.includes.alerts')
        <div class="card-header">
            <form action="{{ '#' }}" method="POST" class="form form-inline">
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
                                     
                                <td style="width=10px;">
                                    {{-- <a href="{{ route('tiposturmas.avaliacoes.edit', $avaliacao->id_avaliacao) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>                                    
                                    <a href="{{ route('tiposturmas.avaliacoes.show', $avaliacao->id_avaliacao) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a> --}}
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
