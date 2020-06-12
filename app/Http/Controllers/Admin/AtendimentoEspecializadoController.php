<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateAtendimentoEspecializado;
use App\Models\TipoAtendimentoEspecializado;
use Illuminate\Http\Request;

class AtendimentoEspecializadoController extends Controller
{
    private $repositorio;
    
    public function __construct(TipoAtendimentoEspecializado $atendimentoEspecializado)
    {
        $this->repositorio = $atendimentoEspecializado;

    }

    public function index()
    {
        $atendimentosEspecializados = $this->repositorio->paginate();
        
        return view('admin.paginas.atendimentosespecializados.index', [
                    'atendimentosEspecializados' => $atendimentosEspecializados,
        ]);
    }

    public function create()
    {
        $this->authorize('Tipo Atendimento Especializado Cadastrar');       
        return view('admin.paginas.atendimentosespecializados.create');
    }

    public function store(StoreUpdateAtendimentoEspecializado $request )
    {
        $dados = $request->all();
        
        $this->repositorio->create($dados);

        return redirect()->route('atendimentosespecializados.index');
    }

    public function show($id)
    {
        $atendimentoEspecializado = $this->repositorio->where('id_atendimento_especializado', $id)->first();

        if (!$atendimentoEspecializado)
            return redirect()->back();

        return view('admin.paginas.atendimentosespecializados.show', [
            'atendimentoEspecializado' => $atendimentoEspecializado
        ]);
    }

    public function destroy($id)
    {
        $atendimentoEspecializado = $this->repositorio->where('id_atendimento_especializado', $id)->first();

        if (!$atendimentoEspecializado)
            return redirect()->back();

        $atendimentoEspecializado->where('id_atendimento_especializado', $id)->delete();
        return redirect()->route('atendimentosespecializados.index');
    }

    public function search(Request $request)
    {
        $filtros = $request->except('_token');
        $atendimentosEspecializados = $this->repositorio->search($request->filtro);
        
        return view('admin.paginas.atendimentosespecializados.index', [
            'atendimentoEspecializado' => $atendimentosEspecializados,
            'filtros' => $filtros,
        ]);
    }

    public function edit($id)
    {
        $this->authorize('Tipo Atendimento Especializado Alterar');       
        $atendimentoEspecializado = $this->repositorio->where('id_atendimento_especializado', $id)->first();
        
        if (!$atendimentoEspecializado)
            return redirect()->back();
                
        return view('admin.paginas.atendimentosespecializados.edit',[
            'atendimentoEspecializado' => $atendimentoEspecializado,
        ]);
    }

    public function update(StoreUpdateAtendimentoEspecializado $request, $id)
    {
        $atendimentoEspecializado = $this->repositorio->where('id_atendimento_especializado', $id)->first();

        if (!$atendimentoEspecializado)
            return redirect()->back();
        
        $atendimentoEspecializado->where('id_atendimento_especializado', $id)->update($request->except('_token', '_method'));

        return redirect()->route('atendimentosespecializados.index');
    }

}
