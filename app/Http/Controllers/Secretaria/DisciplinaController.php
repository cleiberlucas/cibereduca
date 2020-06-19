<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateDisciplina;
use App\Models\Secretaria\Disciplina;
use Illuminate\Http\Request;

class DisciplinaController extends Controller
{
    private $repositorio;
    
    public function __construct(Disciplina $disciplina)
    {
        $this->repositorio = $disciplina;

    }

    public function index()
    {
        $disciplinas = $this->repositorio->orderBy('disciplina')->paginate();
        
        return view('secretaria.paginas.disciplinas.index', [
                    'disciplinas' => $disciplinas,
        ]);
    }

    public function create()
    {
        $this->authorize('Disciplina Cadastrar');
       // dd(view('secretaria.paginas.disciplinas.create'));
        return view('secretaria.paginas.disciplinas.create');
    }

    public function store(StoreUpdateDisciplina $request )
    {
        $dados = $request->all();
        $sit = $this->verificarSituacao($dados);
        $dados = array_merge($dados, $sit);
       // dd($dados);
        $this->repositorio->create($dados);

        return redirect()->route('disciplinas.index');
    }

    public function show($id)
    {
        $disciplina = $this->repositorio->where('id_disciplina', $id)->first();

        if (!$disciplina)
            return redirect()->back();

        return view('secretaria.paginas.disciplinas.show', [
            'disciplina' => $disciplina
        ]);
    }

    public function destroy($id)
    {
        $disciplina = $this->repositorio->where('id_disciplina', $id)->first();

        if (!$disciplina)
            return redirect()->back();

        $disciplina->where('id_disciplina', $id)->delete();
        return redirect()->route('disciplinas.index');
    }

    public function search(Request $request)
    {
        $filtros = $request->except('_token');
        $disciplinas = $this->repositorio->search($request->filtro);
        
        return view('secretaria.paginas.disciplinas.index', [
            'disciplina' => $disciplinas,
            'filtros' => $filtros,
        ]);
    }

    public function edit($id)
    {
        $this->authorize('Disciplina Alterar');
        $disciplina = $this->repositorio->where('id_disciplina', $id)->first();
        
        if (!$disciplina)
            return redirect()->back();
                
        return view('secretaria.paginas.disciplinas.edit',[
            'disciplina' => $disciplina,
        ]);
    }

    public function update(StoreUpdateDisciplina $request, $id)
    {
        $disciplina = $this->repositorio->where('id_disciplina', $id)->first();

        if (!$disciplina)
            return redirect()->back();
        
        $sit = $this->verificarSituacao($request->all());
        
        $request->merge($sit);

        $disciplina->where('id_disciplina', $id)->update($request->except('_token', '_method'));

        return redirect()->route('disciplinas.index');
    }

    /**
     * Verifica se a situação foi ativada
     */
    public function verificarSituacao(array $dados)
    {
        if (!array_key_exists('situacao_disciplina', $dados))
            return ['situacao_disciplina' => '0'];
        else
             return ['situacao_disciplina' => '1'];            
    }
}
