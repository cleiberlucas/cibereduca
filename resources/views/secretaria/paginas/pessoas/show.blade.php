@extends('adminlte::page')

@section('title_postfix', ' '.$pessoa->tipoPessoa->tipo_pessoa)

@section('content_header')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>

    <div class="d-flex justify-content-between">
        <div class="p-2">
            <ol class="breadcrumb">        
                <li class="breadcrumb-item active" >           
                    <a href="{{ route('pessoas.index', $pessoa->fk_id_tipo_pessoa) }} " class=""> {{$pessoa->tipoPessoa->tipo_pessoa}} </a>        
                </li>
                <li class="breadcrumb-item active" >
                    <a href="{{ route('pessoas.edit', $pessoa->id_pessoa) }}" class="btn btn-xp btn-link">Editar</a>
                    
                </li>
            </ol>

            <h1>Ficha cadastral {{$pessoa->tipoPessoa->tipo_pessoa}} - <b>{{ $pessoa->nome}}</b></h1>
        </div>

        <div class="p-2">
            <img src="{{url("storage/$pessoa->foto")}}" alt="" width="100" heigth="200">
        </div>
        <div class="p-2"> </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <ul>                
                <li>
                    <strong>Nome:</strong> {{ $pessoa->nome}}
                </li>
                <li>
                    <strong>CPF:</strong> {{ mascaraCpfCnpj('###.###.###-##', $pessoa->cpf)}}
                </li>
                <li>
                    <strong>Documento identidade:</strong> {{ $pessoa->tipoDocIdentidade->tipo_doc_identidade ?? ''}} - {{ $pessoa->doc_identidade}}
                </li>
                <li>
                    <strong>Data Nascimento:</strong> {{date('d/m/Y', strtotime($pessoa->data_nascimento))}}
                </li>
                <li>
                    <strong>Pai:</strong> {{$pessoa->pai}}
                </li>
                <li>
                    <strong>Mãe:</strong> {{$pessoa->mae}}
                </li>
                <li>
                    <strong>Fone principal:</strong> {{ mascaraTelefone("(##) #####-####", $pessoa->telefone_1)}}
                </li>
                <li>
                    <strong>Fone:</strong> {{ mascaraTelefone("(##) #####-####", $pessoa->telefone_2)}}
                </li>
                <li>
                    <strong>Email principal:</strong> {{ $pessoa->email_1}}
                </li>
                <li>
                    <strong>Email:</strong> {{ $pessoa->email_2}}
                </li>
                {{-- Endereço somente p responsável --}}
                @if ($pessoa->fk_id_tipo_pessoa == 2)
                    <li><strong>Endereço:</strong> {{$pessoa->endereco->endereco ?? ''}}</li>
                    <li><strong>Complemento:</strong> {{$pessoa->endereco->complemento ?? ''}}</li>
                    <li><strong>Número:</strong> {{$pessoa->endereco->numero  ?? ''}}</li>
                    <li><strong>Bairro</strong> {{$pessoa->endereco->bairro  ?? ''}}</li>
                    <li><Strong>Cidade:</Strong> {{$pessoa->endereco->cidade->cidade ?? ''}}/{{$pessoa->endereco->cidade->estado->sigla ?? ''}}</li>
                    <li><strong>CEP:</strong>{{$pessoa->endereco->cep ?? ''}}</li>
                    
                @endif
                <li>
                    <strong>Situação:</strong>  
                    @if ($pessoa->situacao_pessoa == 1)
                        Ativo
                    @else
                        Inativo
                    @endif
                </li>
                <li>
                    <strong>Cadastrado por:</strong> {{ $pessoa->usuarioCadastro->name}} em {{date('d/m/Y H:m:i', strtotime($pessoa->data_cadastro))}}
                </li>
                <li>
                    <strong>Última alteração por:</strong> {{ $pessoa->usuarioAlteracao->name ?? ''}} em {{date('d/m/Y H:m:i', strtotime($pessoa->data_alteracao))}}
                </li>
                
            </ul>
            <form action="{{ route('pessoas.destroy', $pessoa->id_pessoa) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"> <i class="fas fa-trash"></i> Apagar</button>
            </form>
        </div>
    </div>
    
@endsection