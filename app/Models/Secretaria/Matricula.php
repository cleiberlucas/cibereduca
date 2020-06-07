<?php

namespace App\Models\Secretaria;

use App\Models\AnoLetivo;
use App\Models\FormaPagamento;
use App\Models\Secretaria\Turma;
use App\Models\SituacaoMatricula;
use App\Models\TipoAtendimentoEspecializado;
use App\Models\TipoDescontoCurso;
use App\Models\TipoDocumento;
use App\Models\Turno;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    protected $table = "tb_matriculas";
    protected $primaryKey = 'id_matricula';
    
    public $timestamps = false;
        
    protected $fillable = ['fk_id_aluno', 'fk_id_responsavel', 'fk_id_turma', 
                            'data_matricula', 'valor_matricula', 'data_limite_desistencia', 'data_vigencia', 'data_pagto_matricula', 'fk_id_forma_pagto_matricula',
                            'valor_desconto', 'fk_id_tipo_desconto_curso', 'qt_parcelas_curso', 'data_venc_parcela_um', 'fk_id_forma_pagto_curso',
                            'valor_material_didatico', 'data_pagto_mat_didatico', 'fk_id_forma_pagto_didatico', 'qt_parcelas_mat_didatico',
                            'fk_id_atendimento_especializado', 'obs_matricula', 'fk_id_situacao_matricula', 'obs_matricula', 'fk_id_user_cadastro', 'fk_id_user_altera'];
   
    /* public function search($filtro = null)
    {
        $resultado = $this->where('nome_turma', 'like', "%{$filtro}%") 
                            ->paginate();
        
        return $resultado;
    } */

    /**
     * Retorna a turma MatrículaXAluno
     */
    public function turma()
    {       
        return $this->belongsTo(Turma::class, 'fk_id_turma', 'id_turma')->with('turno', 'tipoTurma');
    }

    public function quantMatriculas($idTurma)
    {
        $quant = $this->
                            where(['fk_id_turma' => $idTurma])
                            ->where(['fk_id_situacao_matricula' => '1'])
                            ->count();
        return $quant;
    }

    public function quantVagasDisponiveis($idTurma)
    {
        $turma = new Turma;
        $limiteAlunos =  intval($turma->quantLimiteAlunos($idTurma));
        $qtmatriculas = intval($this->quantMatriculas($idTurma));
        
        return $limiteAlunos - $qtmatriculas;
    }

    /**
     * Retorna aluno MatrículaXAluno
     */
    public function aluno()
    {       
        return $this->belongsTo(Pessoa::class, 'fk_id_aluno', 'id_pessoa');
    }

    /**
     * Retorna forma pagamento matricula
     */
    public function formaPagamentoMatricula()
    {       
        return $this->belongsTo(FormaPagamento::class, 'fk_id_forma_pagto_matricula', 'id_forma_pagamento');
    }

    /**
     * Retorna forma pagamento curso
     */
    public function formaPagamentoCurso()
    {       
        return $this->belongsTo(FormaPagamento::class, 'fk_id_forma_pagto_curso', 'id_forma_pagamento');        
    }

    /**
     * Retorna forma pagamento material didático
     */
    public function formaPagamentoMaterialDidatico()
    {       
        return $this->belongsTo(FormaPagamento::class, 'fk_id_forma_pagto_mat_didatico', 'id_forma_pagamento');
    }
    
    /**
     * Retorna tipo atendimento especializado MatrículaXAluno
     */
    public function tipoAtendimentoEspecializado()
    {       
        return $this->belongsTo(TipoAtendimentoEspecializado::class, 'fk_id_atendimento_especializado', 'id_atendimento_especializado');
    }   

    /**
     * Retorna situação MatrículaXAluno
     */
    public function situacaoMatricula()
    {       
        return $this->belongsTo(situacaoMatricula::class, 'fk_id_situacao_matricula', 'id_situacao_matricula');
    }

    /**
     * Ler todos documentos entregues
     */
    public function tiposDocumentos()
    {
        //join M:M matricula X documentos
        return $this->belongsToMany(TipoDocumento::class, 'tb_documentos_matricula', 'fk_id_matricula', 'fk_id_tipo_documento');
    }

    /**
     * Ler permissões livres para um perfil
     */
    public function documentosNaoEntregues($filtro = null) 
    {
        $documentos = TipoDocumento::whereNotIn('id_tipo_documento', function($query){
            $query->select('tb_documentos_matricula.fk_id_tipo_documento');
            $query->from('tb_documentos_matricula');
            $query->whereRaw("tb_documentos_matricula.fk_id_matricula = {$this->id_matricula}");        
            })
            ->where(function ($queryFiltro) use ($filtro){
                if ($filtro)
                    $queryFiltro->where('tb_tipo_documentos.tipo_documento', 'LIKE', "%{$filtro}%");
            })
            ->paginate();
        //dd($permissoes);
        return $documentos;
    }    

}
