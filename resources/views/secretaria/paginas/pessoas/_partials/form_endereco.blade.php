<div class="row">
    <div class="form-group col-sm-6 col-xs-12">
        <label>Endereço:</label>
        <input type="text" name="endereco" class="form-control" placeholder="Endereço" required value="{{ $pessoa->endereco->endereco ?? old('endereco') }}">
    </div>
    
    <div class="form-group col-sm-2 col-xs-12">
        <label>Número:</label>
        <input type="text" name="numero" class="form-control" placeholder="1" value="{{ $pessoa->endereco->numero ?? old('numero') }}">
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-6 col-xs-12">
        <label>Complemento:</label>
        <input type="text" name="complemento" class="form-control" placeholder="Complemento do Endereço" value="{{ $pessoa->endereco->complemento ?? old('complemento') }}">        
    </div>    
</div>

<div class="row">
    <div class="form-group col-sm-4 col-xs-12">
        <label>Bairro:</label>
        <input type="text" name="bairro" class="form-control" placeholder="Bairro" value="{{ $pessoa->endereco->bairro ?? old('bairro') }}">
    </div>
    <div class="form-group col-sm-1 col-xs-10">
        <label>Estado: </label>
        <select name="estado" id="estado" class="form-control">
            <option value=""></option>
            @foreach ($estados as $estado)
                <option value="{{$estado->id_estado }}"
                    @if (isset($pessoa) && isset($pessoa->endereco->cidade) && $estado->id_estado == $pessoa->endereco->cidade->estado->fk_id_estado)
                        selected="selected"
                    @endif
                    >                    
                    {{$estado->sigla}}</option>
            @endforeach
        </select>        
    </div>
    <div class="form-group col-sm-3 col-xs-10">
        <label>Cidade:</label>
        <select name="fk_id_cidade" id="fk_id_cidade" class="form-control">
            <option value=""></option>
            @foreach ($cidades as $cidade)
                <option value="{{$cidade->id_cidade }}"
                    @if (isset($pessoa) && $cidade->id_cidade == $pessoa->endereco->fk_id_cidade)
                        selected="selected"
                    @endif
                    >                    
                    {{$cidade->cidade}}</option>
            @endforeach
        </select> 
    </div>
    <div class="form-group col-sm-2 col-xs-10">
        <label>CEP:</label>
        <input type="text" name="cep" class="form-control" placeholder="CEP" value="{{ $pessoa->endereco->cep ?? old('cep') }}">
    </div>
</div>
