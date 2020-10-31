@extends('adminlte::page')

@section('title_postfix', ' '.$tipo_pessoa )

@section('content_header')

    <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>

    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >   
            @if ($tipo_pessoa == 'aluno')
                <a href="{{ route('pessoas.index', 1) }} " class=""> Aluno </a>
            @else
            <a href="{{ route('pessoas.index', 2) }} " class=""> Responsável </a>
            @endif                 
        </li>
        <li class="breadcrumb-item active" >
            <a href="#" class="">Novo</a>
        </li>
    </ol>

    <h1>Cadastrar 
        @if ($tipo_pessoa == 'aluno')
            Aluno
        @else
            Responsável
        @endif
        
    </h1>
@stop

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.js"></script>

<script src="/js/utils.js"></script>

    <div class="container-fluid">
        <form action="{{ route('pessoas.store')}}" class="form" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="container-fluid">

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
                        <input type="text" name="nome" maxlength="100" class="form-control" placeholder="Nome" required value="{{ $pessoa->nome ?? old('nome') }}" onblur="getPessoa(this.value);">
                    </div>
                    <div class="form-group col-sm-4 col-xs-12">
                        <label>Foto:</label>
                        <input type="file" name="foto" class="form-control">
                    </div>
                </div>

                @include('secretaria.paginas.pessoas._partials.form')
                <input type="hidden" name="fk_id_user_cadastro" value="{{Auth::id()}}">
                {{-- Endereço apenas para responsáveis --}}
                @if ($tipo_pessoa == 'responsavel')
                    <div class="row">
                        <div class="form-group col-sm-4 col-xs-2"> 
                            <label for="">Profissão</label>
                            <input type="text" name="profissao" class="form-control" maxlength="100" placeholder="Profissão" value="{{ $pessoa->profissao ?? old('profissao') }}">
                        </div>
                        <div class="form-group col-sm-4 col-xs-2"> 
                            <label for="">Empresa</label>
                            <input type="text" name="empresa" class="form-control" maxlength="150" placeholder="Empresa" value="{{ $pessoa->empresa ?? old('empresa') }}">
                        </div>
                    </div>

                    @include('secretaria.paginas.pessoas._partials.form_endereco')    
                @endif

                <div class="row">
                    <div class="form-group col-sm-12 col-xs-2"> 
                        <label for="">Observações</label>
                        <textarea class="form-control" name="obs_pessoa" id="" cols="100" rows="5">{{$pessoa->obs_pessoa ?? old('obs_pessoa')}}</textarea>
                    </div>
                </div>

                {{-- Unidade de ensino somente para alunos --}}
                @if ($tipo_pessoa == 'aluno')
                    <div class="row">
                        <div class="form-group col-sm-6 col-xs-2"> 
                            <label for="">* Unidade de Ensino</label>
                            <select name="fk_id_unidade_ensino" id="fk_id_unidade_ensino" required class="form-control">
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
                            <input type="checkbox" id="situacao_pessoa" name="situacao_pessoa" value="1" checked="checked"> 
                        @else
                            <input type="checkbox" id="situacao_pessoa" name="situacao_pessoa" value="0"> 
                        @endif
                        Ativar  
                    </div>
                </div>   

                <div class="row">
                    <div class="form-group col-sm-4 col-xs-6">     
                        <div>
                            * Campos Obrigatórios<br>
                            <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Enviar</button>
                        </div>
                    </div>
                </div>
            </div>    
        </form>
    </div>

@endsection