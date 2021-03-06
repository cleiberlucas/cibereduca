<div class="container-fluid">

    @include('admin.includes.alerts')
    @csrf

    <div class="row">

        <div class="form-group col-sm-2 col-xs-2">            
            <label>* Ano letivo:</label>
            <select name="fk_id_ano_letivo"  class="form-control" required>
                <option value=""></option>
                @foreach ($anosLetivos as $anoletivo)
                    
                    <option value="{{$anoletivo->id_ano_letivo}}" 
                        @if (isset($captacao) && $anoletivo->id_ano_letivo == $captacao->fk_id_ano_letivo)
                            selected="selected"
                        @endif
                    >{{$anoletivo->ano}}</option>
                    
                @endforeach
            </select>
        </div>        
    
        <div class="form-group col-sm-6 col-xs-2">            
            <label>* Interessado:</label>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <a href="{{ route('pessoas.create.responsavel') }}" class="btn btn-sm btn-outline-success" target="_blank"><i class="fas fa-plus-square"></i> </a>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <a href="javascript:popularResponsaveisTodos();"  class="btn btn-sm btn-outline-primary"><i class="fas fa-sync-alt"></i> </a>
            <select name="fk_id_pessoa" id="fk_id_pessoa" class="form-control" required>
                <option value=""></option>
                @foreach ($pessoas as $pessoa)                    
                    <option value="{{$pessoa->id_pessoa}}" 
                        @if (isset($captacao) && $pessoa->id_pessoa == $captacao->fk_id_pessoa)
                            selected="selected"
                        @endif
                    >{{$pessoa->nome}}</option>                    
                @endforeach
            </select>
            
        </div> 

        <div class="form-group col-sm-4 col-xs-2">            
            <label>* Tipo Cliente:</label>
            <select name="fk_id_tipo_cliente"  class="form-control" required>
                <option value=""></option>
                @foreach ($tiposCliente as $tipoCliente)                    
                    <option value="{{$tipoCliente->id_tipo_cliente}}" 
                        @if (isset($captacao) && $tipoCliente->id_tipo_cliente == $captacao->fk_id_tipo_cliente)
                            selected="selected"
                        @endif
                    >{{$tipoCliente->tipo_cliente}}</option>
                    
                @endforeach
            </select>
        </div> 
    </div>
    
    <div class="row">
        <div class="form-group col-sm-5 col-xs-2">
            <label>Aluno:</label>
            <input type="text" name="aluno" class="form-control" value="{{ $captacao->aluno ?? old('aluno') }}">        
        </div>

        <div class="form-group col-sm-3 col-xs-2">
            <label>Série Pretendida:</label>
            <input type="text" name="serie_pretendida" class="form-control" value="{{ $captacao->serie_pretendida ?? old('serie_pretendida') }}">        
        </div>
        
        <div class="form-group col-sm-3 col-xs-2">
            <label>* Apoio psicológico ou pedagógico:</label>
            <select name="necessita_apoio" id="necessita_apoio" class="form-control" required>
                <option value=""></option>
                <option value="0" 
                    @if (isset($captacao) and $captacao->necessita_apoio == 0)
                        selected                    
                    @endif>
                Não</option>

                <option value="1"
                    @if (isset($captacao) and $captacao->necessita_apoio == 1)
                        selected                    
                    @endif>
                Sim</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-3 col-xs-2">
            <label>Agendamento para:</label>
            <input type="date" name="data_agenda" class="form-control" value="{{$captacao->data_agenda ?? old('data_agenda') }}" >               
        </div>   
        <div class="form-group col-sm-2 col-xs-2">
            <label>Horário:</label>
            <input type="time" name="hora_agenda" min="08:00" max="17:00" class="form-control" value="{{$captacao->hora_agenda ?? old('hora_agenda') }}" >                 
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-4 col-xs-2">            
            <label>* Motivo Contato:</label>
            <select name="fk_id_motivo_contato"  class="form-control" required>
                <option value=""></option>
                @foreach ($motivosContato as $motivoContato)
                    
                    <option value="{{$motivoContato->id_motivo_contato}}" 
                        @if (isset($captacao) && $motivoContato->id_motivo_contato == $captacao->fk_id_motivo_contato)
                            selected="selected"
                        @endif
                    >{{$motivoContato->motivo_contato}}</option>
                    
                @endforeach
            </select>
        </div> 
        <div class="form-group col-sm-4 col-xs-2">            
            <label>* Situação:</label>
            <select name="fk_id_tipo_negociacao"  class="form-control" required>
                <option value=""></option>
                @foreach ($tiposNegociacao as $tipoNegociacao)
                    
                    <option value="{{$tipoNegociacao->id_tipo_negociacao}}" 
                        @if (isset($captacao) && $tipoNegociacao->id_tipo_negociacao == $captacao->fk_id_tipo_negociacao)
                            selected="selected"
                        @endif
                    >{{$tipoNegociacao->tipo_negociacao}}</option>
                    
                @endforeach
            </select>
        </div> 
    </div>
    <div class="row">

        <div class="form-group col-sm-4 col-xs-10">
            <label>Data 1° Contato:</label>
            <input type="date" name="data_contato" class="form-control" value="{{ $captacao->data_contato ?? old('data_contato') }}">
            <div class="input-group-addon" >
                <span class="glyphicon glyphicon-th"></span>
            </div>            
        </div>

        
        <div class="form-group col-sm-4 col-xs-2">            
            <label>Como conheceu a escola:</label>
            <select name="fk_id_tipo_descoberta"  class="form-control">
                <option value=""></option>
                @foreach ($tiposDescoberta as $tipoDescoberta)                    
                    <option value="{{$tipoDescoberta->id_tipo_descoberta}}" 
                        @if (isset($captacao) && $tipoDescoberta->id_tipo_descoberta == $captacao->fk_id_tipo_descoberta)
                            selected="selected"
                        @endif
                    >{{$tipoDescoberta->tipo_descoberta}}</option>
                    
                @endforeach
            </select>
        </div> 

    </div>
    <strong>Valores negociados</strong>
    <div class="row">
        <div class="form-group col-sm-3 col-xs-2">
            <label>Matrícula:</label><br>
            <input type="number" name="valor_matricula" id="valor_matricula" step="0.010" class="form-control"  value="{{ $captacao->valor_matricula ?? old('valor_matricula') }}">    
        </div>
        <div class="form-group col-sm-3 col-xs-2">
            <label>Curso:</label><br>
            <input type="number" name="valor_curso" id="valor_curso" step="0.010" class="form-control"  value="{{ $captacao->valor_curso ?? old('valor_curso') }}">    
        </div>
        <div class="form-group col-sm-3 col-xs-2">
            <label>Material Didático:</label><br>
            <input type="number" name="valor_material_didatico" id="valor_material_didatico" step="0.010" class="form-control"  value="{{ $captacao->valor_material_didatico ?? old('valor_material_didatico') }}">    
        </div>
    </div>

    <div class="row">       
        <div class="form-group col-sm-3 col-xs-2">
            <label>Bilíngue:</label><br>
            <input type="number" name="valor_bilingue" id="valor_bilingue" step="0.010" class="form-control"  value="{{ $captacao->valor_bilingue ?? old('valor_bilingue') }}">    
        </div>
        <div class="form-group col-sm-3 col-xs-2">
            <label>Robótica:</label><br>
            <input type="number" name="valor_robotica" id="valor_robotica" step="0.010" class="form-control"  value="{{ $captacao->valor_robotica ?? old('valor_robotica') }}">    
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-12 col-xs-2">
            <label>Observações:</label><br>
            <textarea name="observacao"  rows="3" class="form-control">{{$captacao->observacao ?? old('observacao')}}</textarea>
        </div>
    </div>
        
    <div class="row">
        <div class="form-group col-sm-4 col-xs-2">            
            * Campos Obrigatórios<br>
            <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Enviar</button>            
        </div>
    </div>
</div>

<script type="text/javascript" src="{!!asset('/js/populaResponsaveis.js')!!}"></script>

<script>
     $(document).ready(function ($) { 
        var $fone1 = $("#telefone");
        $fone1.mask('(00) 00000-0000', {reverse: false});
    });

    $(document).ready(function(){
          $(".alert").slideDown(300).delay(5000).slideUp(300);
    });  
</script>
