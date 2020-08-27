<div class="container-fluid">

    @include('admin.includes.alerts')
    @csrf
    
    <div class="row">
        <div class="form-group col-sm-3 col-xs-2">
            <input type="hidden" name="fk_id_tipo_turma" 
                value="
                    @if( isset($tipoTurma))
                        {{$tipoTurma->id_tipo_turma}}

                    @endif
                
                ">            
            <label>* Período Letivo:</label>
            
            <select name="fk_id_periodo_letivo" id="fk_id_periodo_letivo" required class="form-control">
                <option value="" ></option>                                                
                @foreach ($periodosLetivos as $periodoLetivo)
                
                    <option value="{{$periodoLetivo->id_periodo_letivo}} " 
                        
                        @if (isset($avaliacao) && $periodoLetivo->id_periodo_letivo == $avaliacao->fk_id_periodo_letivo ) 
                            selected="selected"
                        @endif
                    >{{$periodoLetivo->periodo_letivo}}</option>                    
                @endforeach
            </select>
        </div>
   
        <div class="form-group col-sm-3 col-xs-2">
            <label>* Disciplina:</label>
            <select name="fk_id_disciplina" id="fk_id_disciplina" required class="form-control">
                <option value="" ></option>                                                
                @foreach ($gradeCurricular as $disciplina)
                
                    <option value="{{$disciplina->id_disciplina}} " 
                        
                        @if (isset($avaliacao) && $disciplina->id_disciplina == $avaliacao->fk_id_disciplina ) 
                            selected="selected"
                        @endif
                    >{{$disciplina->disciplina}}</option>                    
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-3 col-xs-2">
            <label>* Tipo de Avaliação:</label>            
            <select name="fk_id_tipo_avaliacao" id="fk_id_tipo_avaliacao" class="form-control" required >
                <option value="" ></option>                
                @foreach ($tiposAvaliacao as $tipoAvaliacao)
                    <option value="{{$tipoAvaliacao->id_tipo_avaliacao}}" 
                        
                        @if (isset($avaliacao) && $tipoAvaliacao->id_tipo_avaliacao == $avaliacao->fk_id_tipo_avaliacao ) 
                            selected="selected"
                        @endif

                    >{{$tipoAvaliacao->tipo_avaliacao}}</option>                    
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-3 col-xs-2">            
            <label>* Valor</label>
            <input type="number" step="0.010"  name="valor_avaliacao" class="form-control" placeholder="" required  value="{{ $avaliacao->valor_avaliacao ?? old('valor_avaliacao') }}">
        </div>          
    </div>
    <div class="row">
        <div class="form-group col-sm-12 col-xs-2">            
            <label>Conteúdo Avaliativo</label><br>
            <textarea name="conteudo" id="" cols="140" rows="5">{{$avaliacao->conteudo ?? old('conteudo')}}</textarea>
        </div>          
    </div>

    <div class="row">
        <div class="form-group col-sm-4 col-xs-2">   
            * Campos obrigatórios.<br>
            <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Enviar</button>
        </div>
    </div>
    
</div>

<script>
    document.getElementById("valor_avaliacao").addEventListener("change", function(){
        this.value = parseFloat(this.value).toFixed(2);
    });
</script>

<script>
    $(document).ready(function(){
          $(".alert").slideDown(300).delay(5000).slideUp(300);
    });    
</script>