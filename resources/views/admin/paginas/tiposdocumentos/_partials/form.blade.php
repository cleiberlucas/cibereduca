<div class="container-fluid">
    @include('admin.includes.alerts')

    @csrf

    <div class="row">
        <div class="form-group col-sm-3 col-xs-2">
            <label>*Nome do documento:</label>
            <input type="text" name="tipo_documento" required class="form-control" placeholder="Nome do Documento" value="{{ $tipoDocumento->tipo_documento ?? old('tipo_documento') }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-3 col-xs-2">
            <label>Comentário:</label>
            <input type="text" name="comentario" class="form-control" value="{{ $tipoDocumento->comentario ?? old('comentario') }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-2 col-xs-2">
            
            @if (isset($tipoDocumento->obrigatorio) && $tipoDocumento->obrigatorio == 1)
                <input type="checkbox"  id="obrigatorio" name="obrigatorio" value="1" checked> 
            @else
                <input type="checkbox"  id="obrigatorio" name="obrigatorio" value="0"> 
            @endif
            Obrigatório entregar cópia  
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-2 col-xs-2">
            <label>*Situação:</label><br>
            @if (isset($tipoDocumento->situacao) && $tipoDocumento->situacao == 1)
                <input type="checkbox"  id="situacao" name="situacao" value="1" checked> 
            @else
                <input type="checkbox"  id="situacao" name="situacao" value="0"> 
            @endif
            Ativar  
        </div>
    </div>
    
    <div class="row">
        <div class="form-group col-sm-4 col-xs-2">            
            * Campos Obrigatórios<br>
            <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Enviar</button>            
        </div>
    </div>

</div>
