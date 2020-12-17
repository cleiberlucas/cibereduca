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
        $this->authorize('Recuperação Final Ver');   

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
                    'recuperacoesFinais' => $recuperacoesFinais,                      
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

    public function destroy($id)
    {
        $this->authorize('Recuperação Final Remover');

        $recuperacaoFinal = $this->repositorio->where('id_recuperacao_final', $id)->first();

        if (!$recuperacaoFinal)
            return redirect()->back()->with('erro', 'Recuperação final não encontrada.');

        try {
            $recuperacaoFinal->where('id_recuperacao_final', $id)->delete();
        } catch (QueryException $qe) {
            return redirect()->back()->with('error', 'Houve erro ao excluir.');            
        }
        return redirect()->route('recuperacaofinal.index', $recuperacaoFinal->fk_id_tipo_turma)->with('sucesso', 'Recuperação final excluída com sucesso.');
    }

    public function edit($id)
    {       
        $this->authorize('Recuperação Final Alterar');

        $recuperacaoFinal = $this->repositorio->getRecuperacaoFinal($id);
        
        return view('pedagogico.paginas.recuperacaofinal.edit',
            compact('recuperacaoFinal')                    
        );
    }

    public function update(Request $request, $id)
    {
        $recuperacaoFinal = $this->repositorio->where('id_recuperacao_final', $id)->first();

        if (!$recuperacaoFinal)
            return redirect()->back()->with('erro', 'Recuperação final não encontrada.');
        
        $recuperacaoFinal->where('id_recuperacao_final', $id)->update($request->except('_token', '_method'));

        return redirect()->route( 'recuperacaofinal.index')->with('sucesso', 'Recuperação final alterada com sucesso.') ;
    }

}
