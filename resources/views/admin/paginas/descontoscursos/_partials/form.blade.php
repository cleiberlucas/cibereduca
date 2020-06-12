@include('admin.includes.alerts')

@csrf

<div class="form-group">
    <label>Tipo Desconto Curso:</label>
    <input type="text" name="tipo_desconto_curso" required class="form-control" placeholder="Descrição" value="{{ $descontoCurso->tipo_desconto_curso ?? old('tipo_desconto_curso') }}">
</div>

<div class="row">
    <div class="form-group col-sm-4 col-xs-2">            
        * Campo Obrigatório<br>
        <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Enviar</button>            
    </div>
</div>
