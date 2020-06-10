@include('admin.includes.alerts')
@csrf

<div class="container-fluid">
    <div class="row">
        <div class="form-group col-sm-3 col-xs-2">
            <label>Perfil:</label>
            <input type="text" name="perfil" class="form-control" placeholder="Perfil de usuário" value="{{ $perfil->perfil ?? old('perfil') }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-6 col-xs-2">            
            <label>Descrição:</label>
            <input type="text" name="descricao_perfil" class="form-control" placeholder="Descrição" value="{{ $perfil->descricao_perfil ?? old('descricao_perfil') }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-3 col-xs-2">
            <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Enviar</button>
        </div>        
    </div>
</div>
