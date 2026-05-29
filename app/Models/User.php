<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_approved',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_approved' => 'boolean',
        ];
    }

    public function siswa(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Siswa::class);
    }

    public function guru(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Guru::class);
    }

    public function pembimbingIndustri(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PembimbingIndustri::class);
    }
}
