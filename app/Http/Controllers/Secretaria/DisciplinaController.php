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
        $disciplinas = $this->repositorio->paginate();
        
        return view('secretaria.paginas.disciplinas.index', [
                    'disciplinas' => $disciplinas,
        ]);
    }

    public function create()
    {
       // dd(view('secretaria.paginas.disciplinas.create'));
        return view('secretaria.paginas.disciplinas.create');
    }

    public function store(StoreUpdateDisciplina $request )
    {
        $dados = $request->all();
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

        $disciplina->where('id_disciplina', $id)->update($request->except('_token', '_method'));

        return redirect()->route('disciplinas.index');
    }
}
