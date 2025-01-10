<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $primaryKey = 'bkg_id';
    public $incrementing = false;

    protected $fillable = [
        'bkg_id',
        'name',
        'room_type',
        'total_numbers',
        'date',
        'time_start',
        'time_end',
        'email',
        'phone_number',
        'message',
        'status_meet'
    ];

    // Di model Booking
    public function user()
    {
        return $this->belongsTo(User::class, 'name', 'name');
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (!$model->bkg_id) {
                $latestBooking = static::latest()->first();
                $number = $latestBooking ? intval(substr($latestBooking->bkg_id, 4)) + 1 : 1;
                $model->bkg_id = 'BKG-' . str_pad($number, 5, '0', STR_PAD_LEFT);
            }
        });
    }
}