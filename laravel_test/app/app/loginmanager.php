<?php

namespace App\app;

use Illuminate\Database\Eloquent\Model;

class loginmanager extends Model
{
     protected $table = 'loginmanger';
     protected $fillable = [
        'name', 'password','geo','service','state',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
