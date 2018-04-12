<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\user;
use Validator;
use Redirect;

use Auth;
use Input;
class FirstController extends Controller
{
    public function store( Request $request ){
        // echo "Hello Laravel";
        // $email = $request->input('email');
        // $password = $request->input('password');
        // var_dump($email);
        $data = $request->all();
        $rule = array(
            'email' => 'required|email',
            'password' => 'required|min:6',
        );
        $message = array(
            'email.required' => 'The Email should contain @',
            'password.required' => 'The password should contain 6 characters',
        );
        $validator = Validator::make($data, $rule, $message);

        if($validator->fails()){
            return Redirect::to('/')->withErrors($validator);
        }
        else{
            // SignIn::formstore($data);
            $userdata = array(
                'email'=>$request->email,
                'password'=>$request->password
            );
            // $vemail = Input::get('email');
            // $vpass = Input::get('passsword');
            // $array = array_keys($userdata);
            // echo $userdata[$array[1]]; die();
            // $userdata = Input::except(array('_token'));

            $loginAttempt = LoginAttempt::create([
                'ip' => $request->ip(),
                'time' => Carbon::now(),
                'user' => $request->username,
            ]);
            
            if(Auth::attempt($userdata)){
                $loginAttempt->success = true;       //logged in
                $loginAttempt->save();
                return Redirect::to('home');
                // echo "match";
            }else{
                // return Redirect::to('/');
                echo "no match";
            }
        }
    }
}
