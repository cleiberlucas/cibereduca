@extends('adminlte::page')

@section('title_postfix', ' Avaliação')

@section('content_header')    
    
    <ol class="breadcrumb"> 
        <li class="breadcrumb-item active" >
            <a href="#" class="">Pedagógico</a>
        </li>       
        <li class="breadcrumb-item active" >
            <a href="{{ url('pedagogico/tiposturmas') }} " class="">Padrões de Turmas</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{ route('tiposturmas.avaliacoes', $avaliacao->fk_id_tipo_turma) }}" class="">Avaliações</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="#" class="">Ver</a>
        </li>
    </ol>
    <br>
    <h3><strong>Avaliação {{$avaliacao->tipoTurma->tipo_turma}}</strong></h3>
@stop

@section('content')
    <div class="container-fluid">
        @include('admin.includes.alerts')
        <div class="card-header">
            <ul>
                <li>
                    <strong>Período Letivo:</strong> {{$avaliacao->periodoLetivo->periodo_letivo}}
                </li>
                <li>
                    <strong>Disciplina:</strong> {{ $avaliacao->disciplina->disciplina}}
                </li>
                <li>
                    <strong>Tipo Avaliação:</strong>  {{ $avaliacao->tipoAvaliacao->tipo_avaliacao}}
                </li>
                <li>
                    <strong>Valor:</strong>  {{number_format($avaliacao->valor_avaliacao, 1, ',', '.')}}
                </li>                                
                <li><strong>Conteúdo: </strong><br>
                    {{$avaliacao->conteudo}}
                </li>
            </ul>
            
            
            <form action="{{ route('tiposturmas.avaliacoes.destroy', $avaliacao->id_avaliacao) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"> <i class="fas fa-trash"></i> Apagar</button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function(){
              $(".alert").slideDown(300).delay(5000).slideUp(300);
        });       
    </script>

@endsection