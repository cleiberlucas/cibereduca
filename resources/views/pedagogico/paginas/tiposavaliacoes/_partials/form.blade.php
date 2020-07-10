
<div class="container-fluid">

    @include('admin.includes.alerts')
    @csrf

    <div class="row">        
        <div class="form-group col-sm-3 col-xs-2">            
            <label>Tipo de avaliação:</label>
            <input type="text" name="tipo_avaliacao" required maxlength="45" id="" class="form-control" value="{{ $tipoAvaliacao->tipo_avaliacao ?? old('tipo_avaliacao') }}">        
        </div>        
        <div class="form-group col-sm-2 col-xs-2">
            <label>Sigla:</label>
            <input type="text" name="sigla_avaliacao" required maxlength="3" class="form-control" value="{{ $tipoAvaliacao->sigla_avaliacao ?? old('sigla_avaliacao') }}">        
        </div>
    </div>
    
    
    <div class="row">
        <div class="form-group col-sm-2 col-xs-2">
            <label>*Situação:</label><br>
            @if (isset($tipoAvaliacao->situacao) && $tipoAvaliacao->situacao == 1)
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
