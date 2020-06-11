<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Secretaria\Matricula;
use App\Models\TipoDocumento;
use App\Models\User;

class MatriculaDocumentoController extends Controller
{
    protected $matricula, $documento;
    
    public function __construct(Matricula $matricula, TipoDocumento $documento)
    {
        $this->matricula = $matricula;
        $this->documento = $documento;
    }

    //Documentos de uma matrícula
    public function documentos($id_matricula)
    {
        $matricula = $this->matricula->join('tb_turmas', 'tb_turmas.id_turma', '=', 'tb_matriculas.fk_id_turma')
                                        ->join('tb_tipos_turmas', 'tb_tipos_turmas.id_tipo_turma', '=', 'tb_turmas.fk_id_tipo_turma')
                                        ->join('tb_anos_letivos', 'tb_anos_letivos.id_ano_letivo', '=', 'tb_tipos_turmas.fk_id_ano_letivo')
                                        ->where('id_matricula', $id_matricula)
                                        ->where('tb_anos_letivos.fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
                                        ->first();

        if (!$matricula)
            return redirect()->back();
        
        $documentosEntregues = $matricula->tiposDocumentos()->get();
        $documentosNaoEntregues = $matricula->documentosNaoEntregues();

        return view('secretaria.paginas.matriculas.documentos.documentos', [
                    'matricula'             => $matricula,
                    'documentosEntregues'   => $documentosEntregues,
                    'documentosNaoEntregues'=> $documentosNaoEntregues, 
        ]);
    }

    public function documentosAdd(Request $request, $id_matricula)
    {
        $matricula = $this->matricula->where('id_matricula', $id_matricula)->first();

        if (!$matricula)
            return redirect()->back();

        $filtros = $request->except('_token');
        $documentos = $matricula->documentosLivres($request->filtro);         
        
        return view('secretaria.paginas.matriculas.documentos.add', [
            'matricula' => $matricula,
            'documentos' => $documentos,
            'filtros' => $filtros,
        ]);
    }

    public function vincularDocumentosMatricula(Request $request, $id_matricula)
    {
        $matricula = $this->matricula->where('id_matricula', $id_matricula)->first();

        if (!$matricula)
            return redirect()->back();

        if (!$request->documentos || count($request->documentos) == 0){
            return redirect()
                    ->back()
                    ->with('info', 'Escolha pelo menos um documento.');
        }

        $matricula->tiposDocumentos()->attach($request->documentos);

        return redirect()->route('matriculas.documentos', $matricula->id_matricula);
    }

    public function removerDocumentosMatricula($id_matricula, $id_documento)
    {
        $matricula = $this->matricula->join('tb_turmas', 'tb_turmas.id_turma', '=', 'tb_matriculas.fk_id_turma')
                                        ->join('tb_tipos_turmas', 'tb_tipos_turmas.id_tipo_turma', '=', 'tb_turmas.fk_id_tipo_turma')
                                        ->join('tb_anos_letivos', 'tb_anos_letivos.id_ano_letivo', '=', 'tb_tipos_turmas.fk_id_ano_letivo')                                        
                                        ->where('tb_anos_letivos.fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
                                        ->where('id_matricula', $id_matricula)
                                        ->first();
        
                                        $documento = $this->documento->where('id_tipo_documento', $id_documento)->first();

        if (!$matricula || !$documento)
            return redirect()->back();

        $matricula->tiposDocumentos()->detach($documento);

        return redirect()->route('matriculas.documentos', $matricula->id_matricula);
    }
}
