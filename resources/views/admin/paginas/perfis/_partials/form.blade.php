@include('admin.includes.alerts')
@csrf

<div class="form-group">
    <label>Perfil:</label>
    <input type="text" name="perfil" class="form-control" placeholder="Perfil de usuário" value="{{ $perfil->perfil ?? old('perfil') }} ">
</div>
<div class="form-group">
    <label>Descrição:</label>
    <input type="text" name="descricao_perfil" class="form-control" placeholder="Descrição" value="{{ $perfil->descricao_perfil ?? old('descricao_perfil') }}">
</div>

<div>
    <button type="submit" class="btn btn-dark">Enviar</button>
</div>