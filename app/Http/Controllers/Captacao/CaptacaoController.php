<?php

namespace App\Http\Controllers\Captacao;

use App\Http\Controllers\CaptchaServiceController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Secretaria\PessoaController;
use App\Http\Requests\StoreUpdateCaptacao;
use App\Models\AnoLetivo;
use App\Models\Captacao\Captacao;
use App\Models\Captacao\HistoricoCaptacao;
use App\Models\Captacao\MotivoContato;
use App\Models\Captacao\TipoCliente;
use App\Models\Captacao\TipoDescoberta;
use App\Models\Captacao\TipoNegociacao;
use App\Models\Secretaria\Pessoa;
use App\Models\UnidadeEnsino;
use App\User;
use Illuminate\Http\Request;

class CaptacaoController extends Controller
{
    private $repositorio;
    
    public function __construct(Captacao $captacao)
    {
        $this->repositorio = $captacao;        
    }

    public function index()
    {
        $this->authorize('Captação Ver');   
        
        $captacoes = $this->repositorio
            ->select('id_captacao', 'ano', 'nome', 'aluno', 'serie_pretendida', 'tipo_negociacao', 'data_agenda', 'hora_agenda')
            ->leftJoin('tb_anos_letivos',  'fk_id_ano_letivo', 'id_ano_letivo')
            ->join('tb_pessoas', 'fk_id_pessoa', 'id_pessoa')
            ->leftJoin('tb_tipos_negociacao', 'fk_id_tipo_negociacao', 'id_tipo_negociacao')
            ->where('tb_captacoes.fk_id_unidade_ensino', session()->get('id_unidade_ensino') )
            /* ->orderBy('ano', 'desc') */
            ->orderBy('data_agenda', 'desc')
            ->orderBy('hora_agenda')
            ->orderBy('nome')            
            ->paginate(25);      

        //dd($captacoes);
                
        return view('captacao.paginas.index', 
            compact('captacoes')
        );
    }

     public function create()
    {       
        $this->authorize('Captação Cadastrar');

        $anosLetivos = new AnoLetivo;
        $anosLetivos = $anosLetivos
            ->select('id_ano_letivo', 'ano')
            ->where('fk_id_unidade_ensino', session()->get('id_unidade_ensino') )
            ->orderBy('ano', 'desc')
            ->get();

        // dd($anosLetivos);

        $pessoas = new Pessoa;
        $pessoas = $pessoas
            ->select('id_pessoa', 'nome')
            ->where('fk_id_tipo_pessoa', 2)
            ->orderBy('nome')
            ->get();

        $tiposCliente = new TipoCliente;
        $tiposCliente = $tiposCliente
            ->orderBy('tipo_cliente')
            ->get();

        $motivosContato = new MotivoContato;
        $motivosContato = $motivosContato
            ->orderBy('motivo_contato')
            ->get();

        $tiposNegociacao = new TipoNegociacao;
        $tiposNegociacao = $tiposNegociacao
            ->orderBy('tipo_negociacao')
            ->get();
        
        $tiposDescoberta = new TipoDescoberta;
        $tiposDescoberta = $tiposDescoberta
            ->orderBy('tipo_descoberta')
            ->get();

        return view('captacao.paginas.create', [
            'anosLetivos' => $anosLetivos,
            'pessoas' => $pessoas,
            'tiposCliente' => $tiposCliente,
            'motivosContato' => $motivosContato,
            'tiposNegociacao' => $tiposNegociacao,
            'tiposDescoberta' => $tiposDescoberta,
        ]);
    }

