<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdatePessoa;
use App\Models\Cidade;
use App\Models\Estado;
use App\Models\Secretaria\Pessoa;
use Illuminate\Http\Request;

class PessoaController extends Controller
{
    private $repositorio, $estados;
    
    public function __construct(Pessoa $pessoa)
    {
        $this->repositorio = $pessoa;
        $this->estados = new Estado;
        $this->estados = $this->estados->all()->sortBy('sigla');
    }

    public function index(Request $request)
    {        
        $pessoas = $this->repositorio->where('fk_id_tipo_pessoa', $request->segment(2))->orderBy('nome', 'asc')->paginate();
        
        return view('secretaria.paginas.pessoas.index', [
                    'pessoas' => $pessoas,
                    'tipo_pessoa' => $request->segment(2),
        ]);
    }

    public function create(Request $request)
    {   
        return view('secretaria.paginas.pessoas.create', [
                    'tipo_pessoa' => $request->segment(4),
                    'estados' => $this->estados,
        ]);
    }

    public function store(StoreUpdatePessoa $request )
    {
        $dados = $request->all();
        $sit = $this->verificarSituacao($dados);
        $dados = array_merge($dados, $sit);
        //dd($dados);
        $insertPessoa = $this->repositorio->create($dados);

        $pess = Pessoa::find($insertPessoa);
        //$dados = array_merge($dados, ['fk_id_pessoa', $insertPessoa]);
        //dd($insertPessoa->id_pessoa);
        $insertEnd = $pess->endereco()->create($dados);
        //dd($pessoa);
        //$pessoa

        return redirect()->back();
    }

    public function show($id)
    {
        $pessoa = $this->repositorio->where('id_pessoa', $id)->with('usuario')->first();
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

        return redirect()->back();
       /* return view('secretaria.paginas.pessoas.index', [
            'pessoas' => $pessoas,
            'tipo_pessoa' => $request->segment(2),
        ]); */
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
