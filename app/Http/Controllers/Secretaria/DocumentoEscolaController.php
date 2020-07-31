<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\AnoLetivo;
use App\Models\Secretaria\DocumentoEscola;
use Illuminate\Http\Request;
use App\Models\Secretaria\Matricula;
use App\Models\TipoDocumentoEscola;
use App\User;

class DocumentoEscolaController extends Controller
{
    protected $documentoEscola, $matricula;
    
    public function __construct(DocumentoEscola $documentoEscola)
    {        
        $this->documentoEscola = $documentoEscola;
        $this->matricula = new Matricula;
    }

    public function create()
    {
        $anosLetivos = new AnoLetivo;
        $anosLetivos = $anosLetivos->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
                                    ->orderBy('ano', 'desc')
                                    ->get();


        return view('secretaria.paginas.matriculas.documentos_escola.declaracao', [
                    'anosLetivos' => $anosLetivos,
        ]);
    }

    //Documentos de uma matrícula
    public function index($id_aluno)
    {
        $documentosEscola = $this->documentoEscola
                                        ->join('tb_matriculas', 'id_matricula', '=', 'tb_documentos_escola.fk_id_matricula')
                                        ->join('tb_turmas', 'id_turma', '=', 'tb_matriculas.fk_id_turma')
                                        ->join('tb_tipos_turmas', 'id_tipo_turma', '=', 'tb_turmas.fk_id_tipo_turma')
                                        ->join('tb_anos_letivos', 'id_ano_letivo', '=', 'tb_tipos_turmas.fk_id_ano_letivo')                                        
                                        ->where('fk_id_aluno', $id_aluno)
                                        ->where('tb_anos_letivos.fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
                                        ->paginate();

       /*  if (!$matricula)
            return redirect()->back()->with('Nenhuma matrícula gerada para este aluno.');        
        */

        return view('secretaria.paginas.matriculas.documentos_escola.index', [
                    'documentosEscola' => $documentosEscola,          
                    
        ]);
    }

    /**
     * Imprimir documento gravado anteriormente.
     */
    public function show($id_documento){
        $documentoEscola = $this->documentoEscola->where('id_documento_escola', $id_documento)->first();

        return view('secretaria.paginas.matriculas.documentos_escola.show', [
            'documentoEscola' => $documentoEscola,
        ]);
    }

}
