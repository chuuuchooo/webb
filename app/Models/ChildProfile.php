<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ChildProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'house_lot_no',
        'purok',
        'barangay',
        'city',
        'last_name',
        'first_name',
        'middle_name',
        'birthdate',
        'birthplace',
        'sex',
        'mothers_name',
        'fathers_name',
        'birth_weight',
        'birth_height',
        'created_by_user_id'
    ];

    protected $casts = [
        'birthdate' => 'date',
        'birth_weight' => 'decimal:2',
        'birth_height' => 'decimal:2'
    ];

    public function vaccinations()
    {
        return $this->hasMany(Vaccination::class, 'child_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function getFullNameAttribute()
    {
        return $this->last_name . ', ' . $this->first_name . ($this->middle_name ? ' ' . $this->middle_name : '');
    }

    public function getAddressAttribute()
    {
        return $this->house_lot_no . ', ' . $this->purok . ', ' . $this->barangay . ', ' . $this->city;
    }

    public function getAgeAttribute()
    {
        if (!$this->birthdate) {
            return null;
        }

        $birthdate = $this->birthdate;
        $now = Carbon::now();
        
        $years = $birthdate->diffInYears($now);
        $months = $birthdate->copy()->addYears($years)->diffInMonths($now);
        $days = $birthdate->copy()->addYears($years)->addMonths($months)->diffInDays($now);
        
        if ($years > 0) {
            return $years . ' yr' . ($years != 1 ? 's' : '') . ', ' . $months . ' mo' . ($months != 1 ? 's' : '');
        } else {
            return $months . ' mo' . ($months != 1 ? 's' : '') . ', ' . $days . ' day' . ($days != 1 ? 's' : '');
        }
    }

    public function getAgeInMonthsAttribute()
    {
        if (!$this->birthdate) {
            return 0;
        }

        return $this->birthdate->diffInMonths(Carbon::now());
    }

    public function getVaccinationStatusAttribute()
    {
        $vaccines = [
            'BCG' => 1,
            'Hepatitis B' => 1,
            'Pentavalent Vaccine' => 3,
            'Oral Polio Vaccine' => 3,
            'Inactivated Polio Vaccine' => 2,
            'Pneumococcal Conjugate Vaccine' => 3,
            'Measles,Mumps,&Rubella' => 2
        ];
        
        $completedVaccines = 0;
        $totalVaccines = count($vaccines);
        $vaccineStatus = [];
        
        foreach ($vaccines as $vaccineType => $requiredDoses) {
            $doses = $this->vaccinations()
                ->where('vaccine_type', $vaccineType)
                ->where('status', 'Completed')
                ->count();
                
            $vaccineStatus[$vaccineType] = [
                'completed' => $doses,
                'required' => $requiredDoses,
                'status' => $doses >= $requiredDoses ? 'Complete' : 'Incomplete'
            ];
            
            if ($doses >= $requiredDoses) {
                $completedVaccines++;
            }
        }
        
        $percentComplete = $totalVaccines > 0 ? round(($completedVaccines / $totalVaccines) * 100) : 0;
        
        return [
            'vaccines' => $vaccineStatus,
            'completed_count' => $completedVaccines,
            'total_count' => $totalVaccines,
            'percent_complete' => $percentComplete,
            'status' => $completedVaccines == $totalVaccines ? 'Fully Vaccinated' : ($completedVaccines > 0 ? 'Partially Vaccinated' : 'Not Vaccinated')
        ];
    }

    public function scopeFilterByPurok($query, $purok)
    {
        return $purok ? $query->where('purok', $purok) : $query;
    }

    public function scopeFilterByBarangay($query, $barangay)
    {
        return $barangay ? $query->where('barangay', $barangay) : $query;
    }

    public function scopeFilterByCity($query, $city)
    {
        return $city ? $query->where('city', $city) : $query;
    }

    public function scopeFilterByNameSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('middle_name', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    public function scopeFilterByVaccinationStatus($query, $status)
    {
        // This is more complex and would need to be implemented in the controller
        // as it requires checking the vaccination relationships
        return $query;
    }
} 