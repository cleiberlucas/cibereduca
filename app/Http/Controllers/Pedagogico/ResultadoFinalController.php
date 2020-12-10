<?php

namespace App\Http\Controllers\Pedagogico;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateNota;
use App\Models\GradeCurricular;
use App\Models\Pedagogico\ResultadoFinal;
use App\Models\Pedagogico\TipoResultadoFinal;
use App\Models\Secretaria\Matricula;
use App\Models\Secretaria\Turma;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResultadoFinalController extends Controller
{
    private $repositorio, $resultadoAlunoPeriodo;
        
    public function __construct(ResultadoFinal $resultadoFinal)
    {
        $this->repositorio = $resultadoFinal;                
    }

    /**
     * Lista Turmas para lançamento de resultado final
     */
    public function indexResultadoFinal()
    {
        $this->authorize('Resultado Final Ver');   
        
        $idUnidade = User::getUnidadeEnsinoSelecionada();
        $perfilUsuario = new User;        
        $perfilUsuario = $perfilUsuario->getPerfilUsuarioUnidadeEnsino($idUnidade, Auth::id());

        if(!$perfilUsuario)
            return redirect()->back();

        /* Se for professor, listar somente a turma dele */
        if ($perfilUsuario->fk_id_perfil == 2){
            $turmas = Turma::select ('id_turma', 'nome_turma', 'sub_nivel_ensino', 'descricao_turno', 'ano')
                ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma' )
                ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
                ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
                ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno')        
                ->join('tb_turmas_disciplinas_professor', 'tb_turmas_disciplinas_professor.fk_id_turma', 'id_turma' )                                                    
                ->where('tb_anos_letivos.fk_id_unidade_ensino', '=', User::getUnidadeEnsinoSelecionada())         
                ->where('fk_id_professor', Auth::id())   
                ->where('situacao_disciplina_professor', 1)             
                ->groupBy('id_turma')
                ->groupBy('nome_turma')
                ->groupBy('sub_nivel_ensino')
                ->groupBy('descricao_turno')
                ->groupBy('ano')                
                ->paginate(25);
        }
        //para os demais usuários (com permissão é claro) listar todas as turmas
        else{
            $turmas = Turma::select ('*')
                            ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma' )
                            ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
                            ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
                            ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno')                                                            
                            ->where('tb_anos_letivos.fk_id_unidade_ensino', '=', User::getUnidadeEnsinoSelecionada())                             
                            ->orderBy('tb_anos_letivos.ano', 'desc')
                            ->orderBy('tb_sub_niveis_ensino.sub_nivel_ensino', 'asc')
                            ->orderBy('nome_turma', 'asc')
                            ->orderBy('tb_turnos.descricao_turno', 'asc')
                            ->paginate(25);
        }

       //dd($turmas);
        return view('pedagogico.paginas.resultadofinal.index_turmas', [
                    'turmas' => $turmas,       
        ]);
    }

    /**
     * Gera abas de períodos letivos e disciplinas para lançamento de notas
     */
    public function index($id_turma, $mensagem = null)
    {
        $this->authorize('Resultado Final Cadastrar');   
        //dd($id_turma);

        $turma = Turma::where('id_turma', $id_turma)->first();
        if (!$turma)
            return redirect()->back()->with('erro', 'Turma não encontrada');
        
        $perfilUsuario = new User;        
        $perfilUsuario = $perfilUsuario->getPerfilUsuarioUnidadeEnsino(User::getUnidadeEnsinoSelecionada(), Auth::id());
        
        $disciplinasTurma = new GradeCurricular;
        /* Perfil de professor: carregar somente disciplinas vinculadas a ele */
        if ($perfilUsuario->fk_id_perfil == 2){
            $disciplinasTurma = $disciplinasTurma->disciplinasTurmaProfessor($id_turma, Auth::id());
        }
        /* para os outros perfis libera todas as disciplinas */
        else{            
            //Somente disciplinas vinculadas à grade curricular da turma            
            $disciplinasTurma = $disciplinasTurma->disciplinasTurma($id_turma);            
        } 

       // dd($disciplinasTurma);
        if (count($disciplinasTurma) <= 0 or !$disciplinasTurma)
            return redirect()->back()->with('atencao', 'Não há grade curricular cadastrada para esta turma.');
        
        $tiposResultados = TipoResultadoFinal::where('situacao', 1)->get();

        $resultadosPeriodos = DB::table('tb_resultados_alunos_periodos')        
            ->select(DB::raw('fk_id_matricula, fk_id_disciplina'), DB::raw('sum(nota_media) as media, sum(total_faltas) as faltas'))
            ->groupBy(DB::raw('fk_id_matricula') )
            ->groupBy(DB::raw('fk_id_disciplina') )
            ->join('tb_matriculas', 'fk_id_matricula', 'id_matricula')             
            ->where('fk_id_turma', $id_turma)           
            ->get();

        $resultadosFinaisTurma = $this->repositorio
            ->select('tb_resultado_final.*')
            ->join('tb_matriculas', 'fk_id_matricula', 'id_matricula')
            ->where('fk_id_turma', $id_turma)
            ->get();
            
        //dd($resultadosFinaisTurma);

        return view('pedagogico.paginas.resultadofinal.index', [
            'id_turma' => $id_turma,
            'disciplinasTurma' => $disciplinasTurma,            
            'turmaMatriculas'      => $this->getTurmaMatriculas($id_turma),      
            'turma' => $turma,  
            'resultados' => $resultadosPeriodos,    
            'tiposResultados' => $tiposResultados,
            'resultadosFinais' => $resultadosFinaisTurma,
            'sucesso' => $mensagem,
           
        ]); 
    }
    
    public function store(StoreUpdateNota $request)
    {
        $this->authorize('Resultado Final Cadastrar');

        $dados = $request->all();
        $id_turma = $dados['fk_id_turma'];
        
        if (in_array(null, $dados['fk_id_tipo_resultado_final']))
            return redirect()->back()->with('atencao', 'Escolha o tipo de resultado para todos os alunos.');

       $resultadosFinais = [];

        foreach($dados['fk_id_tipo_resultado_final'] as $index => $resultadoFinal){ 
            $resultadosFinais = [];
            
            //dd($dados['fk_id_user']);
           
            /* Preparando array p gravar resultados finais                
            */                            
            if ($resultadoFinal != null)          
            {                   
                $resultadosFinais['fk_id_matricula'] = $dados['fk_id_matricula'][$index];
                $resultadosFinais['fk_id_tipo_resultado_final'] = $dados['fk_id_tipo_resultado_final'][$index];                
                $resultadosFinais['fk_id_user']      = $dados['fk_id_user'];

                //dd($resultadosFinais);
            }
            
            if (count($resultadosFinais) > 0)
            {
                try {
                    $this->repositorio->create($resultadosFinais); 

                } catch (QueryException $qe) {
                    $resultadoAluno = $this->repositorio->where('fk_id_matricula', $resultadosFinais['fk_id_matricula'])->first();
                    if (isset($resultadoAluno))
                        return redirect()->route('resultadofinal.index', $id_turma)->with('erro', 'Houve erro ao gravar o resultado final. A resultado final do(a) aluno(a) já foi lançado anteriormente.'); 
                }
            }
        }
       
        return redirect()->route('resultadofinal.index.turmas')->with('sucesso', 'Resultados Finais lançados com sucesso.');        
    }

    public function edit($fk_id_matricula)
    {
        $resultadoFinal = $this->repositorio->where('fk_id_matricula', $fk_id_matricula)
            ->first();

        $matricula = new Matricula;
        $matricula = $matricula->where('id_matricula', $fk_id_matricula)->first();

        if (!$matricula)
            return redirect()->back()->with('erro', 'Resultado final não encontrado');

        $tiposResultados = TipoResultadoFinal::where('situacao', 1)->get();

        //dd($matricula);
        return view('pedagogico.paginas.resultadofinal.edit', [
            'resultadoFinal' => $resultadoFinal,
            'matricula' => $matricula,
            'tiposResultados' => $tiposResultados,
        ]);
    }

    public function update(StoreUpdateNota $request, $id)
    {        
        $this->authorize('Resultado Final Alterar');
        
        $resultadoFinal = $this->repositorio->where('id_resultado_final', $id)->first();
        
        if (!$resultadoFinal)
            return redirect()->back()->with('erro', 'Resultado final não encontrado');
      
        $resultadoFinal->where('id_resultado_final', $id)->update($request->except('_token', '_method', 'id_turma'));

        return $this->index($request->id_turma, 'Resultado Final alterado com sucesso.');
    }

    public function search(Request $request)
    {
        $filtros = $request->except('_token');

        $turmas = new Turma;
        $turmas = $turmas->searchTurmaNotas($request->filtro);
        
        return view('pedagogico.paginas.resultadofinal.index_turmas', [
            'turmas' => $turmas,       
            'filtros' => $filtros,
        ]);
    }

    /**
     * Retorna todas as matrículas de uma turma
     */
    public function getTurmaMatriculas($id_turma)    
    {
        $matricula = new Matricula;
        $turmaMatriculas = $matricula->select('id_matricula',
                                                'tb_pessoas.nome')                                    
                                    ->join('tb_pessoas', 'fk_id_aluno', 'id_pessoa')
                                    ->where('tb_matriculas.fk_id_turma', '=', $id_turma)
                                    ->orderBy('tb_pessoas.nome')
                                    ->get();

        return $turmaMatriculas;
    }

}
