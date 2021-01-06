@extends('adminlte::page')

@section('title_postfix', ' Opção Educacional')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" >
            <a href="{{ route('opcaoeducacional.index') }} " class="">Opção Educacional</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="#" class="">Cadastrar</a>
        </li>
    </ol>
    
@stop

@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.js"></script>
    
    <div class="card">
        <form action="{{ route('opcaoeducacional.store')}}" class="form" method="POST">
            @csrf
                  
            
            <div class="container-fluid">

            <div class="row">

                <div class="form-group col-sm-2 col-xs-2">            
                    <label>* Ano letivo:</label>
                    <select name="anoLetivo" id="anoLetivo"  class="form-control" required>
                        <option value=""></option>
                        @foreach ($anosLetivos as $anoLetivo)
                            
                            <option value="{{$anoLetivo->id_ano_letivo}}" 
                                @if (isset($opcaoEducacional) && $anoLetivo->id_ano_letivo == $opcaoEducacional->fk_id_ano_letivo)
                                    selected="selected"
                                @endif
                            >{{$anoLetivo->ano}}</option>
                            
                        @endforeach
                    </select>
                </div>
        
                <div class="form-group col-sm-4 col-xs-2">
                    <label for="">* Aluno</label>
                    <select name="fk_id_matricula" id="fk_id_matricula" class="form-control" required> 
                        <option value=""></option>
                    </select>
                </div>
                
            </div>

            @include('secretaria.paginas.opcaoeducacional._partials.form')
        </form>
    </div>
@endsection