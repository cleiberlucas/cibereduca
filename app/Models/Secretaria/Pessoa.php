<?php

namespace App\Models\Secretaria;

use App\Models\Endereco;
use App\Models\TipoDocIdentidade;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    protected $table = "tb_pessoas";
    protected $primaryKey = 'id_pessoa';
    
    public $timestamps = false;
    
    //protected $attributes = ['situacao_disciplina' => '0'];

    protected $fillable = ['nome', 
                            'cpf', 
                            'foto',
                            'fk_id_tipo_doc_identidade', 
                            'doc_identidade', 
                            'data_nascimento', 
                            'telefone_1', 
                            'telefone_2', 
                            'email_1', 
                            'email_2', 
                            'fk_id_tipo_pessoa', 
                            'obs_pessoa', 
                            'fk_id_user_cadastro',
                            'situacao_pessoa',
                            'data_cadastro',
                            'fk_id_user_alteracao',
                            'data_alteracao',
                        ];
   
    public function search($filtro = null, $tipo_pessoa)
    {        
        $resultado = $this->where('nome', 'LIKE', "%{$filtro}%")
                            ->where('fk_id_tipo_pessoa', '=', "{$tipo_pessoa}")
                            ->orderBy('nome') 
                            ->paginate();
        
        return $resultado;
    }

    /**
     * Ler alunos não matriculados em um determinado ano letivo
     */
    public function alunosNaoMatriculados($id_ano = null) 
    {        
        $alunos = Pessoa::whereNotIn('id_pessoa', function($query) use ($id_ano){
            $query->select('tb_matriculas.fk_id_aluno');
            $query->from('tb_matriculas');
            $query->leftJoin('tb_turmas', 'tb_turmas.id_turma', 'tb_matriculas.fk_id_turma');
            $query->leftJoin('tb_tipos_turmas', 'tb_tipos_turmas.id_tipo_turma', 'tb_turmas.fk_id_tipo_turma');
            $query->where("tb_tipos_turmas.fk_id_ano_letivo", '=', $id_ano);            
            })            
            ->where('tb_pessoas.fk_id_tipo_pessoa', '=', 1)
            ->where('situacao_pessoa', 1)
            ->orderBy('nome')
            ->get();

        return $alunos;
    }

    public function getResponsaveis()
    {
        return Pessoa::select('*')
                ->where('fk_id_tipo_pessoa', '=', '2')
                ->where('situacao_pessoa', 1)
                ->orderBy('nome')
                ->get();
    }

    public function tipoPessoa()
    {       
        return $this->belongsTo(TipoPessoa::class, 'fk_id_tipo_pessoa', 'id_tipo_pessoa');
    }

    public function usuarioCadastro()
    {
        return $this->belongsTo(User::class, 'fk_id_user_cadastro', 'id');
    }
    
    public function usuarioAlteracao()
    {
        return $this->belongsTo(User::class, 'fk_id_user_alteracao', 'id');
    }

    public function endereco()
    {       
        return $this->hasOne(Endereco::class, 'fk_id_pessoa', 'id_pessoa');       
    }

    public function tipoDocIdentidade()
    {       
        return $this->belongsTo(TipoDocIdentidade::class, 'fk_id_tipo_doc_identidade', 'id_tipo_doc_identidade');
    }
}
