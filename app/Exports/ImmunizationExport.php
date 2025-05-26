<?php

namespace App\Exports;

use App\Models\ChildProfile;
use Maatwebsite\Excel\Concerns\FromCollection;

class ImmunizationExport implements FromCollection
{
    public function collection()
    {
        return ChildProfile::all();
    }
}
