<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateOpcaoEducacional;
use App\Models\AnoLetivo;
use App\Models\Secretaria\Matricula;
use App\Models\Secretaria\OpcaoEducacional;
use App\Models\Secretaria\UserUnidadeEnsino;
use App\Models\UnidadeEnsino;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpcaoEducacionalController extends Controller
{
    private $repositorio;
    
    public function __construct(OpcaoEducacional $opcaoEducacional)
    {
        $this->repositorio = $opcaoEducacional;        
    }

    public function index()
    {
        $this->authorize('Opção Educacional Ver');   
        
        $opcoesEducacionais = $this->repositorio
            ->select('tb_opcoes_educacionais.*',
                'resp.nome as responsavel',
                'aluno.nome as aluno',
                'aluno.data_nascimento',
                'tb_turmas.nome_turma',
                'tb_anos_letivos.ano', 
                )                
            ->join('tb_matriculas', 'id_matricula', 'fk_id_matricula')        
            ->join('tb_pessoas as resp', 'resp.id_pessoa', 'fk_id_responsavel')
            ->join('tb_pessoas as aluno', 'aluno.id_pessoa', 'fk_id_aluno')            
            ->join('tb_turmas', 'id_turma', 'fk_id_turma')            
            ->join('tb_tipos_turmas', 'id_tipo_turma', 'fk_id_tipo_turma')
            ->join('tb_anos_letivos', 'id_ano_letivo', 'fk_id_ano_letivo')
            ->where('tb_anos_letivos.fk_id_unidade_ensino', session()->get('id_unidade_ensino') )            
            ->orderBy('nome_turma')
            ->orderBy('aluno')
            ->paginate(25);      
        
        $perfil = new UserUnidadeEnsino;
        $perfil = $perfil
            ->select('fk_id_perfil')
            ->where('fk_id_user', Auth::id())
            ->first();
                
        return view('secretaria.paginas.opcaoeducacional.index', 
            compact('opcoesEducacionais', 'perfil')
        );
    }

    public function indexResponsavel()
    {
        $this->authorize('Opção Educacional Responsável');   
        
        $opcoesEducacionais = $this->repositorio
            ->select('tb_opcoes_educacionais.*',
                'resp.nome as responsavel',
                'aluno.nome as aluno',
                'aluno.data_nascimento',
                'tb_turmas.nome_turma',
                'tb_anos_letivos.ano', 
                )                
            ->join('tb_matriculas', 'id_matricula', 'fk_id_matricula')        
            ->join('tb_pessoas as resp', 'resp.id_pessoa', 'fk_id_responsavel')
            ->join('tb_pessoas as aluno', 'aluno.id_pessoa', 'fk_id_aluno')            
            ->join('tb_turmas', 'id_turma', 'fk_id_turma')            
            ->join('tb_tipos_turmas', 'id_tipo_turma', 'fk_id_tipo_turma')
            ->join('tb_anos_letivos', 'id_ano_letivo', 'fk_id_ano_letivo')
            ->join('users', 'email', 'resp.cpf')
            ->where('id', Auth::id())
            ->where('tb_anos_letivos.fk_id_unidade_ensino', session()->get('id_unidade_ensino') )                     
            ->orderBy('nome_turma')
            ->orderBy('aluno')
            ->paginate(25);      

            $perfil = new UserUnidadeEnsino;
            $perfil = $perfil
                ->select('fk_id_perfil')
                ->where('fk_id_user', Auth::id())
                ->first();
                
        return view('secretaria.paginas.opcaoeducacional.index', 
            compact('opcoesEducacionais', 'perfil')
        );
    }

    public function imprimir($id)
    {
       // $this->authorize('Opção Educacional Ver');   
        
        $opcaoEducacional = $this->repositorio
            ->select('tb_opcoes_educacionais.*',
                'resp.nome as responsavel',
                'resp.cpf',
                'aluno.nome as aluno',
                'aluno.data_nascimento',
                'sexo',
                'tb_turmas.nome_turma',
                'descricao_turno',
                'tb_anos_letivos.ano',
                'name',
                'tb_anos_letivos.fk_id_unidade_ensino')
            ->join('tb_matriculas', 'id_matricula', 'fk_id_matricula')        
            ->join('tb_pessoas as resp', 'resp.id_pessoa', 'fk_id_responsavel')
            ->join('tb_pessoas as aluno', 'aluno.id_pessoa', 'fk_id_aluno')
            ->join('tb_sexo', 'id_sexo', 'aluno.fk_id_sexo')
            ->join('tb_turmas', 'id_turma', 'fk_id_turma')
            ->join('tb_turnos', 'fk_id_turno', 'id_turno')
            ->join('tb_tipos_turmas', 'id_tipo_turma', 'fk_id_tipo_turma')
            ->join('tb_anos_letivos', 'id_ano_letivo', 'fk_id_ano_letivo')
            ->join('users', 'tb_opcoes_educacionais.fk_id_usuario', 'id')
            ->where('tb_anos_letivos.fk_id_unidade_ensino', session()->get('id_unidade_ensino') )
            ->where('id_opcao_educacional', $id)
            ->orderBy('nome_turma')
            ->orderBy('aluno')
            ->first();      

           // dd($opcaoEducacional);
        $unidadeEnsino = new UnidadeEnsino;
        $unidadeEnsino = $unidadeEnsino
            ->select('*')
            ->where('id_unidade_ensino', $opcaoEducacional->fk_id_unidade_ensino)
            ->first();

            //dd($unidadeEnsino);

        return view('secretaria.paginas.opcaoeducacional.opcao_educacional', 
            compact('opcaoEducacional', 'unidadeEnsino')
        );
    }    

    public function create()
    {       
        $this->authorize('Opção Educacional Cadastrar');

        $anosLetivos = new AnoLetivo;
        $anosLetivos = $anosLetivos
            ->select('id_ano_letivo', 'ano')
            ->where('fk_id_unidade_ensino', session()->get('id_unidade_ensino') )
            ->orderBy('ano', 'desc')
            ->get();

        return view('secretaria.paginas.opcaoeducacional.create', [
            'anosLetivos' => $anosLetivos,
            
        ]);
    }

    public function createResponsavel()
    {       
        $this->authorize('Opção Educacional Responsável');

        $alunos = new Matricula;
        $alunos = $alunos
            ->select(
                'id_matricula',
                'aluno.nome as aluno',
                'nome_turma',
                'ano')
            ->join('tb_pessoas as resp', 'resp.id_pessoa', 'fk_id_responsavel') 
            ->join('tb_pessoas as aluno', 'aluno.id_pessoa', 'fk_id_aluno')            
            ->join('tb_turmas', 'id_turma', 'fk_id_turma')            
            ->join('tb_tipos_turmas', 'id_tipo_turma', 'fk_id_tipo_turma')
            ->join('tb_anos_letivos', 'id_ano_letivo', 'fk_id_ano_letivo')
            ->join('users', 'email', 'resp.cpf')            
            ->where('id', Auth::id())
            ->where('ano', '2021')
            ->orderBy('aluno')
            ->get();

        return view('secretaria.paginas.opcaoeducacional.create_resp', 
            compact('alunos')            
        );
    }

    public function store(StoreUpdateOpcaoEducacional $request)
    {
       // $this->authorize('Opção Educacional Cadastrar');   
              
        $dados = $request->all();        
        $dados = array_merge($dados);
        
       try{
            $this->repositorio->create($dados);
       } catch (\Throwable $th) {                
            return redirect()->back()->with('atencao', 'Já existe opção educacional cadastrada para esta matrícula.');
       }

       $perfil = new UserUnidadeEnsino;
       $perfil = $perfil
        ->select('fk_id_perfil')
        ->where('fk_id_user', Auth::id())
        ->first();

        //acesso para o colégio
        if ($perfil->fk_id_perfil != 6)
            return redirect()->route('opcaoeducacional.index')->with('sucesso', 'Opção Educacional cadastrada com sucesso.');
        //acesso para responsável
        else
            return redirect()->route('opcaoeducacional.responsavel')->with('sucesso', 'Opção Educacional cadastrada com sucesso.');
    }

    public function destroy($id)
    {
        $this->authorize('Opção Educacional Remover');   

        $opcaoEducacional = $this->repositorio->where('id_opcao_educacional', $id)->first();

        if (!$opcaoEducacional)
            return redirect()->back()->with('atencao', 'Opção Educacional não encontrada.');

        $opcaoEducacional->where('id_opcao_educacional', $id)->delete();
        return redirect()->route('opcaoeducacional.index')->with('sucesso', 'Opção Educacional removida.');
    }

    public function edit($id)
    {
        $this->authorize('Opção Educacional Alterar');
        $opcaoEducacional = $this->repositorio
            ->select('tb_opcoes_educacionais.*',            
            'aluno.nome as aluno',
            'tb_turmas.nome_turma',
            'tb_anos_letivos.ano')
            ->join('tb_matriculas', 'id_matricula', 'fk_id_matricula')                               
            ->join('tb_pessoas as aluno', 'aluno.id_pessoa', 'fk_id_aluno')
            ->join('tb_turmas', 'id_turma', 'fk_id_turma')
            ->join('tb_tipos_turmas', 'id_tipo_turma', 'fk_id_tipo_turma')
            ->join('tb_anos_letivos', 'id_ano_letivo', 'fk_id_ano_letivo')
            ->where('tb_anos_letivos.fk_id_unidade_ensino', session()->get('id_unidade_ensino') )
            ->where('id_opcao_educacional', $id)
            ->first();
             
        if (!$opcaoEducacional)
            return redirect()->back();

        $anosLetivos = new AnoLetivo;
        $anosLetivos = $anosLetivos
            ->select('id_ano_letivo', 'ano')
            ->where('fk_id_unidade_ensino', session()->get('id_unidade_ensino') )
            ->orderBy('ano', 'desc')
            ->get();

        return view('secretaria.paginas.opcaoeducacional.edit', [
            'opcaoEducacional' => $opcaoEducacional,
            'anosLetivos' => $anosLetivos,
            
        ]);        
    }

    public function editResponsavel($id)
    {
        //$this->authorize('Opção Educacional Alterar');
        $opcaoEducacional = $this->repositorio
            ->select('tb_opcoes_educacionais.*',            
            'aluno.nome as aluno',
            'tb_turmas.nome_turma',
            'tb_anos_letivos.ano')
            ->join('tb_matriculas', 'id_matricula', 'fk_id_matricula')       
            ->join('tb_pessoas as resp', 'resp.id_pessoa', 'fk_id_responsavel')               
            ->join('tb_pessoas as aluno', 'aluno.id_pessoa', 'fk_id_aluno')
            ->join('tb_turmas', 'id_turma', 'fk_id_turma')
            ->join('tb_tipos_turmas', 'id_tipo_turma', 'fk_id_tipo_turma')
            ->join('tb_anos_letivos', 'id_ano_letivo', 'fk_id_ano_letivo')
            ->join('users', 'email', 'resp.cpf')            
            ->where('id', Auth::id())
            ->where('tb_anos_letivos.fk_id_unidade_ensino', session()->get('id_unidade_ensino') )
            ->where('id_opcao_educacional', $id)            
            ->first();
             
        if (!$opcaoEducacional)
            return redirect()->back()->with('erro', 'Esta matrícula não está vinculada a você.');

        return view('secretaria.paginas.opcaoeducacional.edit_resp', [
            'opcaoEducacional' => $opcaoEducacional,           
            
        ]);        
    }

    public function update(StoreUpdateOpcaoEducacional $request, $id)
    {      
        //$this->authorize('Opção Educacional Alterar');   

        $opcaoEducacional = $this->repositorio->where('id_opcao_educacional', $id)->first();     
        if (!$opcaoEducacional)
            return redirect()->back()->with('erro', 'Não foi possível alterar a Opção Educacional.');
                    
        $opcaoEducacional->where('id_opcao_educacional', $id)->update($request->except('_token', '_method'));

        $perfil = new UserUnidadeEnsino;
        $perfil = $perfil
         ->select('fk_id_perfil')
         ->where('fk_id_user', Auth::id())
         ->first();
 
         //acesso para o colégio
        if ($perfil->fk_id_perfil != 6)         
            return redirect()->route('opcaoeducacional.index')->with('sucesso', 'Opção Educacional alterada com sucesso.');
        else
            return redirect()->route('opcaoeducacional.responsavel')->with('sucesso', 'Opção Educacional alterada com sucesso.');
    }

    /* public function show($id)
    {
        $this->authorize('Opção Educacional Ver');

        $opcaoEducacional = $this->repositorio  
            ->select('nome', 'id_pessoa',
                'ano',
                'id_opcao_educacional', 'aluno', 'serie_pretendida', 'telefone_1', 'tb_pessoas.telefone_2', 'email_1', 'email_2', 'data_contato', 'observacao', 'tb_captacoes.data_cadastro',
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
            ->where('id_opcao_educacional', $id)            
            ->first();
        //dd($turma);

        if (!$opcaoEducacional)
            return redirect()->back()->with('alerta', 'Opção Educacional não encontrada.');

        $historicos = new HistoricoCaptacao;
        $historicos = $historicos
            ->join('tb_motivos_contato', 'fk_id_motivo_contato', 'id_motivo_contato')    
            ->where('fk_id_opcao_educacional', $id)
            ->orderby('data_contato')
            ->get();

        return view('secretaria.paginas.opcaoeducacional.show', 
            compact('captacao', 'historicos')            
        );
    } */
    
    public function search(Request $request)
    {
        $filtros = $request->except('_token');
       // dd($request->filtro);
        $captacoes = $this->repositorio->search($request->filtro);
        
        
        return view('secretaria.paginas.opcaoeducacional.index', [
                    'opcoesEducacionais' => $captacoes,
                    'filtros' => $filtros,
        ]);
    }
}
