<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser, HasName
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;



    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
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
        'password' => 'hashed',
    ];


    public function patient(): HasOne{
        return $this->hasOne(Patient::class, 'user_id', 'id');
    }


    public function doctor(): HasOne{
        return $this->hasOne(Doctor::class, 'user_id', 'id');
    }


    public function canAccessPanel(\Filament\Panel $panel): bool{
        return $this->hasVerifiedEmail() && $this->hasRole($panel->getId());
    }

    public function getFilamentName(): string
    {
        $user = auth()->user();
        if($user->hasRole('admin')) return 'Admin';
        if($user->hasRole('doctor')){
            if ($user->doctor == null) return '';
            return "{$user->doctor->firstname} {$user->doctor->lastname}";
        }
        if ($user->patient == null) return '';
        return "{$user->patient->firstname} {$user->patient->lastname}";
    }

}
