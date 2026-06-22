<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelNotification extends Model
{
    protected $table = 'hotel_notifications';

    protected $fillable = [
        'type',
        'title',
        'body',
        'reservation_id',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];
}