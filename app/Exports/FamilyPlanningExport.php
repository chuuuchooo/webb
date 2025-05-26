<?php

namespace App\Exports;

use App\Models\FamilyPlanning;
use Maatwebsite\Excel\Concerns\FromCollection;

class FamilyPlanningExport implements FromCollection
{
    public function collection()
    {
        return FamilyPlanning::all();
    }
}
