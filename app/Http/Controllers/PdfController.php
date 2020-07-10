<?php

namespace App\Http\Controllers;

use App\Models\Secretaria\Pessoa;
use Illuminate\Http\Request;
use PDF;

class PdfController extends Controller
{
    //
    public function gerarPdf(){
        $pessoas = Pessoa::all();

        $pdf = PDF::loadView('secretaria.paginas.pessoas.pdf', compact('pessoas'));

        return $pdf->setPaper('a4')->stream('todas_pessoas.pdf');
        
    }
}
