<?php

namespace App\Http\Controllers\Pedagogico;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Secretaria\TurmaController;
use App\Models\Secretaria\Turma;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $idUnidade = User::getUnidadeEnsinoSelecionada();
        $perfilUsuario = new User;        
        $perfilUsuario = $perfilUsuario->getPerfilUsuarioUnidadeEnsino($idUnidade, Auth::id());
        //dd($perfilUsuario->fk_id_perfil);
        if(!$perfilUsuario)
            return redirect()->back();

        /* Se for professor, listar somente a turma dele */
        if ($perfilUsuario->fk_id_perfil == 2){
            $turmas = Turma::select ('id_turma', 'nome_turma', 'sub_nivel_ensino', 'descricao_turno', 'ano')
                ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma' )
                ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
                ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
                ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno')        
                ->join('tb_turmas_disciplinas_professor', 'tb_turmas_disciplinas_professor.fk_id_turma', 'id_turma' )  
                ->join('tb_usuarios_unidade_ensino', 'tb_usuarios_unidade_ensino.fk_id_user', 'fk_id_professor')                                                  
                ->where('tb_anos_letivos.fk_id_unidade_ensino', '=', User::getUnidadeEnsinoSelecionada())         
                ->where('fk_id_professor', Auth::id())   
                ->where('situacao_disciplina_professor', 1)    
                ->where('situacao_vinculo', 1)         
                ->groupBy('id_turma')
                ->groupBy('nome_turma')
                ->groupBy('sub_nivel_ensino')
                ->groupBy('descricao_turno')
                ->groupBy('ano')                
                ->paginate(25);
        }
        else{
            $turmas = Turma::select ('*')
                ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma' )
                ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
                ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
                ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno')                                                            
                ->where('tb_anos_letivos.fk_id_unidade_ensino', '=', User::getUnidadeEnsinoSelecionada())                                                         
                ->orderBy('tb_anos_letivos.ano', 'desc')
                ->orderBy('tb_sub_niveis_ensino.sub_nivel_ensino', 'asc')
                ->orderBy('nome_turma', 'asc')
                ->orderBy('tb_turnos.descricao_turno', 'asc')
                ->paginate(25); 
        }
        
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
