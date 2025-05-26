<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'sex',
        'address',
        'contact_number',
        'birthdate',
        'email',
        'password',
        'isAdmin',
        'last_login_at',
        'last_logout_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthdate' => 'date',
        'last_login_at' => 'datetime',
        'last_logout_at' => 'datetime',
        'isAdmin' => 'boolean',
    ];

    public function familyPlannings()
    {
        return $this->hasMany(FamilyPlanning::class);
    }

    public function immunizationRecords()
    {
        return $this->hasMany(ImmunizationRecord::class);
    }
    
    public function isAdmin()
    {
        return $this->isAdmin;
    }
}
