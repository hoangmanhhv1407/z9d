<?php

namespace App;

use App\Models\TransactionHistory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use Notifiable, HasApiTokens;

    protected $table = 'users';
    protected $fillable = [
        'userid', 'userpassword', 'remember_token',
    ];

    protected $hidden = [
        'userpassword', 'remember_token',
    ];

    public function transactionHistory()
    {
        return $this->hasMany(TransactionHistory::class, 'user_id', 'id');
    }

    public function getAuthPassword()
    {
        return $this->userpassword;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    protected $casts = [
        'admin' => 'integer',
    ];
}