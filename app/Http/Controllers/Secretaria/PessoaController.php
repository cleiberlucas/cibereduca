<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Admin\ACL\UserController;
use App\Http\Controllers\Admin\ACL\UserUnidadeEnsinoController;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdatePessoa;
use App\Models\Cidade;
use App\Models\Endereco;
use App\Models\Estado;
use App\Models\Secretaria\Pessoa;
use App\Models\Sexo;
use App\Models\TipoDocIdentidade;
use App\Models\UnidadeEnsino;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PessoaController extends Controller
{
    private $repositorio, $estados, $cidades, $tiposDocIdentidade, $unidadesEnsino, $sexos;

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

        $this->sexos = new Sexo;
        $this->sexos = $this->sexos->all()->sortBy('sexo');
    }

    public function index(Request $request)
    {
        /* Alunos 
            Mostra somente unidades de ensino vinculadas ao usuário logado
        */
        if ($request->segment(2) == '1') {

            /* Quantidade alunos cadastrados ativos */
            $qtdPessoas = $this->repositorio
                ->join('tb_unidades_ensino', 'fk_id_unidade_ensino', 'id_unidade_ensino')
                ->where('fk_id_tipo_pessoa', $request->segment(2))
                ->where('id_unidade_ensino', '=', User::getUnidadeEnsinoSelecionada())
                ->where('situacao_pessoa', 1)                
                ->count();

            $pessoas = $this->repositorio
                ->select('id_pessoa', 'nome', 'data_nascimento', 'telefone_1', 'situacao_pessoa',
                    'users.email as login')
                ->join('tb_unidades_ensino', 'fk_id_unidade_ensino', 'id_unidade_ensino')
                ->leftJoin('users', 'id', 'fk_id_user')
                ->where('fk_id_tipo_pessoa', $request->segment(2))
                ->where('id_unidade_ensino', '=', User::getUnidadeEnsinoSelecionada())
                ->orderBy('nome', 'asc')
                ->paginate(25);
        }
        /* Responsável 
            Não mostra unidade ensino
        */ else{
            $qtdPessoas = $this->repositorio->where('fk_id_tipo_pessoa', $request->segment(2))   
                ->where('situacao_pessoa', 1)                          
                ->count();

            $pessoas = $this->repositorio
                ->select('id_pessoa', 'nome', 'data_nascimento', 'telefone_1', 'situacao_pessoa',
                    'users.email as login')
                ->where('fk_id_tipo_pessoa', $request->segment(2))
                ->leftJoin('users', 'id', 'fk_id_user')
                ->orderBy('nome', 'asc')
                ->paginate(20);
        }

        return view('secretaria.paginas.pessoas.index', [
            'pessoas' => $pessoas,
            'tipo_pessoa' => $request->segment(2),
            'qtdPessoas' => $qtdPessoas,
        ]);
    }

    public function create(Request $request)
    {
        $this->authorize('Pessoa Cadastrar');
        $unidadesEnsino = $this->unidadesEnsino->where('id_unidade_ensino', User::getUnidadeEnsinoSelecionada())->get();

        return view('secretaria.paginas.pessoas.create', [
            'tipo_pessoa' => $request->segment(4),
            'estados' => $this->estados,
            'cidades' => $this->cidades,
            'tiposDocIdentidade' => $this->tiposDocIdentidade,
            'unidadesEnsino'     => $unidadesEnsino,
            'sexos'  => $this->sexos,
        ]);
    }

    public function store(StoreUpdatePessoa $request)
    {
        $request['cpf'] = somenteNumeros($request['cpf']);
        $request['telefone_1'] = somenteNumeros($request['telefone_1']);
        $request['telefone_2'] = somenteNumeros($request['telefone_2']);
        $request['cep'] = somenteNumeros($request['cep']);

        $dados = $request->all();
        $sit = $this->verificarSituacao($dados);
        $dados = array_merge($dados, $sit);
        
        if ($request->hasfile('foto') && $request->foto->isValid()) {
            $dados['foto'] = $request->file('foto')->store('pessoas');
        }
        //Gravando pessoa
        try {            
            $insertPessoa = Pessoa::create($dados);
        } catch (\Throwable $th) {
            //throw $th;
            return back()->withInput()->with('atencao', 'Erro ao gravar. Verifique se já existe o cadastro desta pessoa.');
        }

        //Gravando endereço
        //Somente para Responsável
        if ($dados['fk_id_tipo_pessoa'] == 2) {
            try {
                $insertPessoa->endereco()->create($request->except('pai', 'mae', 'fk_id_sexo'));
                $this->gerarLoginResponsavel($insertPessoa->id_pessoa);
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
        else if($dados['fk_id_tipo_pessoa'] == 1){
            try {
                $this->gerarLoginAluno($insertPessoa->id_pessoa);
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
        return redirect()->back()->with('sucesso', 'Cadastro realizado com sucesso.');
    }

    /* Gravando dados do responsável, enviado pelo agendamento online */
    public function storeResponsavelAgendamento(Array $dados ){
        //dd($dados);
        //Gravando pessoa
        try {            
            $insertPessoa = Pessoa::create($dados);
            
        } catch (\Throwable $th) {
            //throw $th;
            return $th;            
        }

        //Gravando endereço
        try {
            $dadosEndereco = array('endereco' => '-');
            $insertPessoa->endereco()->create($dadosEndereco);
        } catch (\Throwable $th) {
            //throw $th;
        }
        
        return $insertPessoa;
    }

    public function show($id)
    {
        $this->authorize('Pessoa Ver');
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
        $tipoPessoa = $pessoa->fk_id_tipo_pessoa;

        if (!$pessoa)
            return redirect()->back();

        try {
            //code...
            $pessoa->where('id_pessoa', $id)->delete();
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('atencao', 'Não é possível excluir este cadastro. Alguma informação pode estar vinculada a registros anteriores.');
        }

        return redirect()->route('pessoas.index', $tipoPessoa);
    }

    public function search(Request $request)
    {
        $filtros = $request->except('_token');
        $pessoas = $this->repositorio->search($request->filtro, $request->tipo_pessoa);
        //dd($pessoas);
        return view('secretaria.paginas.pessoas.index', [
            'pessoas' => $pessoas,
            'tipo_pessoa' => $request->tipo_pessoa,
            'filtros' => $filtros,
        ]);
    }

    public function edit($id)
    {
        $this->authorize('Pessoa Alterar');
        $pessoa = $this->repositorio->where('id_pessoa', $id)->first();
        $tipoPessoa = $pessoa->tipoPessoa->tipo_pessoa;

        if (!$pessoa)
            return redirect()->back();

        $unidadesEnsino = $this->unidadesEnsino->where('id_unidade_ensino', User::getUnidadeEnsinoSelecionada())->get();

        return view('secretaria.paginas.pessoas.edit', [
            'pessoa' => $pessoa,
            'tipoPessoa' => $tipoPessoa,
            'estados' => $this->estados,
            'cidades' => $this->cidades,
            'tiposDocIdentidade' => $this->tiposDocIdentidade,
            'unidadesEnsino'    => $unidadesEnsino,
            'sexos'  => $this->sexos,
        ]);
    }

    public function update(StoreUpdatepessoa $request, $id)
    {
        $pessoa = $this->repositorio->where('id_pessoa', $id)->first();

        if (!$pessoa)
            return redirect()->back();

        $sit = $this->verificarSituacao($request->all());
        $request['cpf'] = somenteNumeros($request['cpf']);
        $request['telefone_1'] = somenteNumeros($request['telefone_1']);
        $request['telefone_2'] = somenteNumeros($request['telefone_2']);
        $request['cep'] = somenteNumeros($request['cep']);

        $request->merge($sit);
        $dados = $request->except('_token', '_method', 'endereco', 'complemento', 'numero', 'bairro', 'fk_id_cidade', 'cep', 'estado');

        if ($request->hasfile('foto') && $request->foto->isValid()) {
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
                    ->except(
                        '_token',
                        '_method',
                        'nome',
                        'cpf',
                        'doc_identidade',
                        'data_nascimento',
                        'naturalidade',
                        'foto',
                        'fk_id_tipo_doc_identidade',
                        'obs_pessoa',
                        'pai',
                        'mae',
                        'fk_id_sexo',
                        'telefone_1',
                        'telefone_2',
                        'email_1',
                        'email_2',
                        'fk_id_tipo_pessoa',
                        'fk_id_user',
                        'situacao_pessoa',
                        'estado',
                        'fk_id_user_alteracao',
                        'profissao',
                        'empresa'
                    ));

        $pessoas = $this->repositorio->where('fk_id_tipo_pessoa', $pessoa->fk_id_tipo_pessoa)->orderBy('nome', 'asc')->paginate(20);

        return view('secretaria.paginas.pessoas.index', [
            'tipo_pessoa' => $pessoa->fk_id_tipo_pessoa,
            'pessoas'     => $pessoas,
        ])->with('sucesso', 'Dados alterados com sucesso.');
    }

    /**
     * Consulta uma pessoa pelo nome
     * Quaisquer unidade de ensino / não filtra unidade de ensino
     * @param string nome
     * @return boolean
     */
    public function getPessoa(string $nome)
    {
        $pessoa['data'] =  $this->repositorio->select('nome', 'data_nascimento')
            ->where('nome', $nome)
            ->get();
        echo json_encode($pessoa);
        exit;     
    }
    
    /*
    *Lista de todos responsaveis cadastrados
    *somente para popular combo
    */
    public function getResponsaveisTodos()
    {
        $responsaveis['data'] = $this->repositorio
            ->select('id_pessoa', 'nome')         
            ->where('fk_id_tipo_pessoa', 2)                       
            ->orderBy('nome')
            ->get();

        echo json_encode($responsaveis);
        exit;
    }

    /**
     * Geração de login para pessoa cadastrada (responsável)     
     */
    public function gerarLoginResponsavel($id_pessoa)
    {        
        $resp = new Pessoa;
        $resp = $resp
            ->select('id_pessoa', 'nome', 'email_1', 'cpf', 'fk_id_tipo_pessoa')            
            ->where('id_pessoa', $id_pessoa)                        
            ->first();
            //dd($resp);

        $user = new User;
        
        //verificando se a pessoa possui CPF cadastrado
        if ($resp->cpf != null)
            $user = $user->where('email', $resp->cpf)->first();
        else            
            return redirect()->route('pessoas.index', $resp->fk_id_tipo_pessoa)->with('atencao', 'Login para '.$resp->nome.' não foi gerado, pois não possui CPF cadastrado.');
        
        //verificando se a pessoa já possui login cadastrado
        if ($user)
            return redirect()->route('pessoas.index', $resp->fk_id_tipo_pessoa)->with('atencao', 'Esta pessoa (CPF) já possui login cadastrado.');
            //return redirect()->back()->with('atencao', 'Esta pessoa (CPF) já possui login cadastrado.');

        $userController = new UserController(new User);
        //dd($userController);
        $userUnidadeController = new UserUnidadeEnsinoController(new User, new UnidadeEnsino);
        
        $unidadesEnsino = array('0' => User::getUnidadeEnsinoSelecionada());
        
        $dadosUser = array('name' => $resp->nome,
            'email' => $resp->cpf,
            'password' => $resp->cpf);
        try{
            //dd($dadosUser);
            $idRespUser = $userController->storeRespUser($dadosUser);            

            if ($idRespUser > 0){
                //atualizando cadastro pessoa com o id_user
                $fk_id_user = array('fk_id_user' => $idRespUser);
                $resp->update($fk_id_user);                
                //vinculando à unidade de ensino                     
                $userUnidadeController->vincularUnidadesRespUser($unidadesEnsino, $idRespUser);
                //atribuindo perfil 6 = RESPONSAVEL
                $userPerfil = array(                        
                    'fk_id_perfil' => 6,                         
                );
                $userUnidadeController->updateRespUser($userPerfil, $idRespUser);                    
                //dd($resp);
                return redirect()->route('pessoas.index', $resp->fk_id_tipo_pessoa)->with('sucesso', 'Login cadastrado com sucesso.');    
            }
        } catch(\Throwable $qe) {
            //dd($qe);
            return redirect()->back()->with('erro', 'Erro ao gerar login para o responsável. Verifique se o CPF está cadastrado.'.$qe);
        }           
    }

    public function gerarLoginTodosAlunos(){
        $alunos = new Pessoa;
        $alunos = $alunos
            ->select('id_pessoa')
            ->where('fk_id_tipo_pessoa', 1)
            ->where('fk_id_user', null)
            ->get();
        //dd($alunos);
        
            foreach($alunos as $aluno){
                $this->gerarLoginAluno($aluno->id_pessoa);
            }
        return redirect()->back()->with('atencao', 'Geração de logins concluída.');
    }

    /**
     * Geração de login para pessoa cadastrada (responsável)     
     */
    public function gerarLoginAluno($id_pessoa)
    {        
        $aluno = new Pessoa;
        $aluno = $aluno
            ->select('id_pessoa', 'nome', 'fk_id_tipo_pessoa', 'fk_id_user')            
            ->where('id_pessoa', $id_pessoa)                        
            ->first();
        
        if (!$aluno)
            redirect()->back()->with('erro', 'Aluno não encontrado para geração de login.');

        if ($aluno->fk_id_user != null)
            return redirect()->back()->with('atencao', 'O(a) aluno(a) '.$aluno->nome.' já possui login.');
        
        //gerando nome de usuário para o aluno
        //primeironome.ultimosobrenome
        $nome = explode(' ', $aluno->nome);
        $login = strtolower(removerAcentos($nome[0]).'.'.removerAcentos($nome[count($nome)-1]));
        
        $user = new User;        
        /* verificando se existe login igual - dois alunos que tenham primeiro e último nome iguais
        Caso exista, incrementa um número no final
        Exemplo 
            cleiber.pereira
            cleiber.pereira1
            cleiber.pereira2
        */
        $login_existe = true;
        $cont = 0;
        while($login_existe){
            $user = $user->where('email', $login)->first();
            if (isset($user->email)){
                $cont++;
                $login .= $cont;
            }
            else
                $login_existe = false;
        }
        $senha = $login.'-'.date('Y');
        
        $userController = new UserController(new User);
        $userUnidadeController = new UserUnidadeEnsinoController(new User, new UnidadeEnsino);

        $unidadesEnsino = array('0' => User::getUnidadeEnsinoSelecionada());
        
        $dadosUser = array('name' => $aluno->nome,
            'email' => $login,
            'password' => $senha);
        try{
            $idRespUser = $userController->storeRespUser($dadosUser);            
            if ($idRespUser > 0){
                //atualizando cadastro pessoa com o id_user
                $fk_id_user = array('fk_id_user' => $idRespUser);
                $aluno->update($fk_id_user);
                                
                //vinculando à unidade de ensino                     
                $userUnidadeController->vincularUnidadesRespUser($unidadesEnsino, $idRespUser);
                //atribuindo perfil 7 = ALUNO
                $userPerfil = array(                        
                    'fk_id_perfil' => 7,                         
                );
                $userUnidadeController->updateRespUser($userPerfil, $idRespUser);                    
            }
        } catch(\Throwable $qe) {
            return redirect()->back()->with('erro', 'Erro ao gerar login para o aluno.'.$qe);
        }        
        return redirect()->route('pessoas.index', $aluno->fk_id_tipo_pessoa)->with('sucesso', 'Login cadastrado com sucesso.');    
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
