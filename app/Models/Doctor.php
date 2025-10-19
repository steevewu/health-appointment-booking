<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Doctor extends Model
{
    use HasFactory;


    public $timestamps = false;


    protected $fillable = [
        'fullname',
        'dob',
        'address',
        'phone_number'
    ];



    public function user(): BelongsTo{
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
