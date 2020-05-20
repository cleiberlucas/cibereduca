<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateMatricula;
use App\Models\Secretaria\Matricula;
use Illuminate\Http\Request;

class MatriculaController extends Controller
{
    private $repositorio;
    
    public function __construct(Matricula $matricula)
    {
        $this->repositorio = $matricula;

    }

    public function index(Request $request)
    {
        //$matriculas = $this->repositorio->where('fk_id_turma', $request->segment(2))->with('turma')->paginate();
        $matriculas = $this->repositorio
                            ->join('tb_turmas', 'tb_turmas.id_turma', '=', 'tb_matriculas.fk_id_turma')
                            ->join('tb_tipos_turmas', 'tb_tipos_turmas.id_tipo_turma', '=', 'tb_turmas.fk_id_tipo_turma')
                            ->join('tb_anos_letivos', 'tb_anos_letivos.id_ano_letivo', '=', 'tb_tipos_turmas.fk_id_ano_letivo')
                            ->select('tb_matriculas.*', 'tb_turmas.nome_turma', 'tb_anos_letivos.ano')
                            ->paginate();
                
        return view('secretaria.paginas.matriculas.index', [
                    'matriculas' => $matriculas,
        ]);
    }

    public function create()
    {
       // dd(view('secretaria.paginas.matriculas.create'));
        return view('secretaria.paginas.matriculas.create');
    }

    public function store(StoreUpdateMatricula $request )
    {
        $dados = $request->all();
        $sit = $this->verificarSituacao($dados);
        $dados = array_merge($dados, $sit);
       // dd($dados);
        $this->repositorio->create($dados);

        return redirect()->route('matriculas.index');
    }

    public function show($id)
    {
        //$matricula = $this->repositorio->where('id_matricula', $id)->first();

        $matricula = $this->repositorio
            ->join('tb_pessoas as aluno', 'aluno.id_pessoa', '=', 'fk_id_aluno')
            ->join('tb_pessoas as respons', 'respons.id_pessoa', '=', 'fk_id_responsavel')
            ->join('tb_turmas', 'tb_turmas.id_turma', '=', 'tb_matriculas.fk_id_turma')
            ->join('tb_tipos_turmas', 'tb_tipos_turmas.id_tipo_turma', '=', 'tb_turmas.fk_id_tipo_turma')
            ->join('tb_anos_letivos', 'tb_anos_letivos.id_ano_letivo', '=', 'tb_tipos_turmas.fk_id_ano_letivo')
            ->select('tb_matriculas.*', 'aluno.nome as nome_aluno', 'respons.nome as nome_responsavel', 'respons.telefone_1',
                     'tb_turmas.nome_turma', 'tb_anos_letivos.ano')
            ->where('id_matricula', $id)
            ->first();

        if (!$matricula)
            return redirect()->back();

        return view('secretaria.paginas.matriculas.show', [
            'matricula' => $matricula
        ]);
    }

    public function destroy($id)
    {
        $matricula = $this->repositorio->where('id_matricula', $id)->first();

        if (!$matricula)
            return redirect()->back();

        $matricula->where('id_matricula', $id)->delete();
        return redirect()->route('matriculas.index');
    }

    public function search(Request $request)
    {
        $filtros = $request->except('_token');
        $matriculas = $this->repositorio->search($request->filtro);
        
        return view('secretaria.paginas.matriculas.index', [
            'matricula' => $matriculas,
            'filtros' => $filtros,
        ]);
    }

    public function edit($id)
    {

        $matricula = $this->repositorio->where('id_matricula', $id)->first();
        
        if (!$matricula)
            return redirect()->back();
                
        return view('secretaria.paginas.matriculas.edit',[
            'matricula' => $matricula,
        ]);
    }

    public function update(StoreUpdateMatricula $request, $id)
    {
        $matricula = $this->repositorio->where('id_matricula', $id)->first();

        if (!$matricula)
            return redirect()->back();
        
        $sit = $this->verificarSituacao($request->all());
        
        $request->merge($sit);

        $matricula->where('id_matricula', $id)->update($request->except('_token', '_method'));

        return redirect()->route('matriculas.index');
    }

    /**
     * Verifica se a situação foi ativada
     */
    /* public function verificarSituacao(array $dados)
    {
        if (!array_key_exists('situacao_disciplina', $dados))
            return ['situacao_disciplina' => '0'];
        else
             return ['situacao_disciplina' => '1'];            
    } */
}
