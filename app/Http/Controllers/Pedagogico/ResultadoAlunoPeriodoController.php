<?php

namespace App\Http\Controllers\Pedagogico;

use App\Http\Controllers\Controller;
use App\Models\Pedagogico\Frequencia;
use App\Models\Pedagogico\ResultadoAlunoPeriodo;
use Illuminate\Http\Request;

class ResultadoAlunoPeriodoController extends Controller
{
    private $repositorio, $frequencia;
    
    public function __construct(ResultadoAlunoPeriodo $resultadoAlunoPeriodo)
    {
        $this->repositorio = $resultadoAlunoPeriodo;        
        $this->frequencia = new Frequencia;    
    }
    
}