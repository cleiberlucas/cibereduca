<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\Secretaria\Matricula;
use Illuminate\Http\Request;

class MatriculaPastaController extends Controller
{

    private $repositorio;
    
    public function __construct(Matricula $matricula)
    {
        $this->repositorio = $matricula;
    }

    public function pasta($id_aluno){
        $matriculas = $this->repositorio->where('fk_id_aluno', $id_aluno)->get();

       /*  $turma = Turma::select('tb_turmas.nome_turma', 'tb_turmas.id_turma', 'tb_turmas.limite_alunos', 'tb_anos_letivos.ano', 'tb_turnos.descricao_turno', 'tb_sub_niveis_ensino.sub_nivel_ensino')                            
        ->join('tb_tipos_turmas', 'tb_turmas.fk_id_tipo_turma', '=', 'tb_tipos_turmas.id_tipo_turma' )
        ->join('tb_sub_niveis_ensino', 'tb_tipos_turmas.fk_id_sub_nivel_ensino', '=', 'tb_sub_niveis_ensino.id_sub_nivel_ensino')
        ->join('tb_anos_letivos', 'tb_tipos_turmas.fk_id_ano_letivo', '=', 'tb_anos_letivos.id_ano_letivo')
        ->join('tb_turnos', 'tb_turmas.fk_id_turno', '=', 'tb_turnos.id_turno')         
        ->where('tb_turmas.id_turma', '=', $request->segment(2))                         
        ->first();  */         

        return view('secretaria.paginas.pessoas.pasta.pasta', [
        'matriculas' => $matriculas,        
        ]);
    }

}
