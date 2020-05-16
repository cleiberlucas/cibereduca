<div class="row">
    <div class="form-group col-sm-6 col-xs-12">
        <label>Endereço:</label>
        <input type="text" name="endereco" class="form-control" placeholder="Endereço" value="{{ $unidadeensino->nome_fantasia ?? old('nome_fantasia') }}">
    </div>    

    <div class="form-group col-sm-6 col-xs-12">
        <label>Complemento:</label>
        <input type="text" name="complemento" class="form-control" placeholder="Complemento do Endereço" value="{{ $unidadeensino->cnpj ?? old('cnpj') }}">        
    </div>    
</div>

<div class="row">
    <div class="form-group col-sm-4 col-xs-12">
        <label>Bairro:</label>
        <input type="text" name="bairro" class="form-control" placeholder="Bairro" value="{{ $unidadeensino->cnpj ?? old('cnpj') }}">
    </div>
    <div class="form-group col-sm-1 col-xs-10">
        <label>Estado:</label>
        <input type="text" name="estado" class="form-control"  value="{{ $unidadeensino->telefone ?? old('telefone') }}">
    </div>
    <div class="form-group col-sm-1 col-xs-10">
        <label>Cidade:</label>
        <input type="text" name="fk_id_cidade" class="form-control" placeholder="Cidade" value="{{ $unidadeensino->telefone ?? old('telefone') }}">
    </div>
    <div class="form-group col-sm-1 col-xs-10">
        <label>CEP:</label>
        <input type="text" name="cep" class="form-control" placeholder="CEP" value="{{ $unidadeensino->telefone ?? old('telefone') }}">
    </div>
</div>
