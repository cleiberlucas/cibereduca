<?php

namespace App\Models\Financeiro;

use Illuminate\Database\Eloquent\Model;

class Recebivel extends Model
{
    protected $table = "tb_recebiveis";
    protected $primaryKey = 'id_recebivel';
    
    public $timestamps = false;
        
    protected $fillable = [
        'fk_id_unidade_ensino', 
        'fk_id_matricula', 
        'fk_id_conta_contabil_principal',
        'valor_principal',
        'valor_desconto_principal',
        'valor_total',
        'desconto_autorizado',
        'motivo_desconto',
        'fk_id_usuario_desconto',
        'data_autoriza_desconto',
        'data_vencimento',
        'parcela',
        'obs_recebivel',
        'fk_id_situacao_recebivel',
        'substituido_por',
        'fk_id_usuario_cadastro',
        'data_cadastro',
    ];
       
   public function getRecebiveisBoleto($id_boleto)
   {
       $recebiveis = new BoletoRecebivel;
       $recebiveis = $recebiveis
            ->select('descricao_conta', 
                'tipo_turma', 'ano', 
                'id_recebivel', 'parcela', 'valor_principal', 'valor_desconto_principal', 'valor_total', 'data_vencimento', 'obs_recebivel',
                'id_pessoa', 'nome')
            ->join('tb_recebiveis', 'tb_boletos_recebiveis.fk_id_recebivel', 'id_recebivel')
            ->Join('tb_matriculas', 'fk_id_matricula', 'id_matricula')
            ->join('tb_pessoas', 'id_pessoa', 'fk_id_aluno')
            ->join('tb_turmas', 'fk_id_turma', 'id_turma')
            ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
            ->join('tb_anos_letivos', 'fk_id_ano_letivo', 'id_ano_letivo')
            ->join('tb_contas_contabeis', 'fk_id_conta_contabil_principal', 'id_conta_contabil')
            ->join('tb_tipos_situacao_recebivel', 'fk_id_situacao_recebivel', 'id_situacao_recebivel')            
            ->where('fk_id_boleto', $id_boleto)                              
            ->orderBy('descricao_conta')
            ->get();

        return $recebiveis;
   }

}
