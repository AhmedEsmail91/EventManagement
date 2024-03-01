<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'start_time',
        'end_time','user_id'
    ];
    public function user():BelongsTo{
        // a user who will be the orgnizer.
        return $this->belongsTo(User::class); // one to one relationship.
    }
    public function attendees(): HasMany{
        return $this->hasMany(Attendee::class); // one event can be attended by many attendees. so one event has many attendees
    }
    
}

