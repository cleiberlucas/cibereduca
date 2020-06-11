<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateMatricula;
use App\Models\FormaPagamento;
use App\Models\Secretaria\Matricula;
use App\Models\Secretaria\Pessoa;
use App\Models\Secretaria\Turma;
use App\Models\Secretaria\TipoDescontoCurso;
use App\Models\SituacaoMatricula;
use App\Models\TipoAtendimentoEspecializado;
use App\Models\User;
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
        $matriculas = $this->repositorio->where('fk_id_turma', $request->segment(2))
                                        ->paginate();
        
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
                    'quantMatriculas'    => $this->repositorio->quantMatriculas($turma->id_turma),
                    'quantVagasDisponiveis' => $this->repositorio->quantVagasDisponiveis($turma->id_turma),
        ]);
    }

    public function create(Request $request)
    {   
        $turma = Turma::select('tb_turmas.nome_turma', 'tb_turmas.id_turma', 'tb_turmas.limite_alunos',
                        'tb_anos_letivos.ano', 'tb_turnos.descricao_turno', 'tb_sub_niveis_ensino.sub_nivel_ensino',
                        'tb_tipos_turmas.valor_curso', 'tb_tipos_turmas.fk_id_ano_letivo' )
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
                    'quantMatriculas'       => $this->repositorio->quantMatriculas($turma->id_turma),
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

        return redirect()->route('matriculas.index', $request->fk_id_turma);
    }

    public function show($id)
    {
        //$matricula = $this->repositorio->where('id_matricula', $id)->first();

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
