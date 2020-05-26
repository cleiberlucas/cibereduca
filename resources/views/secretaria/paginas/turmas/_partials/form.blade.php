<div class="container-fluid">

    @include('admin.includes.alerts')
    @csrf
    
    <div class="row">
        <div class="form-group col-sm-3 col-xs-2">
            <label>Padrão de Turma:</label>
            <input type="text" name="nome_turma" class="form-control" placeholder="1º Ano A" value="{{ $turma->nome_turma ?? old('nome_turma') }}">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-3 col-xs-2">
            <label>Nome turma:</label>
            <input type="text" name="nome_turma" class="form-control" placeholder="1º Ano A" value="{{ $turma->nome_turma ?? old('nome_turma') }}">
        </div>

        <div class="form-group col-sm-2 col-xs-2">
            <label>Turno:</label>            
            <select name="fk_id_turno" id="fk_id_turno" class="form-control">
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
            <input type="text" name="localizacao" class="form-control" placeholder="1º Andar Sala 101" value="{{ $turma->localizacao ?? old('localizacao') }}">
        </div>  
        <div class="form-group col-sm-2 col-xs-2">            
            <label>Limite de Alunos</label>
            <input type="number" name="limite_alunos" class="form-control" min="1" value="{{ $turma->limite_alunos ?? old('limite_alunos') }}">        
        </div>        
    </div>

    <div class="row">
        <div class="form-group col-sm-2 col-xs-2">            
            <button type="submit" class="btn btn-success">Enviar</button>            
        </div>
    </div>
    
</div>
