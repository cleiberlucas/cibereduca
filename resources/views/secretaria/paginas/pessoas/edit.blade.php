@extends('adminlte::page')

@section('title_postfix', ' '.$pessoa->tipoPessoa->tipo_pessoa)

@section('content_header')
    <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
    
    <div class="d-flex justify-content-between">
        <div class="p-2">
            <ol class="breadcrumb">        
                <li class="breadcrumb-item active" >           
                    <a href="{{ route('pessoas.index', $pessoa->fk_id_tipo_pessoa) }} " class=""> {{$pessoa->tipoPessoa->tipo_pessoa}} </a>        
                </li>
                <li class="breadcrumb-item active" >
                    <a href="#" class="">Editar</a>
                </li>
            </ol>
            <h1>Editar {{$pessoa->tipoPessoa->tipo_pessoa}} </h1>
        </div>
        
        <div class="p-2">
            <img src="{{url("storage/$pessoa->foto")}}" alt="" width="100" heigth="200">
        </div>
        <div class="p-2"> </div>
    </div>

    
@stop

@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.js"></script>

    <div class="container-fluid">
        <form action="{{ route('pessoas.update', $pessoa->id_pessoa)}}" class="form" method="POST" enctype="multipart/form-data">            
            @csrf
            @method('PUT')

            @include('admin.includes.alerts')

                <div class="row">    
                    <div class="form-group col-sm-4 col-xs-12">
                        {{-- TIPO PESSOA {{$pessoa->tipoPessoa->tipo_pessoa}} --}}
                        
                        @if (isset($tipo_pessoa) &&  $tipo_pessoa == 'aluno')
                            <input type="hidden" name="fk_id_tipo_pessoa" value="1">
                        @elseif (isset($tipo_pessoa) &&  $tipo_pessoa == 'responsavel')
                            <input type="hidden" name="fk_id_tipo_pessoa" value="2"> 
                        @endif
                        
                        <input type="hidden" name="fk_id_user_alteracao" value="{{Auth::id()}}">
                        <label>* Nome:</label>
                        <input type="text" name="nome" class="form-control" placeholder="Nome" required value="{{ $pessoa->nome ?? old('nome') }}" >
                    </div>
                    <div class="form-group col-sm-4 col-xs-12">
                        <label>Foto:</label>
                        <input type="file" name="foto" class="form-control">
                    </div>
                </div>

            @include('secretaria.paginas.pessoas._partials.form')
            
             {{-- Endereço apenas para responsáveis --}}
             @if ($pessoa->fk_id_tipo_pessoa == '2')
                @include('secretaria.paginas.pessoas._partials.form_endereco')    
            @endif

            <div class="row">
                <div class="form-group col-sm-12 col-xs-2"> 
                    <label for="">Observações</label>
                    <textarea class="form-control" name="obs_pessoa" id="" cols="100" rows="5">{{$pessoa->obs_pessoa ?? old('obs_pessoa')}}</textarea>
                </div>
            </div>

            {{-- Unidade de ensino somente para alunos --}}
            @if ($pessoa->fk_id_tipo_pessoa == '1')
                <div class="row">
                    <div class="form-group col-sm-6 col-xs-2"> 
                        <label for="">* Unidade de Ensino</label>
                        <select name="fk_id_unidade_ensino" id="fk_id_unidade_ensino" required  class="form-control">
                            <option value=""></option>
                            @foreach ($unidadesEnsino as $unidadeEnsino)
                                <option value="{{$unidadeEnsino->id_unidade_ensino }}"
                                    @if (isset($pessoa) && $unidadeEnsino->id_unidade_ensino == $pessoa->fk_id_unidade_ensino)
                                        selected="selected"
                                    @endif
                                    >                    
                                    {{$unidadeEnsino->nome_fantasia}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="form-group col-sm-6 col-xs-12">                
                    <label>*Situação:</label><br>
                    @if (isset($pessoa->situacao_pessoa) && $pessoa->situacao_pessoa == 1)
                        <input type="checkbox" id="situacao_pessoa" name="situacao_pessoa" value="1" checked> 
                    @else
                        <input type="checkbox" id="situacao_pessoa" name="situacao_pessoa" value="0"> 
                    @endif
                    Ativar  
                </div>
            </div> 

            <div class="row">
                <div class="form-group col-sm-2 col-xs-6">     
                    <div>
                        * Campos Obrigatórios<br>
                        <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Enviar</button>
                    </div>
                </div>
            </div>

        </form>
    </div>
@endsection