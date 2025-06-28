<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = ['username', 'email', 'password', 'role'];

    protected $hidden = ['password'];

    // Saat membuat pengguna baru, pastikan password di-hash dengan MD5
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = md5($password);
    }
}