    public function createAgendamento(){
        $unidadesEnsino = new UnidadeEnsino;
        $unidadesEnsino = $unidadesEnsino
            ->select('id_unidade_ensino', 'nome_fantasia', 'telefone', 'telefone_2', 'email' )
            ->where('situacao', 1)
            ->orderBy('nome_fantasia')
            ->get();

        $tiposCliente = new TipoCliente;
        $tiposCliente = $tiposCliente
            ->orderBy('tipo_cliente')
            ->get();

        $tiposDescoberta = new TipoDescoberta;
        $tiposDescoberta = $tiposDescoberta
            ->orderBy('tipo_descoberta')
            ->get();

        return view('captacao.paginas.agendamento_online',
            compact('tiposCliente', 'tiposDescoberta', 'unidadesEnsino'
            )
        );
    }

    //Gravação agendamento on-line
    //não tem autenticação
    public function storeAgendamento(Request $request)
    {                        
        $captcha = new CaptchaServiceController;
        $captcha->captchaFormValidate($request);
        
        $dadosPessoa = [
            'nome' => $request->nome,
            'telefone_1' => somenteNumeros($request->telefone_1),
            'email_1' => $request->email_1,
            'fk_id_sexo' => '0',
            'fk_id_user_cadastro' => '-1',
            'situacao_pessoa' => '1',
            'fk_id_user_alteracao' => '-1',
            'fk_id_tipo_pessoa' => '2',
        ];

        //inserindo dados na tabela pessoas
        $inserePessoa = new PessoaController(new Pessoa());
        $inserePessoa = $inserePessoa->storeResponsavelAgendamento($dadosPessoa);

        //dd($inserePessoa);
        
        $request->merge(['fk_id_pessoa' => $inserePessoa->id_pessoa]);//id pessoa
        $request->merge(['fk_id_motivo_contato' => '4']);//agendamento
        $request->merge(['fk_id_tipo_negociacao' => '1']);//em negociação
        $request->merge(['data_contato' => date('Ymd')]);
        $request->merge(['fk_id_usuario_captacao' => '-1']);

        $dados = $request->all();  
        //dd($dados);
        
        try {            
            $agendamento = $this->repositorio->create($dados);             
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('erro', 'Desculpe-nos, ocorreu algum erro. Não foi possível fazer o seu agendamento. Favor agendar via telefone ou email.');            
        }
        
        //dd($agendamento);

        return redirect()->back()->with('sucesso', 'Agendamento cadastrado com sucesso. Dia '.date('d/m/Y', strtotime($request->data_agenda)).' às '.$request->hora_agenda).'.';
    }

    public function store(StoreUpdateCaptacao $request)
    {
        $this->authorize('Captação Cadastrar');   
        
        $request->merge(['fk_id_unidade_ensino' => session()->get('id_unidade_ensino')]);

        $dados = $request->all();        
        $dados = array_merge($dados);
        
       //dd($this->usuario);
        $this->repositorio->create($dados);

        return redirect()->route('captacao.index')->with('sucesso', 'Captação cadastrada com sucesso.');
    }

    public function destroy($id)
    {
        $this->authorize('Captação Remover');   

        $captacao = $this->repositorio->where('id_captacao', $id)->first();

        if (!$captacao)
            return redirect()->back()->with('atencao', 'Captação não encontrada.');

        $captacao->where('id_captacao', $id)->delete();
        return redirect()->route('captacao.index')->with('sucesso', 'Captação removida.');
    }

