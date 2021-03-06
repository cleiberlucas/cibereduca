<?php

namespace App\Http\Controllers\Pedagogico;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateNota;
use App\Models\GradeCurricular;
use App\Models\Pedagogico\Avaliacao;
use App\Models\Pedagogico\Nota;
use App\Models\Pedagogico\ResultadoAlunoPeriodo;
use App\Models\Pedagogico\TurmaPeriodoLetivo;
use App\Models\Secretaria\Matricula;
use App\Models\Secretaria\Turma;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotaController extends Controller
{
    private $repositorio, $resultadoAlunoPeriodo;
        
    public function __construct(Nota $nota)
    {
        $this->repositorio = $nota;        
        $this->resultadoAlunoPeriodo = new ResultadoAlunoPeriodo;
    }

    /**
     * Lista Turmas para lançamento de notas
     */
    public function indexNotas()
    {
        $this->authorize('Nota Ver');   
        
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
        return view('pedagogico.paginas.turmas.notas.index_turmas', [
                    'turmas' => $turmas,       
        ]);
    }

    /**
     * Gera abas de períodos letivos e disciplinas para lançamento de notas
     */
    public function index($id_turma)
    {
        $this->authorize('Nota Cadastrar');   

        $turma = Turma::where('id_turma', $id_turma)->first();

        $idTipoTurma = $turma->fk_id_tipo_turma;
        
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

        $turmaPeriodoLetivo = new TurmaPeriodoLetivo;
        $avaliacoes = new Avaliacao;        
                
        return view('pedagogico.paginas.turmas.notas.index', [
            'id_turma' => $id_turma,
            'disciplinasTurma' => $disciplinasTurma,
            'turmaPeriodosLetivos' => $turmaPeriodoLetivo->getTurmaPeriodosLetivos($id_turma),     
            'turmaMatriculas'      => $this->getTurmaMatriculas($id_turma),
            'avaliacoes'           => $avaliacoes->getAvaliacoesTipoTurma($idTipoTurma),
           
        ]); 
    }

    public function edit($id_nota)
    {
        $nota = $this->repositorio->where('id_nota_avaliacao', $id_nota)                                    
                                    ->first();
        
        //dd($nota);
        return view('pedagogico.paginas.turmas.notas.edit', [
            'notaAluno' => $nota,
            
        ]);
    }

    public function update(StoreUpdateNota $request, $id)
    {        
        $this->authorize('Nota Alterar');
        
        $nota = $this->repositorio->where('id_nota_avaliacao', $id)->first();
        
        if (!$nota)
            return redirect()->back();
      
        $nota->where('id_nota_avaliacao', $id)->update($request->except('_token', '_method'));
        Log::channel('pedagogico_nota')->info('Usuário '.Auth::id(). ' - Nota Alterar '.$id. ' - Matrícula: '. $nota->fk_id_matricula. ' - Avaliação: '.$nota->fk_id_avaliacao. ' - Nota: '.$request->nota);  

        //$periodoLetivo = $this->repositorio->getPeriodoLetivo($id, $nota->fk_id_matricula, $nota->matricula->fk_id_turma, $nota->avaliacao->fk_id_periodo_letivo);

        /**Preparando array p atualizar a nota média do aluno */
        $dadosNota = array(['fk_id_periodo_letivo' => $nota->avaliacao->fk_id_periodo_letivo,                                 
                                'fk_id_matricula' => $nota->fk_id_matricula,
                                'fk_id_disciplina' => $nota->avaliacao->fk_id_disciplina
        ]);
        
        //Atualizar a nota média do aluno X período X disciplina
        $this->gravarNotasAlunoPeriodoDisciplina($dadosNota);

        return $this->notaShowAluno($nota->fk_id_matricula, 'Nota alterada com sucesso.');
    }

    public function store(StoreUpdateNota $request)
    {
        $this->authorize('Nota Cadastrar');

        $dados = $request->all();
        $id_turma = $dados['fk_id_turma'];

        $valorAvaliacao = new Avaliacao;
        $valorAvaliacao = $valorAvaliacao->where('id_avaliacao', $dados['fk_id_avaliacao'][0])->first();
       
        //Verificando se foi informado nota maior que o valor da avaliação aplicada
        foreach($dados['nota'] as $index => $nota){
            if ($nota != null && $nota > $valorAvaliacao->valor_avaliacao){
                return redirect()->back()->with('atencao', 'Lançamento de notas abortado. A nota para esta avaliação deve ser, no máximo, '.$valorAvaliacao->valor_avaliacao.'.
                                                        Nota lançada para um aluno: '.$nota.'.');
            }
        }
        
       $notasResultados = [];

        foreach($dados['nota'] as $index => $nota){ 
            $notas = [];
            
           // dd($dados);
            /* Preparando array p gravar notas 
               Só envia p gravar se a nota for preenchida
               pq o aluno pode faltar a prova
            */                            
            if ($nota != null)          
            {                   
                $notas['fk_id_matricula'] = $dados['fk_id_matricula'][$index];
                $notas['fk_id_avaliacao'] = $dados['fk_id_avaliacao'][0];
                $notas['data_avaliacao']  = $dados['data_avaliacao'];
                $notas['nota']            = $nota;                    
                $notas['fk_id_user']      = $dados['fk_id_user'];

                /**Gerando novo array p gravar a nota média do resultado aluno X período X disciplina */
                $notasMedias['fk_id_periodo_letivo'] = $dados['fk_id_periodo_letivo'];
                $notasMedias['fk_id_periodo_letivo'] = $dados['id_periodo_letivo'];
                $notasMedias['fk_id_matricula'] = $dados['fk_id_matricula'][$index];
                $notasMedias['fk_id_disciplina'] = $dados['fk_id_disciplina'];                
                
                $notasResultados[$index] = $notasMedias;
            }
            
            if (count($notas) > 0)
            {
                try {
                    $nt = $this->repositorio->create($notas); 
                    Log::channel('pedagogico_nota')->info('Usuário '.Auth::id(). ' - Nota Cadastrar '.$nt->id_nota_avaliacao. ' - Matrícula: '. $notas['fk_id_matricula']. ' - Avaliação: '.$notas['fk_id_avaliacao']. ' - Nota: '.$notas['nota']);  

                } catch (QueryException $qe) {
                    $notaAluno = $this->repositorio->where('fk_id_matricula', $notas['fk_id_matricula'])->first();
                    return redirect()->route('turmas.notas', $id_turma)->with('erro', 'Lançamento de Notas abortado. A nota do(a) aluno(a) '.$notaAluno->matricula->aluno->nome.' já foi lançada anteriormente.');
                }
            }
        }

        //Gravar a nota média do aluno no resultado do período
         $this->gravarNotasAlunoPeriodoDisciplina($notasResultados);
       
        return redirect()->route('turmas.notas', $id_turma)->with('sucesso', 'Notas lançadas com sucesso.');        
    }
    
    /**
     * Gravar soma das notas no período todas as vezes que:
     * Incluir
     * Alterar
     * Excluir     
     */
    public function gravarNotasAlunoPeriodoDisciplina(array $notas)
    {
        /**percorrendo a lista de aluno do form */
        foreach ($notas as $key => $nota) {
           
            /*Ler a soma de notas do aluno X periodo X disciplina */
            $nota_media = $this->repositorio->getNotasAlunoPeriodoDisciplina($nota['fk_id_matricula'], $nota['fk_id_periodo_letivo'], $nota['fk_id_disciplina']);
            
            /*Incluindo o total de faltas no array */
            $nota = array_merge($nota, ['nota_media' => $nota_media]);

            //dd($this->repositorio->getFaltasAlunoPeriodoDisciplina($fk_id_matricula, $fk_id_periodo_letivo, $fk_id_disciplina));    

            //Verificar se já foi lançado resultado para um período
            $existeResultado = $this->resultadoAlunoPeriodo->getResultadoAlunoPeriodoDisciplina($nota['fk_id_matricula'], $nota['fk_id_periodo_letivo'], $nota['fk_id_disciplina']);

            //Existe resultado lançado
            //ALTERAR SOMA NOTA NO RESULTADO DO PERIODO
            if ($existeResultado == 1)
                $this->alterarNotasResultadoAluno($nota);
            
            //Não existe resultado lançado
            //INSERIR SOMA NOTAS NO RESULTADO DO PERIODO
            else
                $this->inserirNotasResultadoAluno($nota);
        }
    }

    /**
     * Inserir nota média no resultado do aluno X periodo X disciplina
     * o array deve conter
     * ['fk_id_matricula'], 
     * ['fk_id_periodo_letivo'], 
     * ['fk_id_disciplina']
     * ['nota_media']
     */
    public function inserirNotasResultadoAluno(array $nota)
    {
        $this->resultadoAlunoPeriodo->create($nota);
    }

    /**
     * Alterar nota média no resultado do aluno X periodo X disciplina
     * o array deve conter
     * ['fk_id_matricula'], 
     * ['fk_id_periodo_letivo'], 
     * ['fk_id_disciplina']
     * ['nota_media']
     */
    public function alterarNotasResultadoAluno(array $nota)
    {
        try {
            $this->resultadoAlunoPeriodo->where('fk_id_matricula', $nota['fk_id_matricula'])
                                    ->where('fk_id_periodo_letivo', $nota['fk_id_periodo_letivo'])
                                    ->where('fk_id_disciplina', $nota['fk_id_disciplina'])
                                    ->update(array('nota_media' => $nota['nota_media']));
        } catch (\Throwable $th) {
            return redirect()->back()->with('erro', 'ATENÇÃO: houve erro ao calcular a média do aluno. FAVOR CONTATAR O SUPORTE TÉCNICO.');
        }
    }

    //rotina p atualização de nota média
    //tem algum problema no recálculo das médias
    public function atualizarTodasNotas(){
        //ler todas as notas gravadas
        $notas = $this->repositorio
            ->select('tb_notas_avaliacoes.fk_id_matricula', 'tb_avaliacoes.fk_id_periodo_letivo', 'tb_avaliacoes.fk_id_disciplina')
            ->join('tb_avaliacoes', 'fk_id_avaliacao', 'id_avaliacao')            
            ->groupBy('tb_notas_avaliacoes.fk_id_matricula', 'tb_avaliacoes.fk_id_periodo_letivo', 'tb_avaliacoes.fk_id_disciplina')
            ->get();

       //     dd($notas);
        
         /*Ler a soma de notas do aluno X periodo X disciplina */
         foreach($notas as $key => $nota){
             //dd($nota);
            $nota_media = $this->repositorio->getNotasAlunoPeriodoDisciplina($nota['fk_id_matricula'], $nota['fk_id_periodo_letivo'], $nota['fk_id_disciplina']);
            //dd($nota_media);

            $atualizarNota = array('fk_id_matricula' => $nota['fk_id_matricula'], 'fk_id_periodo_letivo' => $nota['fk_id_periodo_letivo'], 'fk_id_disciplina' =>  $nota['fk_id_disciplina'], 'nota_media' => $nota_media);
           // dd($atualizarNota);

             //Verificar se já foi lançado resultado para um período
          $existeResultado = $this->resultadoAlunoPeriodo->getResultadoAlunoPeriodoDisciplina($nota['fk_id_matricula'], $nota['fk_id_periodo_letivo'], $nota['fk_id_disciplina']);

          //Existe resultado lançado
          //ALTERAR SOMA NOTA NO RESULTADO DO PERIODO
          if ($existeResultado == 1)
              $this->alterarNotasResultadoAluno($atualizarNota);
          
          //Não existe resultado lançado
          //INSERIR SOMA NOTAS NO RESULTADO DO PERIODO
          else
              $this->inserirNotasResultadoAluno($atualizarNota);        

         }
         
    }

    //rotina p atualização de nota média da TURMA
    //tem algum problema no recálculo das médias qdo ta lançando, alterando ou excluindo
    public function atualizarNotasTurma($turma){
        //ler todas as notas gravadas
        $notas = $this->repositorio
            ->select('tb_notas_avaliacoes.fk_id_matricula', 'tb_avaliacoes.fk_id_periodo_letivo', 'tb_avaliacoes.fk_id_disciplina')
            ->join('tb_avaliacoes', 'fk_id_avaliacao', 'id_avaliacao')        
            ->join('tb_matriculas', 'id_matricula', 'tb_notas_avaliacoes.fk_id_matricula')    
            ->where('fk_id_turma', $turma)
            ->groupBy('tb_notas_avaliacoes.fk_id_matricula', 'tb_avaliacoes.fk_id_periodo_letivo', 'tb_avaliacoes.fk_id_disciplina')
            ->get();

            //dd($turma);
        
         /*Ler a soma de notas do aluno X periodo X disciplina */
         foreach($notas as $key => $nota){
             //dd($nota);
            $nota_media = $this->repositorio->getNotasAlunoPeriodoDisciplina($nota['fk_id_matricula'], $nota['fk_id_periodo_letivo'], $nota['fk_id_disciplina']);
            //dd($nota_media);

            $atualizarNota = array('fk_id_matricula' => $nota['fk_id_matricula'], 'fk_id_periodo_letivo' => $nota['fk_id_periodo_letivo'], 'fk_id_disciplina' =>  $nota['fk_id_disciplina'], 'nota_media' => $nota_media);
           // dd($atualizarNota);

             //Verificar se já foi lançado resultado para um período
          $existeResultado = $this->resultadoAlunoPeriodo->getResultadoAlunoPeriodoDisciplina($nota['fk_id_matricula'], $nota['fk_id_periodo_letivo'], $nota['fk_id_disciplina']);

          //Existe resultado lançado
          //ALTERAR SOMA NOTA NO RESULTADO DO PERIODO
          if ($existeResultado == 1)
              $this->alterarNotasResultadoAluno($atualizarNota);
          
          //Não existe resultado lançado
          //INSERIR SOMA NOTAS NO RESULTADO DO PERIODO
          else
              $this->inserirNotasResultadoAluno($atualizarNota);    

         }
         return true;         
    }

    //rotina p atualização de nota média da TURMA em um PERIODO
    //tem algum problema no recálculo das médias qdo ta lançando, alterando ou excluindo
    public function atualizarNotasTurmaPeriodo($turma, $periodo){
        //ler todas as notas gravadas
        $notas = $this->repositorio
            ->select('tb_notas_avaliacoes.fk_id_matricula', 'tb_avaliacoes.fk_id_periodo_letivo', 'tb_avaliacoes.fk_id_disciplina')
            ->join('tb_avaliacoes', 'fk_id_avaliacao', 'id_avaliacao')        
            ->join('tb_matriculas', 'id_matricula', 'tb_notas_avaliacoes.fk_id_matricula')    
            ->where('fk_id_turma', $turma)
            ->where('fk_id_periodo_letivo', $periodo)
            ->groupBy('tb_notas_avaliacoes.fk_id_matricula', 'tb_avaliacoes.fk_id_periodo_letivo', 'tb_avaliacoes.fk_id_disciplina')
            ->get();

            //dd($turma);
        
         /*Ler a soma de notas do aluno X periodo X disciplina */
         foreach($notas as $key => $nota){
             //dd($nota);
            $nota_media = $this->repositorio->getNotasAlunoPeriodoDisciplina($nota['fk_id_matricula'], $nota['fk_id_periodo_letivo'], $nota['fk_id_disciplina']);
            //dd($nota_media);

            $atualizarNota = array('fk_id_matricula' => $nota['fk_id_matricula'], 'fk_id_periodo_letivo' => $nota['fk_id_periodo_letivo'], 'fk_id_disciplina' =>  $nota['fk_id_disciplina'], 'nota_media' => $nota_media);
           // dd($atualizarNota);

             //Verificar se já foi lançado resultado para um período
          $existeResultado = $this->resultadoAlunoPeriodo->getResultadoAlunoPeriodoDisciplina($nota['fk_id_matricula'], $nota['fk_id_periodo_letivo'], $nota['fk_id_disciplina']);

          //Existe resultado lançado
          //ALTERAR SOMA NOTA NO RESULTADO DO PERIODO
          if ($existeResultado == 1)
              $this->alterarNotasResultadoAluno($atualizarNota);
          
          //Não existe resultado lançado
          //INSERIR SOMA NOTAS NO RESULTADO DO PERIODO
          else
              $this->inserirNotasResultadoAluno($atualizarNota);    

         }
         return true;         
    }

    //rotina p atualização de nota média do ALUNO
    //tem algum problema no recálculo das médias qdo ta lançando, alterando ou excluindo
    public function atualizarNotasAluno($matricula){
        //ler todas as notas gravadas
        $notas = $this->repositorio
            ->select('tb_notas_avaliacoes.fk_id_matricula', 'tb_avaliacoes.fk_id_periodo_letivo', 'tb_avaliacoes.fk_id_disciplina')
            ->join('tb_avaliacoes', 'fk_id_avaliacao', 'id_avaliacao')        
            ->join('tb_matriculas', 'id_matricula', 'tb_notas_avaliacoes.fk_id_matricula')    
            ->where('id_matricula', $matricula)
            ->groupBy('tb_notas_avaliacoes.fk_id_matricula', 'tb_avaliacoes.fk_id_periodo_letivo', 'tb_avaliacoes.fk_id_disciplina')
            ->get();

            //dd($turma);
        
         /*Ler a soma de notas do aluno X periodo X disciplina */
         foreach($notas as $key => $nota){
             //dd($nota);
            $nota_media = $this->repositorio->getNotasAlunoPeriodoDisciplina($nota['fk_id_matricula'], $nota['fk_id_periodo_letivo'], $nota['fk_id_disciplina']);
            //dd($nota_media);

            $atualizarNota = array('fk_id_matricula' => $nota['fk_id_matricula'], 'fk_id_periodo_letivo' => $nota['fk_id_periodo_letivo'], 'fk_id_disciplina' =>  $nota['fk_id_disciplina'], 'nota_media' => $nota_media);
           // dd($atualizarNota);

             //Verificar se já foi lançado resultado para um período
          $existeResultado = $this->resultadoAlunoPeriodo->getResultadoAlunoPeriodoDisciplina($nota['fk_id_matricula'], $nota['fk_id_periodo_letivo'], $nota['fk_id_disciplina']);

          //Existe resultado lançado
          //ALTERAR SOMA NOTA NO RESULTADO DO PERIODO
          if ($existeResultado == 1)
              $this->alterarNotasResultadoAluno($atualizarNota);
          
          //Não existe resultado lançado
          //INSERIR SOMA NOTAS NO RESULTADO DO PERIODO
          else
              $this->inserirNotasResultadoAluno($atualizarNota);    

         }
         return true;         
    }

    
    public function notaShowAluno($id_matricula, $mensagem = null)
    {        
        $this->authorize('Nota Alterar');
        $notasAluno = $this->repositorio->getNotasAluno($id_matricula)->first();
        
        if (!$notasAluno){
            $id_turma = Matricula::select('fk_id_turma')->where('id_matricula', $id_matricula)->first();            
            return redirect()->route('turmas.notas', $id_turma->fk_id_turma)->with('info', 'Não há nota lançada para este aluno.');              
        }
        
        $id_turma = $notasAluno->matricula->fk_id_turma;
        $id_tipo_turma = $notasAluno->matricula->turma->fk_id_tipo_turma;

        //Todos os períodos que possuem avaliacao cadastrada
        $periodosTurma = $this->repositorio->getPeriodosTurma($id_tipo_turma);

        //Todas as avaliações cadastradas para a turma
        $avaliacoesTurma = $this->repositorio->getAvaliacoesTurma($id_tipo_turma);
        
        $idUnidade = User::getUnidadeEnsinoSelecionada();
        $perfilUsuario = new User;        
        $perfilUsuario = $perfilUsuario->getPerfilUsuarioUnidadeEnsino($idUnidade, Auth::id());
        
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
        
        $notasAluno = $this->repositorio->getNotasAluno($id_matricula);

        $dadosAluno = new Matricula;
        $dadosAluno = $dadosAluno->where('id_matricula', $id_matricula)->first();

        $turmaPeriodoLetivo = new TurmaPeriodoLetivo;
        
        return view('pedagogico.paginas.turmas.notas.showaluno', [
                    'id_turma'              => $id_turma,
                    'periodosTurma'         => $periodosTurma,
                    'turmaPeriodosLetivos' => $turmaPeriodoLetivo->getTurmaPeriodosLetivos($id_turma), 
                    'gradeCurricular'       => $disciplinasTurma,
                    'avaliacoesTurma'       => $avaliacoesTurma,
                    'notasAluno'            => $notasAluno,
                    'dadosAluno'            => $dadosAluno,
                    'sucesso'               => $mensagem,

        ]);
    } 

    /**
     * Remover nota
     */
    public function remover($id_nota)
    {
        $this->authorize('Nota Remover');   
        
        $notaAluno = $this->repositorio->where('id_nota_avaliacao', $id_nota, )->first();

       // $turmaPeriodoLetivo = $this->repositorio->getTurmaPeriodoLetivo($id_nota, $notaAluno->fk_id_matricula, $notaAluno->matricula->fk_id_turma, $notaAluno->avaliacao->fk_id_periodo_letivo);
        
        if (!$notaAluno)
            return redirect()->back();



       /**Preparando array p atualizar a nota média do aluno */
        $dadosNota = array([
                            'fk_id_periodo_letivo' => $notaAluno->avaliacao->fk_id_periodo_letivo, 
                            'fk_id_matricula' => $notaAluno->fk_id_matricula,
                            'fk_id_disciplina' => $notaAluno->avaliacao->fk_id_disciplina
        ]);

        $id_matricula = $notaAluno->fk_id_matricula;

        $notaAluno2 = $this->repositorio->where('id_nota_avaliacao', $id_nota, )->delete();        
        Log::channel('pedagogico_nota')->alert('Usuário '.Auth::id(). ' - Nota Remover '.$notaAluno->id_nota_avaliacao. ' - Matrícula: '. $notaAluno['fk_id_matricula']. ' - Avaliação: '.$notaAluno->fk_id_avaliacao. ' - Nota: '.$notaAluno->nota);  

        //Atualizar a nota média do aluno X período X disciplina
        $this->gravarNotasAlunoPeriodoDisciplina($dadosNota);
        
        return $this->notaShowAluno($id_matricula)->with('sucesso', 'Nota removida com sucesso.');
    } 

    public function search(Request $request)
    {
        $filtros = $request->except('_token');

        $turmas = new Turma;
        $turmas = $turmas->searchTurmaNotas($request->filtro);
        
        return view('pedagogico.paginas.turmas.notas.index_turmas', [
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
