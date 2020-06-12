@extends('adminlte::page')

@section('title_postfix', ' '.$pessoa->tipoPessoa->tipo_pessoa)

@section('content_header')
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
    <div class="container-fluid">
        <form action="{{ route('pessoas.update', $pessoa->id_pessoa)}}" class="form" method="POST" enctype="multipart/form-data">            
            @csrf
            @method('PUT')
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
                        <label for="">Unidade de Ensino</label>
                        <select name="fk_id_unidade_ensino" id="fk_id_unidade_ensino" class="form-control">
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