<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $fillable = [
        'bkg_room_id',
        'room_type',
        'capacity',
        'fileupload',
        'status',
        'has_projector',
        'has_sound_system',
        'has_tv'
    ];


    /** generate id */
    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $latestUser = self::orderBy('bkg_room_id', 'desc')->first();
            $nextID = $latestUser ? intval(substr($latestUser->bkg_room_id, 3)) + 1 : 1;
            $model->bkg_room_id = 'BR-' . sprintf("%04d", $nextID);

            // Ensure the bkg_room_id is unique
            while (self::where('bkg_room_id', $model->bkg_room_id)->exists()) {
                $nextID++;
                $model->bkg_room_id = 'BR-' . sprintf("%04d", $nextID);
            }
        });
    }
}
