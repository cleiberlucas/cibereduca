@extends('adminlte::page')

@section('title_postfix', ' Captações')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" >
            <a href="{{ route('captacao.index') }} " class="">Captações</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="#" class="">Histórico</a>
        </li>
    </ol>    
@stop

@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.js"></script>
    
    @include('admin.includes.alerts')
    
    <h4>Histórico Captação - Situação atual: <font color="blue">{{ $captacao->tipo_negociacao}}</font></h4>
    <h6> <strong>Interessado(a): <a href="{{ route('pessoas.edit', $captacao->id_pessoa) }}" class="btn btn-link" target="_blank"> {{$captacao->nome}}</a> </strong></h6>
    <h6><b>{{$captacao->tipo_cliente}} - {{$captacao->aluno}} - Série Pretendida: {{$captacao->serie_pretendida}}</b></h6>

    {{-- <div class="card-header"> --}}
        <ul>
            <li><strong>Agendamento:</strong> 
                @if (isset($captacao->data_agenda))
                    {{ date('d/m/Y', strtotime($captacao->data_agenda))}}
                @endif
                @if (isset($captacao->hora_agenda))
                    - {{ date('H:i', strtotime($captacao->hora_agenda))}}  
                @endif
            </li> 
            <li><strong>Telefone: </strong> {{mascaraTelefone("(##) #####-####", $captacao->telefone_1)}} {{mascaraTelefone("(##) #####-####",$captacao->telefone2)}}
                - <strong>Email:</strong> {{$captacao->email_1}}  {{$captacao->email_2}}
            </li>
            
            <li>
                <strong>1° Contato:</strong>  {{ date('d/m/Y', strtotime($captacao->data_contato))}} - <strong>Motivo:</strong> {{$captacao->motivo_contato}}
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
    
        <form action="{{ route('historico.store')}}" class="form" method="POST">
            @csrf
            <input type="hidden" name="fk_id_usuario_hist_captacao" value={{Auth::id()}}>            
            <input type="hidden" name="fk_id_captacao" value={{$captacao->id_captacao}}>            
       
            <div class="row">
                <div class="form-group col-sm-4 col-xs-10">
                    <label>* Contato:</label>
                    <input type="date" name="data_contato" class="form-control" required value="{{old('data_contato') }}">
                    <div class="input-group-addon" >
                        <span class="glyphicon glyphicon-th"></span>
                    </div>            
                </div>

                <div class="form-group col-sm-4 col-xs-2">            
                    <label>* Motivo:</label>
                    <select name="fk_id_motivo_contato"  class="form-control" required>
                        <option value=""></option>
                        @foreach ($motivosContato as $motivoContato)                            
                            <option value="{{$motivoContato->id_motivo_contato}}">{{$motivoContato->motivo_contato}}</option>                            
                        @endforeach
                    </select>
                </div> 
            </div>
            <div class="row">
                <div class="form-group col-sm-12 col-xs-2">
                    <label>* Histórico:</label><br>
                    <textarea name="historico"  rows="3" class="form-control" required>{{old('observacao')}}</textarea>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-4 col-xs-2">            
                    * Campos Obrigatórios<br>
                    <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-forward"></i> Enviar novo histórico</button>            
                </div>
            </div>
        </form>
        
        {{-- Imprimindo históricos cadastrados --}}
        <div class="alert-primary text-center" role="alert">
            <strong>Histórico da Captação</strong> (mais recente acima)
        </div>
        <table class="table table-responsive table-condensed">
            <table class="table table-hover">
                <thead>
                    <th width="10%">Data</th>                        
                    <th width="25%">Motivo</th>                        
                    <th>Observações</th>                    
                    <th >Ações</th>
                </thead>
                <tbody>               
                    @foreach ($historicos as $historico)
                        <form action="{{ route('historico.update', $historico->id_historico_captacao)}}" method="POST">
                            @csrf 
                            @method('PUT')
                            <input type="hidden" name="fk_id_captacao" value={{$captacao->id_captacao}}> 
                            <tr>
                                <td>
                                    <input type="date" name="data_contato" class="form-control" required value="{{$historico->data_contato ?? old('data_contato') }}">
                                </td> 
                                <td>
                                    <select name="fk_id_motivo_contato"  class="form-control" required>
                                        <option value=""></option>
                                        @foreach ($motivosContato as $motivoContato)
                                            
                                            <option value="{{$motivoContato->id_motivo_contato}}" 
                                                @if (isset($historico) && $motivoContato->id_motivo_contato == $historico->fk_id_motivo_contato)
                                                    selected="selected"
                                                @endif
                                            >{{$motivoContato->motivo_contato}}</option>                                        
                                        @endforeach
                                    </select>                                
                                </td>  
                                <td> <textarea name="historico"  rows="3" class="form-control" required>{{$historico->historico ?? old('observacao')}}</textarea></td>
                                <td>
                                    <button type="submit" class="btn btn btn-outline-success"><i class="fas fa-save"></i></button> &nbsp&nbsp&nbsp
                                    {{-- <a href="{{ route('historico.update', $historico->id_historico_captacao) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-save"></i></a> --}}
                                    <a href="{{ route('historico.destroy', $historico->id_historico_captacao) }}" class="btn btn btn-outline-danger"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        </form>

                    @endforeach
                </tbody>
            </table>
        </table>

    </div>
    

<script>
    $(document).ready(function(){
      $(".alert").slideDown(300).delay(5000).slideUp(300);
    });  
</script>

@endsection
