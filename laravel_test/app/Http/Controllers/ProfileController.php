<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\app\loginmanager;
use App\app\trackmanager;
use App\app\playmanager;
class ProfileController extends Controller
{
    //
    public function __construct(){
        parent::__construct();
    }

    public function createUser(Request $request){
            $data = $request->all();
            $newUser = new User();
        
            $newUser['email']= $request->email;

            $newUser['password'] = bcrypt($request->password);
            $newUser['role'] = $request->role;
            $newUser = $newUser->save();

            $response = array(
                'success'=>"Created successfully"
            );
        
        echo json_encode($response);
    }

    public function deleteUser(Request $request){
        $ids = $request->ids;
        User::whereIn('id',$ids)->delete();
    }
    public function editUser(Request $request){
        $id = $request->id;
        $email = $request->email;
        $password = bcrypt($request->password);
        $role = $request->role;
        User::where('id',$id)->update(array('email' => $email,'password' => $password,'role' => $role));
        $response = array(
                'success'=>"Created successfully"
        );
        echo json_encode($response);
    }
    public function addloginManager(Request $request){
        $data = $request->all();
        $manager = new loginmanager();
    
        $manager['name']= $request->name;

        $manager['password'] = bcrypt($request->password);
        $manager['geo'] = $request->geo;
        $manager['service'] = "Shopify";
        $manager['state'] = "Active";
        $manager = $manager->save();

        $response = array(
            'success'=>"Created successfully"
        );
        
        echo json_encode($response);
    }
    public function deleteManager(Request $request){
        $ids = $request->ids;
        loginmanager::whereIn('id',$ids)->delete();
    }
    public function updateManager(Request $request){
        $id = $request->id;
        $state = $request->state;
        $service = $request->service;
        loginmanager::where('id',$id)->update(array('state' => $state,'service' => $service));
        $response = array(
                'success'=>"Created successfully"
        );
        echo json_encode($response);
    }
    public function addtrackManager(Request $request){
        $data = $request->all();
        $trackmanager = new trackmanager();
    
        $trackmanager['Tracktitle']= $request->tracktitle;

        $trackmanager['url'] = $request->url;
        $trackmanager = $trackmanager->save();

        $response = array(
            'success'=>"Created successfully"
        );
        
        echo json_encode($response);
    }
    public function deletetrackManager(Request $request){
        $ids = $request->ids;
        trackmanager::whereIn('id',$ids)->delete();
    }
    public function addplaylistManager(Request $request){
        $data = $request->all();
        $playmanager = new playmanager();
    
        $playmanager['playtitle']= $request->playtitle;

        $playmanager['playurl'] = $request->playurl;
        $playmanager = $playmanager->save();

        $response = array(
            'success'=>"Created successfully"
        );
        
        echo json_encode($response);
    }
    public function deleteplaylistManager(Request $request){
        $ids = $request->ids;
        playmanager::whereIn('id',$ids)->delete();
    }
}
