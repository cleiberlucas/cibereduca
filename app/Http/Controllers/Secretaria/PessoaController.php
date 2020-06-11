<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdatePessoa;
use App\Models\Cidade;
use App\Models\Endereco;
use App\Models\Estado;
use App\Models\Secretaria\Pessoa;
use App\Models\TipoDocIdentidade;
use App\Models\UnidadeEnsino;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PessoaController extends Controller
{
    private $repositorio, $estados, $cidades, $tiposDocIdentidade, $unidadesEnsino;
    
    public function __construct(Pessoa $pessoa)
    {
        $this->repositorio = $pessoa;
        
        $this->estados = new Estado;
        $this->estados = $this->estados->all()->sortBy('sigla');
        
        $this->cidades = new Cidade;
        $this->cidades = $this->cidades->all()->sortBy('cidade');
        
        $this->tiposDocIdentidade = new TipoDocIdentidade;
        $this->tiposDocIdentidade = $this->tiposDocIdentidade->all()->sortBy('tipo_doc_identidade');   
        
         $this->unidadesEnsino = new UnidadeEnsino;
        /*$this->unidadesEnsino = $this->unidadesEnsino->all()->sortBy('nome_fantasia'); */
    }

    public function index(Request $request)
    {        
        /* Alunos 
            Mostra somente unidades de ensino vinculadas ao usuário logado
        */
        if ($request->segment(2) == '1'){
            $pessoas = $this->repositorio
                                        ->join('tb_unidades_ensino', 'fk_id_unidade_ensino', 'id_unidade_ensino')
                                        ->where('fk_id_tipo_pessoa', $request->segment(2))->orderBy('nome', 'asc')
                                        ->where('id_unidade_ensino', '=', User::getUnidadeEnsinoSelecionada())
                                        ->paginate(20);
        }
        /* Responsável 
            Não mostra unidade ensino
        */
        else
            $pessoas = $this->repositorio->where('fk_id_tipo_pessoa', $request->segment(2))->orderBy('nome', 'asc')->paginate(20); 
        
        return view('secretaria.paginas.pessoas.index', [
                    'pessoas' => $pessoas,
                    'tipo_pessoa' => $request->segment(2),
        ]);
    }

    public function create(Request $request)
    {   
        $unidadesEnsino = $this->unidadesEnsino->where('id_unidade_ensino', User::getUnidadeEnsinoSelecionada())->get();

        return view('secretaria.paginas.pessoas.create', [
                    'tipo_pessoa' => $request->segment(4),
                    'estados' => $this->estados,
                    'cidades' => $this->cidades,
                    'tiposDocIdentidade' => $this->tiposDocIdentidade,
                    'unidadesEnsino'     => $unidadesEnsino,
         ]);
    }

    public function store(StoreUpdatePessoa $request )
    {
        $dados = $request->all();
        $sit = $this->verificarSituacao($dados);
        $dados = array_merge($dados, $sit);
        //dd($dados['fk_id_tipo_pessoa']);

        /* if ($request->foto->isValid()){
            //dd($request->foto->extension());
            $request->file('foto')->store('pessoas');
        } */
        if ($request->hasfile('foto') && $request->foto->isValid()){
            $dados['foto'] = $request->file('foto')->store('pessoas');
        }
        //Gravando pessoa
        $insertPessoa = Pessoa::create($dados);

        //Gravando endereço
        //Somente para Responsável
        if ($dados['fk_id_tipo_pessoa'] == 2)
            $insertPessoa->endereco()->create($dados);
        
        return redirect()->back();
    }

    public function show($id)
    {
        $pessoa = $this->repositorio->where('id_pessoa', $id)->first();
        
        if (!$pessoa)
            return redirect()->back();

        return view('secretaria.paginas.pessoas.show', [
            'pessoa' => $pessoa,        
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
        
        $unidadesEnsino = $this->unidadesEnsino->where('id_unidade_ensino', User::getUnidadeEnsinoSelecionada())->get();

        return view('secretaria.paginas.pessoas.edit',[
            'pessoa' => $pessoa,
            'tipoPessoa' => $tipoPessoa,
            'estados' => $this->estados,
            'cidades' => $this->cidades,
            'tiposDocIdentidade' => $this->tiposDocIdentidade,
            'unidadesEnsino'    => $unidadesEnsino,
        ]);
    }

    public function update(StoreUpdatepessoa $request, $id)
    {
        $pessoa = $this->repositorio->where('id_pessoa', $id)->first();

        if (!$pessoa)
            return redirect()->back();
        
        $sit = $this->verificarSituacao($request->all());
        
        $request->merge($sit);
        $dados = $request->except('_token', '_method', 'endereco', 'complemento', 'numero', 'bairro', 'fk_id_cidade', 'cep', 'estado');
        
        if ($request->hasfile('foto') && $request->foto->isValid()){
            //Removendo foto anterior
            if (Storage::exists($pessoa->foto)) {
                Storage::delete($pessoa->foto);
            }
            $dados['foto'] = $request->file('foto')->store('pessoas');
        }
        
        $pessoa->update($dados);

        //Gravando endereço
        //somente para responsável
        if ($pessoa->fk_id_tipo_pessoa == 2)
            Endereco::where('fk_id_pessoa', $pessoa->id_pessoa)
                            ->update($request
                                        ->except('_token', '_method', 
                                                    'nome', 'cpf', 'doc_identidade', 'data_nascimento', 'foto', 'fk_id_tipo_doc_identidade', 
                                                    'obs_pessoa',
                                                    'telefone_1', 'telefone_2', 'email_1', 'email_2', 'fk_id_tipo_pessoa', 
                                                    'fk_id_user', 'situacao_pessoa', 'estado', 'fk_id_user_alteracao'));

        $pessoas = $this->repositorio->where('fk_id_tipo_pessoa', $pessoa->fk_id_tipo_pessoa)->orderBy('nome', 'asc')->paginate(20);
       
        return view('secretaria.paginas.pessoas.index', [
                    'tipo_pessoa' => $pessoa->fk_id_tipo_pessoa,
                    'pessoas'     => $pessoas,
        ]);
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
