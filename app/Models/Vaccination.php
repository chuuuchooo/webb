<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccination extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'vaccine_type',
        'dose_number',
        'date_vaccinated',
        'status',
        'next_schedule',
        'remarks',
        'administered_by_user_id'
    ];

    protected $casts = [
        'date_vaccinated' => 'date',
        'next_schedule' => 'date'
    ];

    public function child()
    {
        return $this->belongsTo(ChildProfile::class, 'child_id');
    }

    public function administeredBy()
    {
        return $this->belongsTo(User::class, 'administered_by_user_id');
    }

    // Get expected vaccination age in months based on vaccine type and dose
    public function getExpectedAgeAttribute()
    {
        $schedules = [
            'BCG' => [1 => 0], // At birth
            'Hepatitis B' => [1 => 0], // At birth
            'Pentavalent Vaccine' => [
                1 => 1.5, // 1.5 months
                2 => 2.5, // 2.5 months
                3 => 3.5  // 3.5 months
            ],
            'Oral Polio Vaccine' => [
                1 => 1.5, // 1.5 months
                2 => 2.5, // 2.5 months
                3 => 3.5  // 3.5 months
            ],
            'Inactivated Polio Vaccine' => [
                1 => 3.5, // 3.5 months
                2 => 3.5  // 3.5 months (same time)
            ],
            'Pneumococcal Conjugate Vaccine' => [
                1 => 1.5, // 1.5 months
                2 => 2.5, // 2.5 months
                3 => 3.5  // 3.5 months
            ],
            'Measles,Mumps,&Rubella' => [
                1 => 9,   // 9 months
                2 => 12   // 12 months
            ]
        ];

        return $schedules[$this->vaccine_type][$this->dose_number] ?? null;
    }

    // Determine if the vaccination is on schedule, delayed, or early
    public function getScheduleStatusAttribute()
    {
        if (!$this->date_vaccinated || !$this->child || !$this->child->birthdate) {
            return 'Unknown';
        }

        $expectedAge = $this->expected_age;
        if ($expectedAge === null) {
            return 'Unknown';
        }

        // Convert expected age to days
        $expectedAgeDays = $expectedAge * 30;
        
        // Get actual age at vaccination in days
        $actualAgeDays = $this->child->birthdate->diffInDays($this->date_vaccinated);
        
        // Allow for 14 days grace period
        if (abs($actualAgeDays - $expectedAgeDays) <= 14) {
            return 'On Schedule';
        } elseif ($actualAgeDays < $expectedAgeDays) {
            return 'Early';
        } else {
            return 'Delayed';
        }
    }
} 