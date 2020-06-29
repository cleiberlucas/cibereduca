<?php

namespace App\Http\Controllers\Pedagogico;

use App\Http\Controllers\Controller;
use App\Models\Secretaria\Turma;
use App\User;
use Illuminate\Http\Request;

class PedagogicoTurmaController extends Controller
{
    private $repositorio;
    
    public function __construct(Turma $turma)
    {
        $this->repositorio = $turma;
    }

    /**
     * Lista Turmas para lançamento de conteúdo lecionado e frequência
     */
    public function index()
    {
        $turmas = Turma::select ('*')
                            ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma' )
                            ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
                            ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
                            ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno')                                                            
                            ->where('tb_anos_letivos.fk_id_unidade_ensino', '=', User::getUnidadeEnsinoSelecionada())                             
                            ->orderBy('tb_anos_letivos.ano', 'desc')
                            ->orderBy('tb_turnos.descricao_turno', 'asc')
                            ->orderBy('tb_sub_niveis_ensino.sub_nivel_ensino', 'asc')
                            ->orderBy('nome_turma', 'asc')
                            ->paginate();
       //dd($turmas);
        return view('pedagogico.paginas.turmas.index', [
                    'turmas' => $turmas,       
        ]);
    }

    public function search(Request $request)
    {
        $filtros = $request->except('_token');
        $turmas = $this->repositorio->search($request->filtro);
        
        return view('pedagogico.paginas.turmas.index', [
            'turmas' => $turmas,
            'filtros' => $filtros,
        ]);
    }

}
