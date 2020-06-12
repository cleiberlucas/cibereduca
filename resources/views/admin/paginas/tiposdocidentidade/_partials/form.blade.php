@include('admin.includes.alerts')

@csrf

<div class="form-group">
    <label>Tipo Documento Identidade:</label>
    <input type="text" name="tipo_doc_identidade" required class="form-control" placeholder="Descrição" value="{{ $tipoDocIdentidade->tipo_doc_identidade ?? old('tipo_doc_identidade') }}">
</div>

<div class="row">
    <div class="form-group col-sm-4 col-xs-2">            
        * Campo Obrigatório<br>
        <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Enviar</button>            
    </div>
</div>
