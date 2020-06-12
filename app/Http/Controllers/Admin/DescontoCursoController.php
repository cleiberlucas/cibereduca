<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateDescontoCurso;
use App\Models\TipoDescontoCurso;
use Illuminate\Http\Request;

class DescontoCursoController extends Controller
{
    private $repositorio;
    
    public function __construct(TipoDescontoCurso $descontoCurso)
    {
        $this->repositorio = $descontoCurso;

    }

    public function index()
    {
        $descontosCursos = $this->repositorio->orderBy('tipo_desconto_curso')->paginate();
        
        return view('admin.paginas.descontoscursos.index', [
                    'descontosCursos' => $descontosCursos,
        ]);
    }

    public function create()
    {
        $this->authorize('Tipo Desconto Curso Cadastrar');       
        return view('admin.paginas.descontoscursos.create');
    }

    public function store(StoreUpdateDescontoCurso $request )
    {
        $dados = $request->all();
        
        $this->repositorio->create($dados);

        return redirect()->route('descontoscursos.index');
    }

    public function show($id)
    {
        $descontoCurso = $this->repositorio->where('id_tipo_desconto_curso', $id)->first();

        if (!$descontoCurso)
            return redirect()->back();

        return view('admin.paginas.descontoscursos.show', [
            'descontoCurso' => $descontoCurso
        ]);
    }

    public function destroy($id)
    {
        $descontoCurso = $this->repositorio->where('id_tipo_desconto_curso', $id)->first();

        if (!$descontoCurso)
            return redirect()->back();

        $descontoCurso->where('id_tipo_desconto_curso', $id)->delete();
        return redirect()->route('descontoscursos.index');
    }

    public function search(Request $request)
    {
        $filtros = $request->except('_token');
        $descontosCursos = $this->repositorio->search($request->filtro);
        
        return view('admin.paginas.descontoscursos.index', [
            'descontoCurso' => $descontosCursos,
            'filtros' => $filtros,
        ]);
    }

    public function edit($id)
    {
        $this->authorize('Tipo Desconto Curso Alterar');       
        $descontoCurso = $this->repositorio->where('id_tipo_desconto_curso', $id)->first();
        
        if (!$descontoCurso)
            return redirect()->back();
                
        return view('admin.paginas.descontoscursos.edit',[
            'descontoCurso' => $descontoCurso,
        ]);
    }

    public function update(StoreUpdateDescontoCurso $request, $id)
    {
        $descontoCurso = $this->repositorio->where('id_tipo_desconto_curso', $id)->first();

        if (!$descontoCurso)
            return redirect()->back();
        
        $descontoCurso->where('id_tipo_desconto_curso', $id)->update($request->except('_token', '_method'));

        return redirect()->route('descontoscursos.index');
    }

}
