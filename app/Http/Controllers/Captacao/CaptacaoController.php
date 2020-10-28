<?php

namespace App\Http\Controllers\Captacao;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateCaptacao;
use App\Models\AnoLetivo;
use App\Models\Captacao\Captacao;
use App\Models\Captacao\MotivoContato;
use App\Models\Captacao\TipoCliente;
use App\Models\Captacao\TipoDescoberta;
use App\Models\Captacao\TipoNegociacao;
use App\Models\Secretaria\Pessoa;
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
            ->select('id_captacao', 'ano', 'nome', 'aluno', 'serie_pretendida', 'tipo_negociacao')
            ->join('tb_anos_letivos',  'fk_id_ano_letivo', 'id_ano_letivo')
            ->join('tb_pessoas', 'fk_id_pessoa', 'id_pessoa')
            ->join('tb_tipos_negociacao', 'fk_id_tipo_negociacao', 'id_tipo_negociacao')
            ->where('tb_anos_letivos.fk_id_unidade_ensino', session()->get('id_unidade_ensino') )
            ->orderBy('ano', 'desc')
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

    public function store(StoreUpdateCaptacao $request)
    {
        $this->authorize('Captação Cadastrar');   
                
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
            ->select('nome',
                'ano',
                'id_captacao', 'aluno', 'serie_pretendida', 'telefone_1', 'telefone_2', 'email_1', 'email_2', 'data_contato', 'observacao', 'tb_captacoes.data_cadastro',
                'tipo_cliente',
                'motivo_contato',
                'tipo_descoberta',
                'tipo_negociacao',
                'name')
            ->join('tb_anos_letivos', 'fk_id_ano_letivo', 'id_ano_letivo')
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

        return view('captacao.paginas.show', [
            'captacao' => $captacao
        ]);
    }


}
