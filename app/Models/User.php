<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username',
        'email',
        'phone',
        'password',
        'role',
        'first_name',
        'last_name',
        'birthday',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getAvatarUrl()
    {
        if ($this->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->avatar)) {
            return asset('storage/' . $this->avatar);
        }
        return asset('img/default-avatar.png');
    }
}
