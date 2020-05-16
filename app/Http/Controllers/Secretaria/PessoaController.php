<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdatePessoa;
use App\Models\Secretaria\Pessoa;
use Illuminate\Http\Request;

class PessoaController extends Controller
{
    private $repositorio;
    
    public function __construct(Pessoa $pessoa)
    {
        $this->repositorio = $pessoa;

    }

    public function index(Request $request)
    {        
        $pessoas = $this->repositorio->where('fk_id_tipo_pessoa', $request->segment(2))->paginate();
        
        return view('secretaria.paginas.pessoas.index', [
                    'pessoas' => $pessoas,
                    'tipo_pessoa' => $request->segment(2),
        ]);
    }

    public function create(Request $request)
    {
       // dd(view('secretaria.paginas.pessoas.create'));
        return view('secretaria.paginas.pessoas.create', [
                    'tipo_pessoa' => $request->segment(4),
        ]);
    }

    public function store(StoreUpdatePessoa $request )
    {
        $dados = $request->all();
        $sit = $this->verificarSituacao($dados);
        $dados = array_merge($dados, $sit);
       // dd($dados);
        $this->repositorio->create($dados);

        return redirect()->route('pessoas.index');
    }

    public function show($id)
    {
        $pessoa = $this->repositorio->where('id_pessoa', $id)->first();
        $tipoPessoa = $pessoa->tipoPessoa->tipo_pessoa;     

        if (!$pessoa)
            return redirect()->back();

        return view('secretaria.paginas.pessoas.show', [
            'pessoa' => $pessoa,
            'tipoPessoa' => $tipoPessoa,
        ]);
    }

    public function destroy($id)
    {
        $pessoa = $this->repositorio->where('id_pessoa', $id)->first();

        if (!$pessoa)
            return redirect()->back();

        $pessoa->where('id_pessoa', $id)->delete();
        return redirect()->route('pessoas.index');
    }

    public function search(Request $request)
    {
        $filtros = $request->except('_token');
        $pessoas = $this->repositorio->search($request->filtro, $request->tipo_pessoa);
        //dd($filtros);
        return view('secretaria.paginas.pessoas.index', [
            'pessoas' => $pessoas,
            'tipo_pessoa' => $request->tipo_pessoa,
            'filtros' => $filtros,
        ]);
    }

    public function edit($id)
    {
        $pessoa = $this->repositorio->where('id_pessoa', $id)->first();
        $tipoPessoa = $pessoa->tipoPessoa->tipo_pessoa;        

        if (!$pessoa)
            return redirect()->back();
                
        return view('secretaria.paginas.pessoas.edit',[
            'pessoa' => $pessoa,
            'tipoPessoa' => $tipoPessoa,
        ]);
    }

    public function update(StoreUpdatepessoa $request, $id)
    {
        $pessoa = $this->repositorio->where('id_pessoa', $id)->first();

        if (!$pessoa)
            return redirect()->back();
        
        $sit = $this->verificarSituacao($request->all());
        
        $request->merge($sit);

        $pessoa->where('id_pessoa', $id)->update($request->except('_token', '_method'));

        return redirect()->route('pessoas.index');
    }

    /**
     * Verifica se a situação foi ativada
     */
    public function verificarSituacao(array $dados)
    {
        if (!array_key_exists('situacao_pessoa', $dados))
            return ['situacao_pessoa' => '0'];
        else
             return ['situacao_pessoa' => '1'];            
    }
}
