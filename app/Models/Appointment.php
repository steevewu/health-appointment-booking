<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Workshift;

class Appointment extends Model
{
    use HasFactory;


    protected $fillable = [
        'workshift_id',
        'patient_id',
        'message'
    ];



    public static function isConflict(int|null $patient_id, int|null $workshift_id): bool
    {

        $workshift = Workshift::with('event')->findOrFail($workshift_id);
        $event_start = $workshift->event->start_at;
        $event_end = $workshift->event->end_at;

        return self::where('patient_id', $patient_id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereHas(
                'workshift.event',
                function ($query) use ($event_start, $event_end) {

                    $query->where(
                        function ($q) use ($event_start, $event_end) {

                            $q->whereBetween('start_at', [$event_start, $event_end])
                                ->orWhereBetween('end_at', [$event_start, $event_end])
                                ->orWhere(
                                    function ($q2) use ($event_start, $event_end) {
                                        $q2->where('start_at', '<=', $event_start)
                                            ->where('end_at', '>=', $event_end);
                                    }
                                );
                        }
                    );

                }
            )
            ->exists();


    }




    public function scopeStatus($query, $status = 'confirmed')
    {
        return $query->where('status', $status);
    }



    public function workshift(): BelongsTo
    {
        return $this->belongsTo(Workshift::class, 'workshift_id', 'id');
    }

    public function getEvent(): Event
    {
        return $this->workshift->event;
    }

}
