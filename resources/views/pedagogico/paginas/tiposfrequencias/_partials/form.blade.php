
<div class="container-fluid">

    @include('admin.includes.alerts')
    @csrf

    <div class="row">        
        <div class="form-group col-sm-3 col-xs-2">            
            <label>Tipo de frequência:</label>
            <input type="text" name="tipo_frequencia" required maxlength="45" id="" class="form-control" value="{{ $tipoFrequencia->tipo_frequencia ?? old('tipo_frequencia') }}">        
        </div>        
        <div class="form-group col-sm-2 col-xs-2">
            <label>Sigla:</label>
            <input type="text" name="sigla_frequencia" required maxlength="1" class="form-control" value="{{ $tipoFrequencia->sigla_frequencia ?? old('sigla_frequencia') }}">        
        </div>
    </div>
    
    <div class="row">
        <div class="form-group col-sm-2 col-xs-2">
            <label>Causa reprovação:</label>
            <select name="reprova" id="" class="form-control" required>
                <option value="0" 
                    @if (isset($tipoFrequencia) && $tipoFrequencia->reprova == 0)
                        selected="selected"
                    @endif
                >Não</option>                
                <option value="1"
                    @if (isset($tipoFrequencia) && $tipoFrequencia->reprova == 1)
                        selected="selected"
                    @endif
                >Sim</option>                
            </select>
            <small class="form-text text-muted">Caso seja SIM, será utilizado para contabilizar reprovação do aluno por ausência.</small>
        </div>

        <div class="form-group col-sm-2 col-xs-2">
            <label>Padrão de frequência:</label>
            <select name="padrao" id="" class="form-control" required>
                <option value="0"
                    @if (isset($tipoFrequencia) && $tipoFrequencia->padrao == 0)
                        selected="selected"
                    @endif
                >Não</option>                
                <option value="1"
                    @if (isset($tipoFrequencia) && $tipoFrequencia->padrao == 1)
                        selected="selected"
                    @endif
                >Sim</option>                
            </select>
            <small class="form-text text-muted">Caso seja SIM, será utilizado para indicar o tipo de frequência, automaticamente, ao lançar frequência de uma aula.</small>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-2 col-xs-2">
            <label>*Situação:</label><br>
            @if (isset($tipoFrequencia->situacao) && $tipoFrequencia->situacao == 1)
                <input type="checkbox"  id="situacao" name="situacao" value="1" checked> 
            @else
                <input type="checkbox"  id="situacao" name="situacao" value="0"> 
            @endif
            Ativar  
        </div>
    </div>
    
    <div class="row">
        <div class="form-group col-sm-4 col-xs-2">            
            * Todos os Campos Obrigatórios<br>
            <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Enviar</button>            
        </div>
    </div>
</div>
