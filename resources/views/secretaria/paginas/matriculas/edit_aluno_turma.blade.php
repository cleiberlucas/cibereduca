@extends('adminlte::page')

@section('title_postfix', ' Turma')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div class="p-2">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" >
                    <a href="{{ route('turmas.index') }} " class="">Turmas</a>
                </li>
                <li class="breadcrumb-item active" >
                    <a href="{{route('matriculas.index', $matricula->fk_id_turma)}}" class="">Matrículas</a>
                </li>
                <li class="breadcrumb-item active" >
                    <a href="#" class="">Editar Turma Aluno</a>
                </li>
            </ol>
            
            <h3><strong>Alteração da Turma do Aluno  </strong> {{$matricula->nome_aluno}}</h3>
            <h3>Turma atual: {{$matricula->nome_turma}} - {{$matricula->descricao_turno}}</h3>            

        </div>
        <div class="p-2">
            @if (isset($matricula->foto))
                <img src="{{url("storage/$matricula->foto")}}" alt="" width="100" heigth="200">
            @endif
        </div>
        <div class="p-2"> </div>
    </div>


@stop

@section('content')
    @include('admin.includes.alerts')
    <div class="card">
        <form action="{{ route('matriculas.update.turma', $matricula->id_matricula)}}" class="form" method="POST">
            @csrf
            @method('PUT')
                        
            <input type="hidden" name="fk_id_user_altera" value={{Auth::id()}}>
            
            <div class="row">
                <div class="form-group col-sm-5 col-xs-1">
                    <label for="">Nova Turma</label>
                    <select class="form-control" name="fk_id_turma" id="fk_id_turma" required>
                        <option value=""></option>
                        @foreach ($turmas as $turma)
                            <option value="{{$turma->id_turma}}">{{$turma->nome_turma}} - {{$turma->descricao_turno}}</option>                            
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-4 col-xs-2">                              
                    <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Enviar</button>
                </div>
            </div>

        </form>
    </div>        
    
<script>
    $(document).ready(function(){
          $(".alert").slideDown(300).delay(5000).slideUp(300);
    });    
</script>

@endsection
