@include('admin.includes.alerts')

@csrf

<div class="form-group">
    <label>Atendimento Especializado:</label>
    <input type="text" name="atendimento_especializado" required class="form-control" placeholder="Descrição" value="{{ $atendimentoEspecializado->atendimento_especializado ?? old('atendimento_especializado') }}">
</div>

<div class="row">
    <div class="form-group col-sm-2 col-xs-2">            
        <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Enviar</button>            
    </div>
</div>
