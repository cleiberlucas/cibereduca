<?php

namespace App\Http\Controllers\Pedagogico;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateRecuperacaoFinal;
use App\Models\AnoLetivo;
use App\Models\GradeCurricular;
use App\Models\Pedagogico\RecuperacaoFinal;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class RecuperacaoFinalController extends Controller
{
    private $repositorio;
        
    public function __construct(RecuperacaoFinal $recuperacaoFinal)
    {
        $this->repositorio = $recuperacaoFinal;        
        
    }
   
    /**
     * Lista de recuperações lançadas
     */
    public function index()
    {
        $this->authorize('Recuperação Cadastrar');   

        $recuperacoesFinais = $this->repositorio->getTodosRecuperacoesFinais();
                
        return view('pedagogico.paginas.recuperacaofinal.index', 
            compact('recuperacoesFinais')
        ); 
    }

    public function search(Request $request)
    {
        $filtros = $request->except('_token');

        $recuperacoesFinais = $this->repositorio->search($request->filtro);
                
        return view('pedagogico.paginas.recuperacaofinal.index', [
                    'avaliacoes' => $recuperacoesFinais,  
                    'tipoTurma'  => $request->id_tipo_turma,   
                    'filtros'    => $filtros,               
        ]);
    }

    public function create()
    {  
        $this->authorize('Recuperação Final Cadastrar');   
        
        $anosLetivos = AnoLetivo::where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
            ->orderBy('ano', 'desc')
            ->get();
       
        return view('pedagogico.paginas.recuperacaofinal.create', 
            compact('anosLetivos')
        );
    }

    public function store(Request $request )
    {
        $dados = $request->all();

        $dados['fk_id_disciplina'] = $dados['disciplina'];
        $dados['fk_id_user'] = Auth::id();
        unset($dados['disciplina']);
        unset($dados['anoLetivo']);
        unset($dados['turma']);

        //dd($dados);
        
        try {
            //code...
            $this->repositorio->create($dados);
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('atencao', 'Erro ao cadastrar a recuperação final.');
        }
 
        return redirect()->route('recuperacaofinal.index')->with('sucesso', 'Recuperação final cadastrada com sucesso.');
    }

    public function show($id)
    {
        $this->authorize('Avaliação Ver');   
        $avaliacao = $this->repositorio                            
                            ->where('id_avaliacao', $id)                            
                            ->first();
        //dd($avaliacao);

        if (!$avaliacao)
            return redirect()->back();

        return view('pedagogico.paginas.recuperacaofinal.show', [
            'avaliacao' => $avaliacao
        ]);
    }

    public function destroy($id)
    {
        $this->authorize('Avaliação Remover');

        $avaliacao = $this->repositorio->where('id_avaliacao', $id)->first();

        if (!$avaliacao)
            return redirect()->back();

        try {
            $avaliacao->where('id_avaliacao', $id)->delete();
        } catch (QueryException $qe) {
            return redirect()->back()->with('error', 'Existe nota lançada para esta avaliação. Não é possível excluir. ');            
        }
        return redirect()->route('recuperacaofinal', $avaliacao->fk_id_tipo_turma);
    }

    public function edit($id)
    {       
        $this->authorize('Avaliação Alterar');

        $avaliacao = $this->repositorio->where('id_avaliacao', $id)->first();
        //dd($avaliacao);
             
        if (!$avaliacao)
            return redirect()->back();
        
        $id_tipo_turma = $avaliacao->fk_id_tipo_turma;

        $periodosLetivos = new PeriodoLetivo;
        $periodosLetivos = $periodosLetivos->join('tb_tipos_turmas', 'tb_tipos_turmas.fk_id_ano_letivo', 'tb_periodos_letivos.fk_id_ano_letivo')                                            
                                            ->where('id_tipo_turma', $id_tipo_turma)
                                            ->where('tb_periodos_letivos.situacao', '1')
                                            ->orderBy('periodo_letivo')->get();

        $gradeCurricular = new GradeCurricular();
        $gradeCurricular = $gradeCurricular->disciplinasTipoTurma($id_tipo_turma);
        //dd($gradeCurricular);
        $tiposAvaliacao = new TipoAvaliacao;
        $tiposAvaliacao = $tiposAvaliacao->getTiposAvaliacao(1);
        $tipoTurma = TipoTurma::where('id_tipo_turma', $id_tipo_turma)->first();
        
        return view('pedagogico.paginas.recuperacaofinal.edit',[
                    'avaliacao' => $avaliacao,    
                    'tipoTurma' => $tipoTurma,
                    'periodosLetivos' => $periodosLetivos,
                    'gradeCurricular' => $gradeCurricular,
                    'tiposAvaliacao'  => $tiposAvaliacao,
        ]);
    }

    public function update(StoreUpdateAvaliacao $request, $id)
    {
        $avaliacao = $this->repositorio->where('id_avaliacao', $id)->first();

        if (!$avaliacao)
            return redirect()->back();
        
        $avaliacao->where('id_avaliacao', $id)->update($request->except('_token', '_method'));

        return redirect()->route( 'recuperacaofinal', $avaliacao->fk_id_tipo_turma) ;
    }



}
