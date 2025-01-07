<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'join_date',
        'phone_number',
        'status',
        'role_name',
        'avatar',
        'position',
        'department',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Tambahkan fungsi untuk cek role admin
    public function isAdmin()
    {
        return $this->role_name === 'admin';
    }

    // Tambahkan fungsi untuk cek role user
    public function isUser()
    {
        return $this->role_name === 'user';
    }

    /** generate id */
    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $latestUser = self::orderBy('user_id', 'desc')->first();
            $nextID = $latestUser ? intval(substr($latestUser->user_id, 3)) + 1 : 1;
            $model->user_id = 'KH-' . sprintf("%04d", $nextID);

            // Ensure the user_id is unique
            while (self::where('user_id', $model->user_id)->exists()) {
                $nextID++;
                $model->user_id = 'KH-' . sprintf("%04d", $nextID);
            }
        });
    }
}