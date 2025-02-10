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
        'password',
        'phone_number',
        'role_name',
        'division',
        'department',
        'avatar',
        'status',
        'join_date'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
        public function getAuthIdentifierName()
    {
        return 'user_id';
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Tambahkan fungsi untuk cek role admin

    public function isSuperAdmin()
    {
        return $this->role_name === 'superadmin';
    }

    public function isAdmin()
    {
        return $this->role_name === 'admin';
    }

    // Tambahkan fungsi untuk cek role user
    public function isUser()
    {
        return $this->role_name === 'user';
    }
    public function User()
    {
        return $this->name;
    }
    /**
     * Generate ID hanya jika user_id tidak diset
     */
    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            // Jika user_id sudah diset (dari form atau seeder), gunakan itu
            if (!empty($model->user_id)) {
                return;
            }

            // Jika user_id belum diset, generate secara otomatis berdasarkan divisi
            $divisionPrefix = 'USR'; // default prefix

            // Set prefix berdasarkan divisi
            switch ($model->division) {
                case 'Building Management':
                    $divisionPrefix = 'BM';
                    break;
                case 'Construction and Property':
                    $divisionPrefix = 'CP';
                    break;
                case 'IT Business and Solution':
                    $divisionPrefix = 'ITBS';
                    break;
                case 'Finance and Accounting':
                    $divisionPrefix = 'FA';
                    break;
                case 'Human Capital and General Affair':
                    $divisionPrefix = 'HC';
                    break;
                case 'Risk, System, and Compliance':
                    $divisionPrefix = 'RSC';
                    break;
                case 'Internal Audit':
                    $divisionPrefix = 'IA';
                    break;
            }

            // Hitung jumlah user dengan prefix yang sama
            $count = self::where('user_id', 'like', $divisionPrefix . '%')->count();
            $nextNumber = $count + 1;

            // Format: [PREFIX]01, [PREFIX]02, dst
            $model->user_id = $divisionPrefix . sprintf("%02d", $nextNumber);

            // Pastikan user_id unik
            while (self::where('user_id', $model->user_id)->exists()) {
                $nextNumber++;
                $model->user_id = $divisionPrefix . sprintf("%02d", $nextNumber);
            }
        });
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/avatars/' . $this->avatar);
        }
        return asset('assets/img/profiles/avatar-11.jpg');
    }
}
