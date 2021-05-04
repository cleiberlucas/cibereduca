@include('admin.includes.alerts')

<div class="container-fluid">
    <div class="row">
        <div class="form-group col-sm-4 col-xs-2">
            <label>Login do usuário (email):</label>
            <input type="text" name="email" class="form-control" placeholder="Email" value="{{ $user->email ?? old('email') }} ">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-4 col-xs-2">
            <label>Nome:</label>
            <input type="text" name="name" class="form-control" placeholder="Nome" value="{{ $user->name ?? old('name') }}">
        </div>
        <div class="form-group col-sm-4 col-xs-2">
            <label>Senha:</label>
            <input type="password" name="password" class="form-control" placeholder="Senha:">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-4 col-xs-2">    
            <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Enviar</button>            
        </div>
    </div>
</div>
