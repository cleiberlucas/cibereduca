@extends('adminlte::page')

@section('title_postfix', ' Atividades ExtraCurriculares')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" >
            <a href="{{ route('extracurriculares.index') }} " class="">Atividades ExtraCurriculares</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="#" class="">Ver</a>
        </li>
    </ol>
    <h3>Atividade ExtraCurricular - {{$extraCurricular->tipo_atividade_extracurricular}} </h3>
@stop
@section('content')
    <div class="card">
        <div class="card-header">
            <ul>
                <li>
                    <strong>Ano Letivo:</strong> {{ $extraCurricular->ano}}
                </li>
                <li><strong>Título para Contrato Matrícula: </strong>{{$extraCurricular->titulo_contrato}}</li>
                <li>
                    <strong>Valor Atividade:</strong> R$ {{ number_format($extraCurricular->valor_padrao_atividade, 2, ',', '.')}}
                </li>
                <li>
                    <strong>Valor Material:</strong> R$ {{ number_format($extraCurricular->valor_padrao_material, 2, ',', '.')}}
                </li>
                <li>
                    <strong>Situação: </strong>
                    @if ($extraCurricular->situacao_atividade == 1)
                        Ativa
                    @else
                        Inativa
                    @endif
                </li>
                <li>Cadastrado por {{$extraCurricular->name}} em {{date('d/m/Y H:i:s', strtotime($extraCurricular->data_cadastro))}}</li>
                
            </ul>
            <form action="{{ route('extracurriculares.destroy', $extraCurricular->id_tipo_atividade_extracurricular) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"> <i class="fas fa-trash"></i> Apagar</button>
            </form>
        </div>
    </div>
@endsection