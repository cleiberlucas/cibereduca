<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\AnoLetivo;
use App\Models\Secretaria\DocumentoEscola;
use Illuminate\Http\Request;
use App\Models\Secretaria\Matricula;
use App\Models\UnidadeEnsino;
use App\User;
use LaravelQRCode\Facades\QRCode;
use PDF;
use PhpParser\Node\Stmt\TryCatch;

class DocumentoEscolaController extends Controller
{
    protected $repositorio, $matricula;

    public function __construct(DocumentoEscola $documentoEscola)
    {
        $this->repositorio = $documentoEscola;
        $this->matricula = new Matricula;
    }

    public function create()
    {
        $this->authorize('Documento Escola Ver'); 

        $anosLetivos = new AnoLetivo;
        $anosLetivos = $anosLetivos->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
            ->orderBy('ano', 'desc')
            ->get();


        return view('secretaria.paginas.matriculas.documentos_escola.declaracao', [
            'anosLetivos' => $anosLetivos,
        ]);
    }

    public function gerar(Request $request)
    {
        $this->authorize('Documento Escola Cadastrar'); 

        $codigoValidacao = sprintf('%07X', mt_rand(0, 0xFFFFFFF));
        
        $request->merge(['codigo_validacao' => $codigoValidacao]);

        $hoje = date('Ymd');

        $url_texto = route('matriculas.documentos_escola.autenticidade');

        $url_qrcode = route('matriculas.documentos_escola.verifica_autenticidade').'?data_geracao='.$hoje.'&codigo_validacao='.$codigoValidacao;
              
        $matricula = new Matricula;
        $matricula = $matricula->where('id_matricula', $request['fk_id_matricula'])->first();

        $unidadeEnsino = new UnidadeEnsino;
        $unidadeEnsino = $unidadeEnsino->where('id_unidade_ensino', user::getUnidadeEnsinoSelecionada())->first();
       
        if ($request['declaracao'] == 'declaracao_cursando') {
            try {
                //lendo conteúdo da view para armazenar do BD
                $conteudo = view(
                    'secretaria.paginas.matriculas.documentos_escola.declaracao_cursando',
                    compact('matricula', 'unidadeEnsino', 'codigoValidacao', 'url_texto', 'url_qrcode') 
                );
                
                $request->merge(['fk_id_tipo_documento' => 1]);
                $request->merge(['corpo_documento' => $conteudo]);
            } catch (\Throwable $th) {                
                return redirect()->back()->with('erro', 'Houve erro ao gerar a Declaração.');
            }

            try {
                //Solicitando gravação da declaração no BD
                $this->store($request);
            } catch (\Throwable $th) {
                return redirect()->back()->with('erro', 'Houve erro ao gravar a Declaração.');
            }           
            
            //Visualizando a declaração
            return view(
                'secretaria.paginas.matriculas.documentos_escola.declaracao_cursando',
                compact('matricula', 'unidadeEnsino', 'codigoValidacao', 'url_texto', 'url_qrcode')
            );
        }
        else if ($request['declaracao'] == 'declaracao_transferencia_concluido') {
                       
            $aptoCurso = $request->aptoCurso;
            if (strlen($aptoCurso) < 5)
                return redirect()->back()->with('atencao',  'Informe o CURSO que o aluno está apto a cursar.');

            $nivelEnsino = $request->nivelEnsino;
            if (strlen($nivelEnsino) < 5)
                return redirect()->back()->with('atencao',  'Informe o NÍVEL DE ENSINO que o aluno está apto a cursar.');

            $anoLetivo = $request->anoLetivo;
            $anoLetivo = new AnoLetivo;
            $anoLetivo = $anoLetivo
                ->select('ano')
                ->where('id_ano_letivo', $request->anoLetivo)->first();

            try {
                //lendo conteúdo da view para armazenar do BD
                $conteudo = view(
                    'secretaria.paginas.matriculas.documentos_escola.declaracao_transferencia_concluido',
                    compact('anoLetivo', 'matricula', 'unidadeEnsino', 'codigoValidacao', 'url_texto', 'url_qrcode', 'aptoCurso', 'nivelEnsino') 
                );
                
                $request->merge(['fk_id_tipo_documento' => 2]);
                $request->merge(['corpo_documento' => $conteudo]);
            } catch (\Throwable $th) {                
                return redirect()->back()->with('erro', 'Houve erro ao gerar a Declaração.');
            }

            try {
                //Solicitando gravação da declaração no BD
                $this->store($request);
            } catch (\Throwable $th) {
                return redirect()->back()->with('erro', 'Houve erro ao gravar a Declaração.');
            }           
            
            //Visualizando a declaração
            return view(
                'secretaria.paginas.matriculas.documentos_escola.declaracao_transferencia_concluido',
                compact('anoLetivo', 'matricula', 'unidadeEnsino', 'codigoValidacao', 'url_texto', 'url_qrcode', 'aptoCurso', 'nivelEnsino')
            );
        } else
            return redirect()->back()->withInput()->with('atencao', 'Escolha um tipo de declaração.');
    }

