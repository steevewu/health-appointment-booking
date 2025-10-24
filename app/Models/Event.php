<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Event extends Model
{
    use HasFactory;


    protected $fillable = [
        'title',
        'start_at',
        'end_at',
        'description'
    ];




    public static function isConflict($start, $end): bool
    {
        return Event::where(function ($query) use ($start, $end) {
            $query->where('start_at', '<', $end)
                ->where('end_at', '>', $start);
        })->exists();
    }

    // public static function isConflict(Event $event1, Event $event2): bool{
    //     return 
    // }

    public function workshifts(): HasMany{
        return $this->hasMany(Workshift::class, 'event_id', 'id');
    }


    public function appointments(): HasManyThrough{
        return $this->hasManyThrough(Appointment::class, Workshift::class, 'event_id', 'workshift_id', 'id', 'id');
    }
}
