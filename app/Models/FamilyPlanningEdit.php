<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyPlanningEdit extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_planning_id',
        'user_id',
        'changes',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public function familyPlanning()
    {
        return $this->belongsTo(FamilyPlanning::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 