<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateMatricula;
use App\Models\FormaPagamento;
use App\Models\Secretaria\CorpoContrato;
use App\Models\Secretaria\Matricula;
use App\Models\Secretaria\Pessoa;
use App\Models\Secretaria\Turma;
use App\Models\TipoDescontoCurso;
use App\Models\SituacaoMatricula;
use App\Models\TipoAtendimentoEspecializado;
use App\Models\UnidadeEnsino;
use App\User;
use PDF;
use Illuminate\Http\Request;

class MatriculaController extends Controller
{
    private $repositorio;
    
    public function __construct(Matricula $matricula)
    {
        $this->repositorio = $matricula;
    }

    /**
     * Lista alunos matriculados em uma turma
     * @param request
     */
    public function index(Request $request)
    {       
        /* $matriculas = $this->repositorio->where('fk_id_turma', $request->segment(2))
                                        ->paginate(25); */
        $matriculas = $this->repositorio
            ->select('id_matricula', 'nome', 'situacao_matricula', 'fk_id_aluno')
            ->join('tb_pessoas', 'fk_id_aluno', 'id_pessoa')
            ->join('tb_situacoes_matricula', 'fk_id_situacao_matricula', 'id_situacao_matricula')
            ->where('fk_id_turma', $request->segment(2))
            ->orderBy('nome')
            ->paginate(25);
        
         $turma = Turma::select('tb_turmas.nome_turma', 'tb_turmas.id_turma', 'tb_turmas.limite_alunos', 'tb_anos_letivos.ano', 'tb_turnos.descricao_turno', 'tb_sub_niveis_ensino.sub_nivel_ensino')                            
                            ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma' )
                            ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
                            ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
                            ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno')         
                            ->where('tb_turmas.id_turma', '=', $request->segment(2)) 
                            ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())                        
                            ->first();          
                
