<?php

namespace App\Http\Controllers\Pedagogico;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateConteudoLecionado;
use App\Models\GradeCurricular;
use App\Models\Pedagogico\ConteudoLecionado;
use App\Models\Pedagogico\TurmaPeriodoLetivo;
use App\Models\Secretaria\Turma;
use App\Models\Secretaria\TurmaProfessor;
use App\User;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConteudoLecionadoController extends Controller
{
    private $repositorio;
        
    public function __construct(ConteudoLecionado $conteudoLecionado)
    {
        $this->repositorio = $conteudoLecionado;        
    }

    public function index($id_turma, $id_periodo_letivo = null, $id_disciplina = null)
    {
        $this->authorize('Conteúdo Lecionado Ver');   

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
        
        $turmaPeriodoLetivo = new TurmaPeriodoLetivo;

        return view('pedagogico.paginas.turmas.conteudoslecionados.index', [
            'id_turma' => $id_turma,
            'disciplinasTurma' => $disciplinasTurma,
            'turmaPeriodosLetivos' => $turmaPeriodoLetivo->getTurmaPeriodosLetivos($id_turma),     
            'conteudosLecionados' => $this->repositorio->getConteudosLecionados($id_turma),
        ]); 
    }

    public function store(StoreUpdateConteudoLecionado $request)
    {
        $this->authorize('Conteúdo Lecionado Cadastrar');

        $dados = $request->all();
        $id_turma = $dados['fk_id_turma'];
       
        $this->repositorio->create($dados);
        
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

        $turmaPeriodoLetivo = new TurmaPeriodoLetivo;

        return view('pedagogico.paginas.turmas.conteudoslecionados.index', [
                        'id_turma' => $id_turma,
                        'disciplinasTurma'     => $disciplinasTurma,
                        'turmaPeriodosLetivos' => $turmaPeriodoLetivo->getTurmaPeriodosLetivos($id_turma),     
                        'conteudosLecionados' => $this->repositorio->getConteudosLecionados($id_turma),
                        'selectPeriodoLetivo'  => $dados['id_periodo_letivo'],
                        'selectDisciplina'     =>  $dados['fk_id_disciplina'],     
                        'sucesso' => 'Conteúdo Lecionado cadastrado com sucesso.',                   
                       
        ]);         
    }

    public function update(StoreUpdateConteudoLecionado $request, $id)
    {        
        $this->authorize('Conteúdo Lecionado Alterar');

        $conteudoLecionado = $this->repositorio->where('id_conteudo_lecionado', $id)->first();
        
        if (!$conteudoLecionado)
            return redirect()->back();
      
        $conteudoLecionado->where('id_conteudo_lecionado', $id)->update($request->except('_token', '_method', 'fk_id_turma', 'id_periodo_letivo'));

        $dados = $request->all();
        $id_turma = $dados['fk_id_turma'];

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

        $turmaPeriodoLetivo = new TurmaPeriodoLetivo;

        //return redirect()->back();
       return view('pedagogico.paginas.turmas.conteudoslecionados.index', [
                    'id_turma' => $id_turma,
                    'disciplinasTurma'     => $disciplinasTurma,
                    'turmaPeriodosLetivos' => $turmaPeriodoLetivo->getTurmaPeriodosLetivos($id_turma),     
                    'conteudosLecionados' => $this->repositorio->getConteudosLecionados($id_turma),
                    'selectPeriodoLetivo'  => $dados['id_periodo_letivo'],
                    'selectDisciplina'     => $dados['fk_id_disciplina'],
                    'sucesso' => 'Conteúdo Lecionado alterado com sucesso.',
        ]);
    } 

    /**
     * Remover conteúdo lecionado
     */
    public function remover($id_conteudo_lecionado)
    {
        
        $this->authorize('Conteúdo Lecionado Remover');   
        
        $conteudoLecionado = $this->repositorio->where('id_conteudo_lecionado', $id_conteudo_lecionado, )->first();
        
        if (!$conteudoLecionado)
            return redirect()->back();

        $id_turma = $conteudoLecionado->turmaPeriodoLetivo->fk_id_turma;
        $id_periodo_letivo = $conteudoLecionado->turmaPeriodoLetivo->fk_id_periodo_letivo;
        $id_disciplina = $conteudoLecionado->fk_id_disciplina;

        $conteudoLecionado->where('id_conteudo_lecionado', $id_conteudo_lecionado, )->delete(); 

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
        $turmaPeriodoLetivo = new TurmaPeriodoLetivo;

        $idUnidade = User::getUnidadeEnsinoSelecionada();

        $perfilUsuario = new User;        
        $perfilUsuario = $perfilUsuario->getPerfilUsuarioUnidadeEnsino($idUnidade, Auth::id());
        $turmaDisciplinaProfessor = new TurmaProfessor;
        $turmaDisciplinaProfessor = $turmaDisciplinaProfessor->getTurmaDisciplinaProfessor($idUnidade, Auth::id());        
        
        return view('pedagogico.paginas.turmas.conteudoslecionados.index', [
                    'id_turma' => $id_turma,
                    'disciplinasTurma'     => $disciplinasTurma,
                    'turmaPeriodosLetivos' => $turmaPeriodoLetivo->getTurmaPeriodosLetivos($id_turma),     
                    'conteudosLecionados' => $this->repositorio->getConteudosLecionados($id_turma),
                    'perfilUsuario' => $perfilUsuario,
                    'selectPeriodoLetivo'  => $id_periodo_letivo,
                    'selectDisciplina'     => $id_disciplina,
                    'sucesso' => 'Conteúdo Lecionado removido com sucesso.',
        ]); 
    } 

    public function pdfFichaBranco(){
        $turma = Turma::where('id_turma', '1')->first();

        $pdf = PDF::loadView('pedagogico.paginas.turmas.conteudoslecionados.fichabranco', compact('turma'));

        return $pdf->setPaper('a4')->stream('ficha_branco.pdf');
    }

}
