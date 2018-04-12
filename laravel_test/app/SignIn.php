<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Input;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SignIn extends Authenticatable
{
    protected $table = "user_info";
    public static function formstore($data){
        // echo "Hello";
        // var_dump($data);
        $email = Input::get('email');
        // echo $email;
        $password = Input::get('password');
        // echo $password;
        $userinfo = new SignIn();
    }
}
