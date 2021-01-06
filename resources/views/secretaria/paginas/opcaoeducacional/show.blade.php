@extends('adminlte::page')

@section('title_postfix', ' Captação')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" >
            <a href="{{ route('captacao.index') }} " class="">Captações</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="#" class="">Dados da Captação</a>
        </li>
    </ol>

@stop

@section('content')
    <div class="container-fluid">
        <div class="card-header">            
            <h4>Captação {{$captacao->ano}}</h4>
            <h6> <strong>Interessado(a): <a href="{{ route('pessoas.edit', $captacao->id_pessoa) }}" class="btn btn-link" target="_blank"> {{$captacao->nome}}</a> </strong></h6>
            <h6><b>{{$captacao->tipo_cliente}} - {{$captacao->aluno}} - Série Pretendida: {{$captacao->serie_pretendida}}</b></h6>
        </div>
        <div class="card-body">
            <ul>
                <li><strong>Agendamento:</strong> 
                        @if (isset($captacao->data_agenda))
                            {{ date('d/m/Y', strtotime($captacao->data_agenda))}}
                        @endif
                        @if (isset($captacao->hora_agenda))
                            - {{ date('H:i', strtotime($captacao->hora_agenda))}}  
                        @endif
                </li>  
                <li><strong>Telefone: </strong> {{mascaraTelefone("(##) #####-####", $captacao->telefone_1)}} {{mascaraTelefone("(##) #####-####",$captacao->telefone_2)}}
                </li>
                <li><strong>Email:</strong> {{$captacao->email_1}}  {{$captacao->email_2}}</li>
                <li>
                    <strong>Motivo Contato:</strong> {{$captacao->motivo_contato}}
                </li>
                <li>
                    <strong>Situação:</strong> {{ $captacao->tipo_negociacao}} 
                </li>
                <li>
                    <strong>1° Contato:</strong>  {{ date('d/m/Y', strtotime($captacao->data_contato))}}
                </li>
                <li>
                    <strong>Como conheceu a escola:</strong> {{ $captacao->tipo_descoberta}}
                </li>    
                <li><strong>Valores negociados:</strong>
                <br>
                <strong>Matrícula:</strong> R$ {{number_format($captacao->valor_matricula, '2', ',', '.')}}
                &nbsp;&nbsp;&nbsp;<strong>Curso:</strong> R$ {{number_format($captacao->valor_curso, '2', ',', '.')}}
                &nbsp;&nbsp;&nbsp;<strong>Material didático:</strong> R$ {{number_format($captacao->valor_material_didatico, '2', ',', '.')}}
                <br>
                <strong>Bilíngue:</strong> R$ {{number_format($captacao->valor_bilingue, '2', ',', '.')}}
                &nbsp;&nbsp;&nbsp;<strong>Robótica:</strong> R$ {{number_format($captacao->valor_robotica, '2', ',', '.')}}
                </li>                            
                
                <li><strong>Observações</strong> {{$captacao->observacao}}</li>
                <li>
                    Cadastrado por: {{$captacao->name}} em {{ date('d/m/Y', strtotime($captacao->data_cadastro))}}
                </li>                
            </ul>
            <hr>

            <div class="alert-dark text-center" role="alert">
                <strong>Histórico</strong>
              </div>
            <table class="table table-responsive table-condensed">
                <table class="table table-hover">
                    <thead>
                        <th>#</th>
                        <th >Data</th>                        
                        <th >Motivo</th>                        
                        <th>Observações</th>                                            
                    </thead>
                    <tbody>               
                        @foreach ($historicos as $index => $historico)                            
                            <tr>
                                <td>{{$index+1}}</td>
                                <td>
                                    {{date('d/m/Y', strtotime($historico->data_contato))}}
                                </td> 
                                <td>
                                    {{$historico->motivo_contato}}
                                </td>  
                                <td> {{$historico->historico }}                                
                            </tr>                                
                        @endforeach
                    </tbody>
                </table>
            </table>
            {{-- <form action="{{ route('captacao.destroy', $captacao->id_captacao) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"> <i class="fas fa-trash"></i> Apagar</button>
            </form> --}}        
        </div>
    </div>
@endsection