    /**
     * Gravar a declaração no BD
     */
    public function store(Request $request)
    {
        $dados = $request->all();

        $this->repositorio->create($dados);

        return redirect()->back()->with('sucesso', 'Declaração gerada com sucesso.');
    }

    //Documentos/declarações gerados para uma matrícula
    public function index($id_aluno)
    {
        $this->authorize('Documento Escola Ver'); 

        $documentosEscola = $this->repositorio
            ->join('tb_matriculas', 'id_matricula', '=', 'tb_documentos_escola.fk_id_matricula')
            ->join('tb_turmas', 'id_turma', '=', 'tb_matriculas.fk_id_turma')
            ->join('tb_tipos_turmas', 'id_tipo_turma', '=', 'tb_turmas.fk_id_tipo_turma')
            ->join('tb_anos_letivos', 'id_ano_letivo', '=', 'tb_tipos_turmas.fk_id_ano_letivo')
            ->where('fk_id_aluno', $id_aluno)
            ->where('tb_anos_letivos.fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada())
            ->orderBy('ano', 'desc')
            ->orderBy('data_geracao', 'desc')
            ->paginate();

        if (count($documentosEscola) == 0)
            return redirect()->back()->with('info', 'Nenhum documento gerado para este aluno.');


        return view('secretaria.paginas.matriculas.documentos_escola.index', [
            'documentosEscola' => $documentosEscola,

        ]);
    }

    /**
     * Imprimir documento gravado anteriormente.
     */
    public function show($id_documento)
    {
        $this->authorize('Documento Escola Ver'); 

        $documentoEscola = $this->repositorio->where('id_documento_escola', $id_documento)->first();

        return view('secretaria.paginas.matriculas.documentos_escola.show', [
            'documentoEscola' => $documentoEscola,
        ]);
    }

    /**
     * Abre interface para verificação de autenticidade de documento
     */
    public function autenticidade()
    {
        return view('secretaria.paginas.matriculas.documentos_escola.autenticidade');
        //dd($request);
    }

    /**
     * 
     */
    public function verificarAutenticidade(Request $request)
    {
        $documentoEscola = $this->repositorio->whereDate('data_geracao', '=', $request['data_geracao'])
            ->where('codigo_validacao', $request['codigo_validacao'])
            ->first();

        if (!$documentoEscola)
            return redirect()->back()->withInput()->with('atencao', 'Documento não encontrado, verifique os dados informados.');

        else if ($documentoEscola->situacao_documento == 0)
            return redirect()->back()->withInput()->with('erro', 'DOCUMENTO INVÁLIDO. Favor contatar a Instituição de Ensino.');

        else            
            return view('secretaria.paginas.matriculas.documentos_escola.autenticidade', 
                ['sucesso' => 'Documento válido - ' . $documentoEscola->tipoDocumentoEscola->tipo_documento . ' emitido para ' . $documentoEscola->matricula->aluno->nome . '.',
                'dados' => 'Emitido em '.date('d/m/Y H:m:i', strtotime($documentoEscola->data_geracao)).' - Código de Validação: '.$documentoEscola->codigo_validacao
                ])
                ->withInput('');            
            
           // return redirect()->back()->withInput()->with('sucesso', 'Documento válido - ' . $documentoEscola->tipoDocumentoEscola->tipo_documento . ' emitido para ' . $documentoEscola->matricula->aluno->nome . '.');
    }
}