    public function edit($id)
    {
        $this->authorize('Captação Alterar');
        $captacao = $this->repositorio->where('id_captacao', $id)->first();
             
        if (!$captacao)
            return redirect()->back();

        $anosLetivos = new AnoLetivo;
        $anosLetivos = $anosLetivos
            ->select('id_ano_letivo', 'ano')
            ->where('fk_id_unidade_ensino', session()->get('id_unidade_ensino') )
            ->orderBy('ano', 'desc')
            ->get();

        // dd($anosLetivos);

        $pessoas = new Pessoa;
        $pessoas = $pessoas
            ->select('id_pessoa', 'nome')
            ->where('fk_id_tipo_pessoa', 2)
            ->orderBy('nome')
            ->get();

        $tiposCliente = new TipoCliente;
        $tiposCliente = $tiposCliente
            ->orderBy('tipo_cliente')
            ->get();

        $motivosContato = new MotivoContato;
        $motivosContato = $motivosContato
            ->orderBy('motivo_contato')
            ->get();

        $tiposNegociacao = new TipoNegociacao;
        $tiposNegociacao = $tiposNegociacao
            ->orderBy('tipo_negociacao')
            ->get();
        
        $tiposDescoberta = new TipoDescoberta;
        $tiposDescoberta = $tiposDescoberta
            ->orderBy('tipo_descoberta')
            ->get();

        return view('captacao.paginas.edit', [
            'captacao' => $captacao,
            'anosLetivos' => $anosLetivos,
            'pessoas' => $pessoas,
            'tiposCliente' => $tiposCliente,
            'motivosContato' => $motivosContato,
            'tiposNegociacao' => $tiposNegociacao,
            'tiposDescoberta' => $tiposDescoberta,

        ]);        
    }

    public function update(StoreUpdateCaptacao $request, $id)
    {      
        $this->authorize('Captação Alterar');   

        $captacao = $this->repositorio->where('id_captacao', $id)->first();     
        if (!$captacao)
            return redirect()->back()->with('erro', 'Não foi possível alterar a captação.');
                    
        $captacao->where('id_captacao', $id)->update($request->except('_token', '_method'));

        return redirect()->route('captacao.index')->with('sucesso', 'Captação alterada com sucesso.');
    }

    public function show($id)
    {
        $this->authorize('Captação Ver');

        $captacao = $this->repositorio  
            ->select('nome', 'id_pessoa',
                'ano',
                'id_captacao', 'aluno', 'serie_pretendida', 'telefone_1', 'telefone_2', 'email_1', 'email_2', 'data_contato', 'observacao', 'tb_captacoes.data_cadastro',
                'data_agenda', 'hora_agenda',
                'necessita_apoio', 'valor_matricula', 'valor_curso', 'valor_material_didatico',
                'valor_bilingue', 'valor_robotica',
                'tipo_cliente',
                'motivo_contato',
                'tipo_descoberta',
                'tipo_negociacao',
                'name')
            ->leftJoin('tb_anos_letivos', 'fk_id_ano_letivo', 'id_ano_letivo')
            ->join('tb_unidades_ensino', 'tb_captacoes.fk_id_unidade_ensino', 'id_unidade_ensino')
            ->join('tb_pessoas', 'fk_id_pessoa', 'id_pessoa')          
            ->join('tb_tipos_cliente', 'fk_id_tipo_cliente', 'id_tipo_cliente')
            ->join('tb_motivos_contato', 'fk_id_motivo_contato', 'id_motivo_contato')
            ->leftJoin('tb_tipos_descoberta', 'fk_id_tipo_descoberta', 'id_tipo_descoberta')
            ->join('tb_tipos_negociacao', 'fk_id_tipo_negociacao', 'id_tipo_negociacao')            
            ->join('users', 'fk_id_usuario_captacao', 'id')
            ->where('id_captacao', $id)            
            ->first();
        //dd($turma);

        if (!$captacao)
            return redirect()->back()->with('alerta', 'Captação não encontrada.');

        $historicos = new HistoricoCaptacao;
        $historicos = $historicos
            ->join('tb_motivos_contato', 'fk_id_motivo_contato', 'id_motivo_contato')    
            ->where('fk_id_captacao', $id)
            ->orderby('data_contato')
            ->get();

        return view('captacao.paginas.show', 
            compact('captacao', 'historicos')            
        );
    }
    
    public function search(Request $request)
    {
        $filtros = $request->except('_token');
       // dd($request->filtro);
        $captacoes = $this->repositorio->search($request->filtro);
        
        
        return view('captacao.paginas.index', [
                    'captacoes' => $captacoes,
                    'filtros' => $filtros,
        ]);
    }

}
