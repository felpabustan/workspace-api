<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'space_id', 'user_id', 'start_time', 'end_time', 'duration', 'rate_type', 'price', 'status'
    ];

    public function space()
    {
        return $this->belongsTo(Space::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function calculatePrice()
    {
        $space = $this->space;
        
        switch ($this->rate_type) {
            case 'hourly':
                $rate = $space->rate_hourly;
                break;
            case 'daily':
                $rate = $space->rate_daily;
                break;
            case 'weekly':
                $rate = $space->rate_weekly;
                break;
            case 'monthly':
                $rate = $space->rate_monthly;
                break;
            default:
                $rate = 0;
        }

        return $rate * $this->duration;
    }

}

