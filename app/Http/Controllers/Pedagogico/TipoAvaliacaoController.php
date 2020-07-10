<?php

namespace App\Http\Controllers\Pedagogico;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateTipoAvaliacao;
use App\Models\Pedagogico\TipoAvaliacao;
use App\Models\User;
use Illuminate\Http\Request;

class TipoAvaliacaoController extends Controller
{
    private $repositorio;
    
    public function __construct(TipoAvaliacao $tipoAvaliacao)
    {
        $this->repositorio = $tipoAvaliacao;        
    }

    public function index()
    {
        $tiposAvaliacoes = $this->repositorio
                                ->orderBy('tipo_avaliacao')
                                ->paginate();      
                
        return view('pedagogico.paginas.tiposavaliacoes.index', [
                    'tiposAvaliacoes' => $tiposAvaliacoes,        
        ]);
    }

    public function create()
    {       
        $this->authorize('Tipo Avaliação Cadastrar');
        return view('pedagogico.paginas.tiposavaliacoes.create');
    }

    public function store(StoreUpdateTipoAvaliacao $request)
    {
        $dados = $request->all();        
        $dados = array_merge($dados);
       //dd($this->usuario);
        $this->repositorio->create($dados);

        return redirect()->route('tiposavaliacoes.index');
    }

    public function destroy($id)
    {
        $tipoAvaliacao = $this->repositorio->where('id_tipo_avaliacao', $id)->first();

        if (!$tipoAvaliacao)
            return redirect()->back();

        $tipoAvaliacao->where('id_tipo_avaliacao', $id)->delete();
        return redirect()->route('tiposavaliacoes.index');
    }

    public function edit($id)
    {
        $this->authorize('Tipo Avaliação Alterar');
        $tipoAvaliacao = $this->repositorio->where('id_tipo_avaliacao', $id)->first();
             
        if (!$tipoAvaliacao)
            return redirect()->back();
        
        return view('pedagogico.paginas.tiposavaliacoes.edit',[
                    'tipoAvaliacao' => $tipoAvaliacao,            
        ]);
    }

    public function update(StoreUpdateTipoAvaliacao $request, $id)
    {        
        $tipoAvaliacao = $this->repositorio->where('id_tipo_avaliacao', $id)->first();     
        if (!$tipoAvaliacao)
            return redirect()->back();

        $sit = $this->verificarSituacao($request->all());        
        $request->merge($sit);

        $tipoAvaliacao->where('id_tipo_avaliacao', $id)->update($request->except('_token', '_method'));

        return redirect()->route('tiposavaliacoes.index');
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
