<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ImmunizationRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vaccination_date',
        'vaccine_name',
        'dose_number',
        'batch_number',
        'administered_by',
        'next_dose_date',
        'remarks'
    ];

    protected $casts = [
        'vaccination_date' => 'date',
        'next_dose_date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the completion status of the immunization record.
     * Placeholder logic: User should update this based on business rules.
     * 
     * @return string
     */
    public function getCompletionStatus()
    {
        if (!$this->vaccination_date || Carbon::parse($this->vaccination_date)->isFuture()) {
            return 'Not Started';
        }

        if (!$this->next_dose_date || Carbon::parse($this->next_dose_date)->isPast()) {
            return 'Completed';
        }

        return 'In Progress';
    }
}