        return view('secretaria.paginas.matriculas.index', [
                    'matriculas' => $matriculas,
                    'turma'      => $turma,
                    'quantMatriculas'    => $this->repositorio->quantMatriculasTurma($turma->id_turma),
                    'quantVagasDisponiveis' => $this->repositorio->quantVagasDisponiveis($turma->id_turma),
        ]);
    }

    public function create(Request $request)
    {   
        $this->authorize('Matrícula Cadastrar');   
        $turma = Turma::select('tb_turmas.nome_turma', 'tb_turmas.id_turma', 'tb_turmas.limite_alunos',
                        'tb_anos_letivos.ano', 'tb_turnos.descricao_turno', 'tb_sub_niveis_ensino.sub_nivel_ensino',
                        'tb_tipos_turmas.valor_curso', 'tb_tipos_turmas.valor_matricula', 'tb_tipos_turmas.valor_material_didatico','tb_tipos_turmas.fk_id_ano_letivo' )
                        ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma' )
                        ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
                        ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
                        ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno')         
                        ->where('tb_turmas.id_turma', '=', $request->segment(4))     
                        ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())                    
                        ->first(); 
       
        $formasPagto = new FormaPagamento;

        $tiposDesconto = new TipoDescontoCurso;

        $situacoesMatricula = new SituacaoMatricula;

        $tiposAtendimentoEspecializado = new TipoAtendimentoEspecializado;

        $pessoa = new Pessoa;
        //dd($turma->id_turma);
        //dd($this->repositorio->quantMatriculas($turma->fk_id_turma));
        return view('secretaria.paginas.matriculas.create', [
                    'alunos'                => $pessoa->alunosNaoMatriculados($turma->fk_id_ano_letivo),
                    'responsaveis'          => $pessoa->getResponsaveis(),
                    'turma'                 => $turma,
                    'formasPagto'           => $formasPagto->getFormasPagamento(),
                    'tiposDesconto'         => $tiposDesconto->getTiposDescontoCurso(),
                    'situacoesMatricula'    => $situacoesMatricula->getSituacoesMatricula(),
                    'quantMatriculas'       => $this->repositorio->quantMatriculasTurma($turma->id_turma),
                    'quantVagasDisponiveis' => $this->repositorio->quantVagasDisponiveis($turma->id_turma),
                    'tiposAtendimentoEspecializado' => $tiposAtendimentoEspecializado->getTiposAtendimentoEspecializado(),
        ]);
    }

    public function store(StoreUpdateMatricula $request )
    {
        $dados = $request->all();
        /* $sit = $this->verificarSituacao($dados);
        $dados = array_merge($dados, $sit); */
        //dd($request->fk_id_turma);
        $this->repositorio->create($dados);

        return redirect()->route('matriculas.index', $request->fk_id_turma)->with('sucesso', 'Matrícula efetuada com sucesso.');
    }

    public function show($id)
    {
        //$matricula = $this->repositorio->where('id_matricula', $id)->first();
        $this->authorize('Matrícula Ver');   

        $matricula = $this->repositorio
                            ->select('tb_matriculas.*', 
                                        'aluno.nome as nome_aluno', 'aluno.foto',
                                        'respons.nome as nome_responsavel',
                                        'respons.telefone_1',
                                        'tb_turmas.nome_turma',
                                        'tb_anos_letivos.ano',
                                        'tb_turnos.descricao_turno',                                        
                                        'user_cadastro.name as nome_user_cadastro',
                                        'user_altera.name as nome_user_altera'
                                    )
                            ->join('tb_pessoas as aluno', 'aluno.id_pessoa', '=', 'fk_id_aluno')
                            ->join('tb_pessoas as respons', 'respons.id_pessoa', '=', 'fk_id_responsavel')
                            
                            ->leftJoin('users as user_cadastro', 'user_cadastro.id', 'tb_matriculas.fk_id_user_cadastro')
                            ->leftJoin('users as user_altera', 'user_altera.id', 'tb_matriculas.fk_id_user_altera')
                            ->join('tb_turmas', 'tb_turmas.id_turma', '=', 'tb_matriculas.fk_id_turma')
                            ->join('tb_tipos_turmas', 'tb_tipos_turmas.id_tipo_turma', '=', 'tb_turmas.fk_id_tipo_turma')
                            ->join('tb_anos_letivos', 'tb_anos_letivos.id_ano_letivo', '=', 'tb_tipos_turmas.fk_id_ano_letivo')
                            ->join('tb_turnos', 'tb_turnos.id_turno', '=', 'tb_turmas.fk_id_turno')                            
                            ->where('id_matricula', $id)
                            ->where('tb_anos_letivos.fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
                            ->first();

        if (!$matricula)
            return redirect()->back();

        return view('secretaria.paginas.matriculas.show', [
            'matricula' => $matricula
        ]);
    }

    public function destroy($id)
    {
        $matricula = $this->repositorio->where('id_matricula', $id)->first();

        if (!$matricula)
            return redirect()->back();

        $matricula->where('id_matricula', $id)->delete();
        return redirect()->route('matriculas.index');
    }

    public function search(Request $request)
    {
        $filtros = $request->except('_token');
        $matriculas = $this->repositorio->search($request->filtro);
        
        return view('secretaria.paginas.matriculas.index', [
                    'matricula' => $matriculas,
                    'filtros' => $filtros,
        ]);
    }

    public function edit($id)
    {
        $this->authorize('Matrícula Alterar');   
        
        $alunos = Pessoa::select('id_pessoa', 'nome')
                    ->where('fk_id_tipo_pessoa', '=', '1')
                    ->where('situacao_pessoa', 1)
                    ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
                    ->orderBy('nome')
                    ->get();

        $responsaveis = Pessoa::select('id_pessoa', 'nome')
                        ->where('fk_id_tipo_pessoa', '=', '2')
                        ->where('situacao_pessoa', 1)
                        ->orderBy('nome')
                        ->get();

        $formasPagto = FormaPagamento::select('*')->orderBy('forma_pagamento')->get();

        $tiposDesconto = TipoDescontoCurso::select('*')->orderBy('tipo_desconto_curso')->get();

        $situacoesMatricula = SituacaoMatricula::select('*')->orderBy('situacao_matricula')->get();

        $tiposAtendimentoEspecializado = new TipoAtendimentoEspecializado;

        $matricula = $this->repositorio
                                ->select('tb_matriculas.*', 
                                        'tb_turmas.nome_turma', 
                                        'tb_anos_letivos.ano', 
                                        'tb_turnos.descricao_turno', 
                                        'tb_sub_niveis_ensino.sub_nivel_ensino',
                                        'aluno.nome as nome_aluno', 'aluno.id_pessoa as id_aluno', 'aluno.foto',
                                        'responsavel.nome as nome_responsavel', 'responsavel.id_pessoa as id_responsavel',                                        
                                        'tb_situacoes_matricula.*',
                                        'tb_tipos_turmas.valor_curso') 
                                ->join('tb_pessoas as aluno', 'aluno.id_pessoa', 'tb_matriculas.fk_id_aluno')
                                ->join('tb_pessoas as responsavel', 'responsavel.id_pessoa', 'tb_matriculas.fk_id_responsavel')
                                ->join('tb_situacoes_matricula', 'tb_situacoes_matricula.id_situacao_matricula', 'tb_matriculas.fk_id_situacao_matricula')                                
                                ->join('tb_turmas', 'tb_turmas.id_turma', 'tb_matriculas.fk_id_turma')                               
                                ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma' )
                                ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
                                ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
                                ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno')   
                                ->where('tb_anos_letivos.fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())      
                                ->where('id_matricula', $id)->first();

/*                                 ->join('tb_formas_pagamento as forma_pagto_matricula', 'forma_pagto_matricula.id_forma_pagamento', 'tb_matriculas.fk_id_forma_pagto_matricula')
                                ->join('tb_formas_pagamento as forma_pagto_curso', 'forma_pagto_curso.id_forma_pagamento', 'tb_matriculas.fk_id_forma_pagto_curso')
                                ->join('tb_formas_pagamento as forma_pagto_mat_did', 'forma_pagto_mat_did.id_forma_pagamento', 'tb_matriculas.fk_id_forma_pagto_mat_didatico') */
        //dd($matricula);
        if (!$matricula)
            return redirect()->back();
                
        return view('secretaria.paginas.matriculas.edit',[
                        'matricula'          => $matricula,
                        'alunos'             => $alunos,
                        'responsaveis'       => $responsaveis,
                        'formasPagto'        => $formasPagto,
                        'tiposDesconto'      => $tiposDesconto,
                        'situacoesMatricula' => $situacoesMatricula,
                        'tiposAtendimentoEspecializado' => $tiposAtendimentoEspecializado->getTiposAtendimentoEspecializado(),
        ]);
    }

    public function update(StoreUpdateMatricula $request, $id)
    {
        $matricula = $this->repositorio->where('id_matricula', $id)->first();

        if (!$matricula)
            return redirect()->back();
        
        /* $sit = $this->verificarSituacao($request->all());
        
        $request->merge($sit); */

        $matricula->where('id_matricula', $id)->update($request->except('_token', '_method'));

        return redirect()->route('matriculas.index', $matricula->fk_id_turma);
    }

    public function imprimirContrato($id_matricula)
    {
        $this->authorize('Matrícula Contrato Ver');  
        $matricula = $this->repositorio->where('id_matricula', $id_matricula)->first();
        /* $corpoContrato = new CorpoContrato;
        $corpoContrato = $corpoContrato->where('fk_id_unidade_ensino', $matricula->turma->tipoTurma->anoLetivo->fk_id_unidade_ensino)->first(); */

        $unidadeEnsino = new UnidadeEnsino;
        $unidadeEnsino = $unidadeEnsino->where('id_unidade_ensino', $matricula->turma->tipoTurma->anoLetivo->fk_id_unidade_ensino)->first();
        
       /*  $pdf = PDF::loadView('secretaria.paginas.matriculas.contrato', compact('matricula', 'corpoContrato', 'unidadeEnsino')); */
        //$pdf = PDF

        /* return $pdf->setPaper('a4')->stream('contrato.pdf'); */
        return view('secretaria.paginas.matriculas.contrato', [
            'matricula' => $matricula,
            /* 'corpoContrato' => $corpoContrato, */
            'unidadeEnsino' =>$unidadeEnsino,
        ]);
    }

    
    public function imprimirReqMatricula($id_matricula)
    {
        $this->authorize('Matrícula Contrato Ver');  
        $matricula = $this->repositorio->where('id_matricula', $id_matricula)->first();
        
        $unidadeEnsino = new UnidadeEnsino;
        $unidadeEnsino = $unidadeEnsino->where('id_unidade_ensino', $matricula->turma->tipoTurma->anoLetivo->fk_id_unidade_ensino)->first();
        
        return view('secretaria.paginas.matriculas.requerimento_matricula', [
            'matricula' => $matricula,        
            'unidadeEnsino' =>$unidadeEnsino,
        ]);
    }

    public function imprimirFichaMatricula($id_aluno)
    {
        $this->authorize('Matrícula Ver');

        $matricula = $this->repositorio->where('fk_id_aluno', $id_aluno)->first();  

        $matriculas = $this->repositorio
                                ->select('tb_matriculas.*', 
                                        'tb_turmas.nome_turma', 
                                        'tb_anos_letivos.ano', 
                                        'tb_turnos.descricao_turno', 
                                        'tb_sub_niveis_ensino.sub_nivel_ensino',
                                        'aluno.nome as nome_aluno', 'aluno.id_pessoa as id_aluno', 'aluno.foto',
                                        'responsavel.nome as nome_responsavel', 'responsavel.id_pessoa as id_responsavel',                                        
                                        'tb_situacoes_matricula.*',
                                        'tb_tipos_turmas.valor_curso') 
                                ->join('tb_pessoas as aluno', 'aluno.id_pessoa', 'tb_matriculas.fk_id_aluno')
                                ->join('tb_pessoas as responsavel', 'responsavel.id_pessoa', 'tb_matriculas.fk_id_responsavel')
                                ->join('tb_situacoes_matricula', 'tb_situacoes_matricula.id_situacao_matricula', 'tb_matriculas.fk_id_situacao_matricula')                                
                                ->join('tb_turmas', 'tb_turmas.id_turma', 'tb_matriculas.fk_id_turma')                               
                                ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma' )
                                ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
                                ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
                                ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno')                                 
                                ->where('fk_id_aluno', $id_aluno)->get();

        //dd($matriculas);
        $unidadeEnsino = new UnidadeEnsino;
        $unidadeEnsino = $unidadeEnsino->where('id_unidade_ensino', $matricula->turma->tipoTurma->anoLetivo->fk_id_unidade_ensino)->first();
        
       /*  $pdf = PDF::loadView('secretaria.paginas.matriculas.contrato', compact('matricula', 'corpoContrato', 'unidadeEnsino')); */
        //$pdf = PDF

        /* return $pdf->setPaper('a4')->stream('contrato.pdf'); */
        return view('secretaria.paginas.matriculas.ficha_matricula', [
            'matriculas' => $matriculas,            
            'unidadeEnsino' =>$unidadeEnsino,
        ]);
    }

    /*
    *Lista de todos alunos matriculados em um ano letivo
    *somente para popular combo
    */
    public function getAlunos($id_ano_letivo)
    {
        $alunos['data'] = $this->repositorio->select('id_matricula', 
                                            'nome')
                                ->join('tb_pessoas', 'id_pessoa', 'tb_matriculas.fk_id_aluno')                                                                
                                ->join('tb_turmas', 'tb_turmas.id_turma', 'tb_matriculas.fk_id_turma')                               
                                ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma' )                                
                                ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')                                     
                                ->where('id_ano_letivo', $id_ano_letivo)
                                ->where('tb_anos_letivos.fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
                                ->orderBy('nome')
                                ->get();

        echo json_encode($alunos);
        exit;
    }

    /*
    *Lista de todos alunos matriculados em uma turma
    *somente para popular combo
    */
    public function getAlunosTurma($id_turma)
    {
        $alunos['data'] = $this->repositorio->select('id_matricula', 
                                            'nome')
                                ->join('tb_pessoas', 'id_pessoa', 'tb_matriculas.fk_id_aluno') 
                                ->join('tb_turmas', 'tb_turmas.id_turma', 'tb_matriculas.fk_id_turma')                               
                                ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma' )                                
                                ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')                                                                
                                ->where('tb_matriculas.fk_id_turma', $id_turma)
                                ->where('tb_anos_letivos.fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
                                ->orderBy('nome')
                                ->get();

        echo json_encode($alunos);
        exit;
    }

    /*
    *Consulta valores de uma matrícula
    *Valores matrícula, curso e material didático    
    */
    public function getValores($id_matricula)
    {
        $valores['data'] = $this->repositorio->select('tb_matriculas.valor_matricula', 
                                            'valor_curso',
                                            'valor_desconto',
                                            'qt_parcelas_curso',
                                            'data_venc_parcela_um',
                                            'tb_matriculas.valor_material_didatico',
                                            'qt_parcelas_mat_didatico',
                                            'obs_matricula',
                                            )   
                                ->join('tb_turmas', 'tb_turmas.id_turma', 'tb_matriculas.fk_id_turma')                               
                                ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', 'tb_tipos_turmas.id_tipo_turma' )                             
                                ->where('id_matricula', $id_matricula)   
                                ->get();

        echo json_encode($valores);
        exit;
    }

    /**
     * Verifica se a situação foi ativada
     */
    /* public function verificarSituacao(array $dados)
    {
        if (!array_key_exists('situacao_disciplina', $dados))
            return ['situacao_disciplina' => '0'];
        else
             return ['situacao_disciplina' => '1'];            
    } */
}
