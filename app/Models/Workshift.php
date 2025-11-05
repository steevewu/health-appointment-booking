<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;

class Workshift extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'doctor_id'
    ];



    public static function isConflict($start, $end, $doctor_id): bool
    {

        return Workshift::where('doctor_id', $doctor_id)
            ->whereHas('event', function ($query) use ($start, $end) {
                $query->where('start_at', '<', $end)
                    ->where('end_at', '>', $start);
            })
            ->exists();
    }



    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'id');
    }


    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }


    public function appointments(): HasOneOrMany
    {
        return $this->hasMany(Appointment::class, 'workshift_id', 'id');
    }


    public function isBooked(): bool
    {
        return $this->appointments()
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();
    }

}
