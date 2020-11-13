<?php

namespace App\Http\Controllers\Captacao;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateCaptacao;
use App\Http\Requests\StoreUpdateHistoricoCaptacao;
use App\Models\AnoLetivo;
use App\Models\Captacao\Captacao;
use App\Models\Captacao\HistoricoCaptacao;
use App\Models\Captacao\MotivoContato;
use App\Models\Captacao\TipoCliente;
use App\Models\Captacao\TipoDescoberta;
use App\Models\Captacao\TipoNegociacao;
use App\Models\Secretaria\Pessoa;
use App\User;
use Illuminate\Http\Request;

class HistoricoController extends Controller
{
    private $repositorio;
    
    public function __construct(HistoricoCaptacao $historico_captacao)
    {
        $this->repositorio = $historico_captacao;        
    }

    public function create($id_captacao)
    {       
        $this->authorize('Captação Cadastrar');
        
        $captacao = Captacao::
            select('nome', 'id_pessoa',
            'ano',
            'id_captacao', 'aluno', 'serie_pretendida', 'telefone_1', 'telefone_2', 'email_1', 'email_2', 'data_contato', 
            'data_agenda', 'hora_agenda',
            'necessita_apoio', 'valor_matricula', 'valor_curso', 'valor_material_didatico',
            'valor_bilingue', 'valor_robotica',
            'observacao', 'tb_captacoes.data_cadastro',
            'tipo_cliente',
            'motivo_contato',
            'tipo_descoberta',
            'tipo_negociacao',
            'name')
        ->join('tb_anos_letivos', 'fk_id_ano_letivo', 'id_ano_letivo')
        ->join('tb_pessoas', 'fk_id_pessoa', 'id_pessoa')          
        ->join('tb_tipos_cliente', 'fk_id_tipo_cliente', 'id_tipo_cliente')
        ->join('tb_motivos_contato', 'fk_id_motivo_contato', 'id_motivo_contato')
        ->leftJoin('tb_tipos_descoberta', 'fk_id_tipo_descoberta', 'id_tipo_descoberta')
        ->join('tb_tipos_negociacao', 'fk_id_tipo_negociacao', 'id_tipo_negociacao')            
        ->join('users', 'fk_id_usuario_captacao', 'id')
        ->where('id_captacao', $id_captacao)            
        ->first();
    //dd($turma);

    if (!$captacao)
        return redirect()->back()->with('alerta', 'Captação não encontrada.');

    $motivosContato = new MotivoContato;
    $motivosContato = $motivosContato        
        ->orderBy('motivo_contato')
        ->get();

    $historicos = $this->repositorio
        ->join('tb_motivos_contato', 'fk_id_motivo_contato', 'id_motivo_contato')    
        ->where('fk_id_captacao', $id_captacao)
        ->orderby('data_contato', 'desc')
        ->get();

    return view('captacao.paginas.historico.create', 
            compact('captacao', 'motivosContato', 'historicos')   
        );
    }

    public function store(StoreUpdateHistoricoCaptacao $request)
    {
        $this->authorize('Captação Cadastrar');   
                
        $dados = $request->all();        
        $dados = array_merge($dados);
       //dd($this->usuario);
        $this->repositorio->create($dados);

        return redirect()->route('historico.create', $request->fk_id_captacao)->with('sucesso', 'Histórico cadastrado com sucesso.');
    }

    public function destroy($id_historico)
    {
        $this->authorize('Captação Remover');   

        $historico = $this->repositorio->where('id_historico_captacao', $id_historico)->first();    

        if (!$historico)
            return redirect()->back()->with('atencao', 'Captação não encontrada.');
        
        $id_captacao = $historico->fk_id_captacao;

        $historico->where('id_historico_captacao', $id_historico)->delete();

        return $this->create($id_captacao)->with('sucesso', 'Histórico removido com sucesso');        
    }

    public function update(StoreUpdateHistoricoCaptacao $request, $id)
    {      
        $this->authorize('Captação Alterar');   

        $historico = $this->repositorio->where('id_historico_captacao', $id)->first();     
        if (!$historico)
            return redirect()->back()->with('erro', 'Não foi possível alterar o histórico da captação.');
                    
        $historico->where('id_historico_captacao', $id)->update($request->except('_token', '_method'));

        return $this->create($id)->with('sucesso', 'Histórico alterado com sucesso.');        
    }

}
