<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
//use Facade\FlareClient\View;
use Maatwebsite\Excel\Concerns\FromView;

class FrequenciaExportView implements FromView
{
    public function __construct($view, $unidadeEnsino, $turma, $mes, $disciplina, $alunos, $qtColunasDias){
        $this->view = $view;
        $this->unidadeEnsino = $unidadeEnsino;
        $this->turma = $turma;
        $this->mes = $mes;
        $this->disciplina = $disciplina;
        $this->alunos = $alunos;
        $this->qtColunasDias = $qtColunasDias;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        //dd($this->turma);
        return view($this->view, [
            'unidadeEnsino' => $this->unidadeEnsino,
            'turma' => $this->turma,
            'mes' => $this->mes,
            'disciplina' => $this->disciplina,
            'alunos' => $this->alunos,
            'qtColunasDias' => $this->qtColunasDias,

        ]);
    }
}
