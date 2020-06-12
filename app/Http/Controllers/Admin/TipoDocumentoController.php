<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateTipoDocumento;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TipoDocumentoController extends Controller
{
    private $repositorio;
    
    public function __construct(TipoDocumento $tipoDocumento)
    {
        $this->repositorio = $tipoDocumento;                      
    }

    public function index()
    {
        $tiposDocumentos = $this->repositorio
                                ->orderBy('tipo_documento', 'asc')
                                ->paginate();      
                
        return view('admin.paginas.tiposdocumentos.index', [
                    'tiposDocumentos' => $tiposDocumentos,        
        ]);
    }

    public function create()
    {       
        $this->authorize('Tipo Documento Matrícula Cadastrar');
        return view('admin.paginas.tiposdocumentos.create');
    }

    public function store(StoreUpdateTipoDocumento $request )
    {
        $dados = $request->all();        
        $dados = array_merge($dados);
        
        //verificando escolha do campo obrigatório
        $obrig = $this->verificarObrigatorio($dados);
        $dados = array_merge($dados, $obrig);

        //verificado escolha do campo situação
        $sit = $this->verificarSituacao($dados);
        $dados = array_merge($dados, $sit);

        $this->repositorio->create($dados);

        return redirect()->route('tiposdocumentos.index');
    }

    public function show($id)
    {
        $tipoDocumento = $this->repositorio->where('id_tipo_documento', $id)->first();

        if (!$tipoDocumento)
            return redirect()->back();

        return view('admin.paginas.tiposdocumentos.show', [
            'tipoDocumento' => $tipoDocumento
        ]);
    }

    public function destroy($id)
    {
        $tipoDocumento = $this->repositorio->where('id_tipo_documento', $id)->first();

        if (!$tipoDocumento)
            return redirect()->back();

        $tipoDocumento->where('id_tipo_documento', $id)->delete();
        return redirect()->route('tiposdocumentos.index');
    }

    public function search(Request $request)
    {
        $filtros = $request->except('_token');
        $tiposDocumentos = $this->repositorio->search($request->filtro);
        
        return view('admin.paginas.tiposdocumentos.index', [
            'tiposDocumentos' => $tiposDocumentos,
            'filtros' => $filtros,
        ]);
    }

    public function edit($id)
    {
        $this->authorize('Tipo Documento Matrícula Alterar');
        $tipoDocumento = $this->repositorio->where('id_tipo_documento', $id)->first();
             
        if (!$tipoDocumento)
            return redirect()->back();
        
        return view('admin.paginas.tiposdocumentos.edit',[
                    'tipoDocumento' => $tipoDocumento,                   
        ]);
    }

    public function update(StoreUpdateTipoDocumento $request, $id)
    {        
        $tipoDocumento = $this->repositorio->where('id_tipo_documento', $id)->first();     
        if (!$tipoDocumento)
            return redirect()->back();

        //verificando escolha do campo obrigatório
        $obrig = $this->verificarObrigatorio($request->all());    
        $request->merge($obrig);

        //verificado escolha do campo situação
        $sit = $this->verificarSituacao($request->all());    
        $request->merge($sit);

        $tipoDocumento->where('id_tipo_documento', $id)->update($request->except('_token', '_method'));

        return redirect()->route('tiposdocumentos.index');
    }

    /**
     * Verifica se a situação foi ativada
     */
    public function verificarSituacao(array $dados)
    {
        if (!array_key_exists('situacao', $dados))
            return ['situacao' => '0'];
        else
             return ['situacao' => '1'];            
    }

    /**
     * Verifica se o campo obrigatorio foi ativado
     */
    public function verificarObrigatorio(array $dados)
    {
        if (!array_key_exists('obrigatorio', $dados))
            return ['obrigatorio' => '0'];
        else
             return ['obrigatorio' => '1'];            
    }
}
