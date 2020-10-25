<?php

namespace App\Exports;

use App\Models\Pedagogico\Frequencia as PedagogicoFrequencia;
use Maatwebsite\Excel\Concerns\FromCollection;

class FrequenciaExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return PedagogicoFrequencia::all();
    }
}
