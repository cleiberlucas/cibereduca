<div class="row">
    <div class="form-group col-sm-6 col-xs-12">
        <label>Endereço:</label>
        <input type="text" name="endereco" class="form-control" placeholder="Endereço" value="{{ $endereco->endereco ?? old('endereco') }}">
    </div>    

    <div class="form-group col-sm-6 col-xs-12">
        <label>Complemento:</label>
        <input type="text" name="complemento" class="form-control" placeholder="Complemento do Endereço" value="{{ $endereco->complemento ?? old('complemento') }}">        
    </div>    
</div>

<div class="row">
    <div class="form-group col-sm-4 col-xs-12">
        <label>Bairro:</label>
        <input type="text" name="bairro" class="form-control" placeholder="Bairro" value="{{ $endereco->bairro ?? old('bairro') }}">
    </div>
    <div class="form-group col-sm-1 col-xs-10">
        <label>Estado:</label>
        <select name="estado" id="estado" class="form-control">
            <option value=""></option>
            @foreach ($estados as $estado)
                <option value="{{$estado->id_estado }}">{{$estado->sigla}}</option>
            @endforeach
        </select>        
    </div>
    <div class="form-group col-sm-3 col-xs-10">
        <label>Cidade:</label>
        
    </div>
    <div class="form-group col-sm-1 col-xs-10">
        <label>CEP:</label>
        <input type="text" name="cep" class="form-control" placeholder="CEP" value="{{ $endereco->cep ?? old('cep') }}">
    </div>
</div>
