<div class="container-fluid">

    @include('admin.includes.alerts')
    @csrf
    
    <div class="row">
        <div class="form-group col-sm-4 col-xs-2">
            <input type="hidden" name="fk_id_user" value="{{Auth::id()}}">
            
            <label>Padrão de Turma:</label>
            <select name="fk_id_tipo_turma" id="fk_id_tipo_turma" required class="form-control">
                <option value="" ></option>                
                {{-- - {{$tipoturma['subNivelEnsino']['sub_nivel_ensino']}} --}}
                
                @foreach ($tiposTurmas as $tipoturma)
                
                    <option value="{{$tipoturma->id_tipo_turma}} " 
                        
                        @if (isset($turma) && $tipoturma->id_tipo_turma == $turma->fk_id_tipo_turma ) 
                            selected="selected"
                        @endif

                    >{{$tipoturma->tipo_turma}} - {{$tipoturma->subNivelEnsino->sub_nivel_ensino}} ({{$tipoturma->anoLetivo->ano}})</option>                    
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-3 col-xs-2">
            <label>Nome turma:</label>
            <input type="text" name="nome_turma" class="form-control" placeholder="1º Ano A" required  value="{{ $turma->nome_turma ?? old('nome_turma') }}">
        </div>

        <div class="form-group col-sm-2 col-xs-2">
            <label>Turno:</label>            
            <select name="fk_id_turno" id="fk_id_turno" class="form-control" required >
                <option value="" ></option>                
                @foreach ($turnos as $turno)
                    <option value="{{$turno->id_turno}}" 
                        
                        @if (isset($turma) && $turno->id_turno == $turma->fk_id_turno ) 
                            selected="selected"
                        @endif

                    >{{$turno->descricao_turno}}</option>                    
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-3 col-xs-2">            
            <label>Localização</label>
            <input type="text" name="localizacao" class="form-control" placeholder="1º Andar Sala 101" required  value="{{ $turma->localizacao ?? old('localizacao') }}">
        </div>  
        <div class="form-group col-sm-2 col-xs-2">            
            <label>Limite de Alunos</label>
            <input type="number" name="limite_alunos" class="form-control" min="1" required  value="{{ $turma->limite_alunos ?? old('limite_alunos') }}">        
        </div>        
    </div>

    <div class="form-group col-sm-3 col-xs-2">
        <label>Situação:</label><br>
        @if (isset($turma->situacao_turma) && $turma->situacao_turma == 1)
            <input type="checkbox" id="situacao_turma" name="situacao_turma" value="1" checked> 
        @else
            <input type="checkbox" id="situacao_turma" name="situacao_turma" value="0"> 
        @endif
        Abrir            
    </div>

    <div class="row">
        <div class="form-group col-sm-2 col-xs-2">            
            <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Enviar</button>
        </div>
    </div>
    
</div>
