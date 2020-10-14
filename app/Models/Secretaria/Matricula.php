<?php

namespace App\Models\Secretaria;


use App\Models\FormaPagamento;
use App\Models\Secretaria\Turma;
use App\Models\SituacaoMatricula;
use App\Models\TipoAtendimentoEspecializado;
use App\Models\TipoDocumento;
use App\User;

use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    protected $table = "tb_matriculas";
    protected $primaryKey = 'id_matricula';

    public $timestamps = false;

    protected $fillable = [
        'fk_id_aluno', 'fk_id_responsavel', 'fk_id_turma',
        'data_matricula', 'valor_matricula', 'data_limite_desistencia', 'data_vigencia', 'data_pagto_matricula', 'fk_id_forma_pagto_matricula',
        'valor_desconto', 'fk_id_tipo_desconto_curso', 'qt_parcelas_curso', 'data_venc_parcela_um', 'fk_id_forma_pagto_curso',
        'valor_material_didatico', 'data_pagto_mat_didatico', 'fk_id_forma_pagto_didatico', 'qt_parcelas_mat_didatico',
        'fk_id_atendimento_especializado', 'obs_matricula', 'fk_id_situacao_matricula', 'obs_matricula', 'fk_id_user_cadastro', 'fk_id_user_altera'
    ];

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

    public function totalMatriculasAno($ano)
    {
        $quant = $this->join('tb_turmas', 'id_turma', 'fk_id_tipo_turma')            
            ->join('tb_tipos_turmas', 'id_tipo_turma', 'fk_id_tipo_turma')
            ->join('tb_anos_letivos', 'id_ano_letivo', 'fk_id_ano_letivo')
            ->where('ano', $ano)
            ->where('fk_id_situacao_matricula', 1)
            ->where('fk_id_unidade_ensino', User::getUnidadeEnsinoSelecionada()) 
            ->count();

        return $quant;
    }

    public function quantMatriculasTurma($idTurma)
    {
        $quant = $this->where(['fk_id_turma' => $idTurma])
            ->where(['fk_id_situacao_matricula' => '1'])
            ->count();
        return $quant;
    }

    public function quantVagasDisponiveis($idTurma)
    {
        $turma = new Turma;
        $limiteAlunos =  intval($turma->quantLimiteAlunos($idTurma));
        $qtmatriculas = intval($this->quantMatriculasTurma($idTurma));

        return $limiteAlunos - $qtmatriculas;
    }

    /**
     * Retorna a lista de alunos de uma turma
     */
    public function getAlunosTurma($id_turma)
    {
        $alunos = $this
            ->select('nome', 'nome_turma', 'descricao_turno', 'sub_nivel_ensino')
            ->join('tb_turmas', 'fk_id_turma', 'id_turma')
            ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
            ->join('tb_turnos', 'fk_id_turno', 'id_turno')
            ->join('tb_sub_niveis_ensino', 'fk_id_sub_nivel_ensino', 'id_sub_nivel_ensino')
            ->join('tb_pessoas', 'fk_id_aluno', 'id_pessoa')
            ->where('fk_id_turma', $id_turma)
            ->orderBy('nome')
            ->get();
            
        return $alunos;
    }

    /**
     * Retorna a matrícula de um aluno
     */
    public function getMatriculaAluno($id_matricula)
    {
        $aluno = $this
            ->select('nome', 'nome_turma', 'descricao_turno', 'sub_nivel_ensino')
            ->join('tb_turmas', 'fk_id_turma', 'id_turma')
            ->join('tb_tipos_turmas', 'fk_id_tipo_turma', 'id_tipo_turma')
            ->join('tb_turnos', 'fk_id_turno', 'id_turno')
            ->join('tb_sub_niveis_ensino', 'fk_id_sub_nivel_ensino', 'id_sub_nivel_ensino')
            ->join('tb_pessoas', 'fk_id_aluno', 'id_pessoa')
            ->where('id_matricula', $id_matricula)            
            ->get();

        return $aluno;
    }

    /**
     * Retorna aluno MatrículaXAluno
     */
    public function aluno()
    {
        return $this->belongsTo(Pessoa::class, 'fk_id_aluno', 'id_pessoa');
    }

    /**
     * Retorna responsavel MatrículaXAluno
     */
    public function responsavel()
    {
        return $this->belongsTo(Pessoa::class, 'fk_id_responsavel', 'id_pessoa');
    }

     /**
     * Retorna usuário que matriculou
     */
    public function usuarioMatricula()
    {
        return $this->belongsTo(User::class, 'fk_id_user_cadastro', 'id');
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
        $documentos = TipoDocumento::whereNotIn('id_tipo_documento', function ($query) {
            $query->select('tb_documentos_matricula.fk_id_tipo_documento');
            $query->from('tb_documentos_matricula');
            $query->whereRaw("tb_documentos_matricula.fk_id_matricula = {$this->id_matricula}");
        })
            ->where(function ($queryFiltro) use ($filtro) {
                if ($filtro)
                    $queryFiltro->where('tb_tipo_documentos.tipo_documento', 'LIKE', "%{$filtro}%");
            })
            ->get();
        //dd($permissoes);
        return $documentos;
    }
}
