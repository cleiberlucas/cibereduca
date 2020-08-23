<?php

namespace App\Http\Controllers\Secretaria\Relatorio;

use App\Http\Controllers\Controller;
use App\Models\AnoLetivo;
use App\Models\Secretaria\Matricula;
use App\Models\Secretaria\Turma;
use App\Models\UnidadeEnsino;
use App\User;
use Illuminate\Http\Request;

class SecretariaController extends Controller
{

    /**
     * Página inicial relatórios secretaria
     */
    public function index()
    {
        $this->authorize('Pessoa Ver');

        $anosLetivos = AnoLetivo::where('fk_id_unidade_ensino', '=', session()->get('id_unidade_ensino'))
            ->orderBy('ano', 'desc')
            ->get();

        return view('secretaria.paginas.relatorios.index', [
            'anosLetivos' => $anosLetivos,
        ]);
    }

    /**
     * Verifica escolhas do usuário e gera relatório
     */
    public function filtros(Request $request)
    {
        $this->authorize('Pessoa Ver');

        $turma = new Turma;
        $matriculas = new Matricula;
        $unidadeEnsino = new UnidadeEnsino;
        $unidadeEnsino = $unidadeEnsino->where('id_unidade_ensino', User::getUnidadeEnsinoSelecionada())->first();
        //dd($request);
        //Verificando se escolheu uma turma
        if ($request['turma']) {
            $turma = $turma->where('id_turma', $request->turma)->first();
        }

        if ($request->tipo_relatorio == 'alunos_turma') {
            if ($request->turma == null)
                return redirect()->back()->with('atencao', 'Escolha uma turma.');

            $matriculas = $matriculas->where('fk_id_turma', $request->turma)
                ->join('tb_pessoas', 'fk_id_aluno', 'id_pessoa')
                ->orderBy($request->ordem)
                ->get();

            return view(
                'secretaria.paginas.relatorios.alunos_turma',
                compact('turma', 'matriculas', 'unidadeEnsino'),
            );
        }
    }
}
