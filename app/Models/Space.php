<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Space extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'description', 
        'rate_hourly', 
        'rate_daily', 
        'rate_weekly', 
        'rate_monthly', 
        'availability',
        'image'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
