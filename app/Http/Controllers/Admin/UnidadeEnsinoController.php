<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateUnidadeEnsino;
use App\Models\UnidadeEnsino;
use App\Models\User;
use App\Models\UserUnidadeEnsino;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnidadeEnsinoController extends Controller
{
    private $repositorio, $userUnidadeEnsinoPermissao;

    public function __construct(UnidadeEnsino $unidadeEnsino)
    {
        $this->repositorio = $unidadeEnsino;
        $this->userUnidadeEnsinoPermissao = new UserUnidadeEnsino;
    }

    public function index()
    {
        $unidadesEnsino = $this->repositorio->paginate();
        //dd($unidadesEnsino);
        return view('admin.paginas.unidadesensino.index', [
                    'unidadesensino' => $unidadesEnsino,
        ]); 
    }

    public function create()
    {
       // dd(view('admin.paginas.unidadesensino.create'));
        return view('admin.paginas.unidadesensino.create');
    }
    
    public function unidadeDefinida()
    {        
        return view('admin.paginas.unidadesensino.definida');
    }

    public function unidadeEnsino($id_unidade_ensino)
    {                
        return $this->repositorio->find($id_unidade_ensino);
    }

    public function unidadesEnsinoAtivas()
    {
        //dd($this->repositorio->all());        
        return $this->repositorio->where('situacao', '=', '1')
                                 ->where('situacao_vinculo', '=', '1')                         
                                 ->where('tb_usuarios_unidade_ensino.fk_id_user', '=', Auth::id())        
                                    ->join('tb_usuarios_unidade_ensino', 'fk_id_unidade_ensino', 'id_unidade_ensino')                                    
                                    ->get();
    }

    public function store(StoreUpdateUnidadeEnsino $request )
    {
        $dados = $request->all();        

        $sit = $this->verificarSituacao($dados);
        $dados = array_merge($dados, $sit);

        $this->repositorio->create($dados);

        return redirect()->route('unidadesensino.index');
    }

    public function show($id)
    {
        $unidadeEnsino = $this->repositorio->where('id_unidade_ensino', $id)->first();

        if (!$unidadeEnsino)
            return redirect()->back();

        return view('admin.paginas.unidadesensino.show', [
            'UnidadeEnsino' => $unidadeEnsino
        ]);
    }

    public function destroy($id)
    {
        $unidadeEnsino = $this->repositorio->where('id_unidade_ensino', $id)->first();

        if (!$unidadeEnsino)
            return redirect()->back();

        $unidadeEnsino->where('id_unidade_ensino', $id)->delete();
        return redirect()->route('unidadesensino.index');
    }

    public function search(Request $request)
    {
        $filtros = $request->except('_token');
        $unidadesEnsino = $this->repositorio->search($request->filtro);
        
        return view('admin.paginas.unidadesensino.index', [
            'unidadesensino' => $unidadesEnsino,
            'filtros' => $filtros,
        ]);
    }

    public function edit($id)
    {
        $unidadeEnsino = $this->repositorio->where('id_unidade_ensino', $id)->first();

        if (!$unidadeEnsino)
            return redirect()->back();
                
        return view('admin.paginas.unidadesensino.edit',[
            'unidadeensino' => $unidadeEnsino,
        ]);
    }

    public function update(StoreUpdateUnidadeEnsino $request, $id)
    {
        $unidadeEnsino = $this->repositorio->where('id_unidade_ensino', $id)->first();

        if (!$unidadeEnsino)
            return redirect()->back();

        $sit = $this->verificarSituacao($request->all());
        
        $request->merge($sit);

        $unidadeEnsino->where('id_unidade_ensino', $id)->update($request->except('_token', '_method'));

        return redirect()->route('unidadesensino.index')->with('info', 'Unidade de Ensino alterada com sucesso.');;
    }

    /**
     * Verifica se a situação foi ativada
     */
    public function verificarSituacao(array $dados)
    {
        if (!array_key_exists('situacao', $dados))
            return ['situacao' => '0'];
        else
             return ['situacao' => '1'];            
    } 

}
