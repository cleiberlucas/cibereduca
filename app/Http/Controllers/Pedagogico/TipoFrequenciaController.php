<?php

namespace App\Http\Controllers\Pedagogico;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateTipoFrequencia;
use App\Models\Pedagogico\TipoFrequencia;
use App\User;
use Illuminate\Http\Request;

class TipoFrequenciaController extends Controller
{
    private $repositorio;
    
    public function __construct(TipoFrequencia $tipoFrequencia)
    {
        $this->repositorio = $tipoFrequencia;        
    }

    public function index()
    {
        $tiposFrequencias = $this->repositorio
                                ->orderBy('tipo_frequencia')
                                ->paginate();      
                
        return view('pedagogico.paginas.tiposfrequencias.index', [
                    'tiposFrequencias' => $tiposFrequencias,        
        ]);
    }

    public function create()
    {       
        $this->authorize('Tipo Frequência Cadastrar');
        return view('pedagogico.paginas.tiposfrequencias.create');
    }

    public function store(StoreUpdateTipoFrequencia $request)
    {
        $dados = $request->all();        
        $dados = array_merge($dados);
       //dd($this->usuario);
        $this->repositorio->create($dados);

        return redirect()->route('tiposfrequencias.index');
    }

    public function destroy($id)
    {
        $tipoFrequencia = $this->repositorio->where('id_tipo_frequencia', $id)->first();

        if (!$tipoFrequencia)
            return redirect()->back();

        $tipoFrequencia->where('id_tipo_frequencia', $id)->delete();
        return redirect()->route('tiposfrequencias.index');
    }

    public function edit($id)
    {
        $this->authorize('Tipo Frequência Alterar');
        $tipoFrequencia = $this->repositorio->where('id_tipo_frequencia', $id)->first();
             
        if (!$tipoFrequencia)
            return redirect()->back();
        
        return view('pedagogico.paginas.tiposfrequencias.edit',[
                    'tipoFrequencia' => $tipoFrequencia,            
        ]);
    }

    public function update(StoreUpdateTipoFrequencia $request, $id)
    {        
        $tipoFrequencia = $this->repositorio->where('id_tipo_frequencia', $id)->first();     
        if (!$tipoFrequencia)
            return redirect()->back();

        $sit = $this->verificarSituacao($request->all());        
        $request->merge($sit);

        $tipoFrequencia->where('id_tipo_frequencia', $id)->update($request->except('_token', '_method'));

        return redirect()->route('tiposfrequencias.index');
    }

    /**
     * Verifica se a situação foi ativada
     */
    public function verificarSituacao(array $dados)
    {
        if (!array_key_exists('situacao', $dados))
            return ['situacao' => '0'];
        else
             return ['situacao' => '1'];            
    }
}
