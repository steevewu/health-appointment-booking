<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Treatment extends Model
{
    use HasFactory;


    protected $fillable = [
        'notes',
        'medication'
    ];


    public function appointment(): BelongsTo{
        return $this->belongsTo(Appointment::class, 'appointment_id', 'id');
    }
}
