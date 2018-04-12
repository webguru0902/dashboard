<?php

namespace App\app;

use Illuminate\Database\Eloquent\Model;

class trackmanager extends Model
{
    //
    protected $table = 'trackmanager';
     protected $fillable = [
        'Tracktitle', 'url',
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
