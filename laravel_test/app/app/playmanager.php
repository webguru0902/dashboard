<?php

namespace App\app;

use Illuminate\Database\Eloquent\Model;

class playmanager extends Model
{
    //
     protected $table = 'playmanager';
     protected $fillable = [
        'playtitle', 'playurl',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
         'remember_token',
    ];
}
