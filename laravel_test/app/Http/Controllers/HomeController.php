<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\app\loginmanager;
use Redis;
use App\app\trackmanager;
use App\app\playmanager;
require base_path().'/geoip2.phar';
use GeoIp2\Database\Reader;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $redis = Redis::connection();

        // $redis->set('name','toru');
        // $name = $redis->get('name');
        // $name = Redis::command('get',['name']);
        $id = 1;
        $timestamp = 1000;
        $resultarray = array(); 
        $stimearr = array();
        $track_arr = array();
        $etimearr = array();
        $clientinfo = array();
        $counttrack = 0;
        $errorcount = 0;
        $reader = new Reader(base_path().'/GeoIP/GeoIP2-City.mmdb');
        // $record = $reader->city('128.101.101.101');
        // var_dump($record->country->name . "\n"); // 'United States'
        // die();
        // Redis::command('set',[$key,'toru']);
        // $list = Redis::command('keys',[$key]);
        // $last_cell = Redis::command('RPOP',[$list]);
        // $key = $redis->keys($key);
        // $key = Redis::command('scan',['0']);
        // $redis->rpush("languages", "french");
        // $key = $redis->llen("languages");
        // $key = Redis::command('sort',['mylist','ALPHA']);

        // $list = $redis->rpush('list',$key);
        for($id = 1;$id > 0; $id++)
        {
            $start_timekey = 'client'.$id.'_sessionstarttime_'.'*';
            $end_timekey = 'client'.$id.'_sessionendtime_'.'*';
            $current_Trackkey = 'client'.$id.'_currenttrack_'.'*';
            $current_Errorkey = 'client'.$id.'_error_'.'*';
            $ip_key = 'client'.$id.'_ip_'.'*';

            $key = $redis -> keys($start_timekey);      //get startsessiontime keys
            $endkey = $redis -> keys($end_timekey);     //get endsessiontime keys
            $trackkeys = $redis -> keys($current_Trackkey);     //get currenttrackkeys
            $errorkeys = $redis -> keys($current_Errorkey);     //get error keys
            $ipkeys = $redis->keys($ip_key);                    //get ip keys

            if($key == NULL) break;

            $redis->rpush('start_timelist',$key);       //insert the startsessiontime keys in start_timelist
            $redis->rpush('end_timelist',$endkey);      //insert the endsessiontime keys in end_timelist
            $redis->rpush('ip_list',$ipkeys);           //insert the ipkeys in ip_list
            $redis->rpush('track_list',$trackkeys);     //insert the trackkeys in track_list
            $redis->rpush('error_list',$errorkeys);     //insert the error keys in error_list

            $sortlist = $redis->sort('start_timelist', array('alpha' => true));             //sort the start_sessiontime
            $endsortlist = $redis->sort('end_timelist', array('alpha' => true));            //sort the endsessiontime
            $ipsortlist = $redis->sort('ip_list', array('alpha' => true));                  //sort the ip list
            $tracksortlist = $redis->sort('track_list', array('alpha' => true));            //sort the track list
            $errorsortlist = $redis->sort('error_list', array('alpha' => true));            //sort the error list
            $redis->ltrim('start_timelist','-1','0');         //initialize the start_timelist
            $redis->ltrim('end_timelist','-1','0');    //initialize the end_timelist
            $redis->ltrim('ip_list','-1','0');         //initialize the ip_list
            $redis->ltrim('track_list','-1','0');      //initialize the track_list
            $redis->ltrim('error_list','-1','0');       //initialize the error_list
            $start_time = end($sortlist);           //get the recent startsessiontime key
            $end_time = end($endsortlist);          //get the recent endsessiontime key
            $ip_recent = end($ipsortlist);             //get the recent ip key
            $track_recentkey = end($tracksortlist);        //get the recent track key
            $error_current = end($errorsortlist);          //get the error key of currenttimestamp
            
            $stime = $redis->get($start_time);    //get start session time            
            $etime = $redis->get($end_time);      //get end session time
            $error_status = $redis->get($error_current);   //get the current error
            $track_recentstamp = substr($track_recentkey,21);       //get the current timestamp from trackkey
            settype($track_recentstamp,'int');
            $session_progress = ($track_recentstamp-$stime)/($etime-$stime);     //get the session progress
            $runtime = gmdate("H:i:s", ($track_recentstamp-$stime));
            $totaltime = gmdate("H:i:s", ($etime-$stime));
            $ip_addr = $redis->get($ip_recent);   //get ip address
            $record = $reader->city($ip_addr);
            $country_name = $record->country->name;   //get countryname from ip address

            foreach ($trackkeys as $trackkey) {
                 $track_timestamp = substr($trackkey,21);
                 settype($track_timestamp,'int');
                //  var_dump($track_timestamp);
                //  die();
                 if(($stime < $track_timestamp) && ($track_timestamp < $etime)){
                    $counttrack = $counttrack + 1;

                 }
            }
            foreach ($errorkeys as $errorkey) {
                $errorcount++;
            }
            if($errorcount == 0){
                $status = "healthy";
            }else{
                $status = $error_status;
            }
            array_push($stimearr,$stime);
            array_push($etimearr,$etime);
            array_push($resultarray,$start_time);
            $client = array(
                'id' => $id,
                'ip_addr' => $ip_addr,
                'country_name' => $country_name,
                'session_progress' =>$session_progress,
                'runtime' => $runtime,
                'totaltime' => $totaltime,
                'status' => $status
            );
            array_push($clientinfo,$client);    
        }
        $count = $id-1;        
        return view('home',compact('count','counttrack','errorcount','clientinfo'));
    }
    public function browser()
    {
        return view('browser_map');
    }
    public function userlist()
    {   
        $users = User::paginate(5);
        // var_dump($users);
        return view('userlist',compact('users'));
    }
    public function showProfile(){
        
    }
    public function loginmanager(){
        $managers = loginmanager::paginate(5);
        return view('login_manager',compact('managers'));
    }
    public function trackmanager(){
        $trackmanagers = trackmanager::paginate(5);
        return view('trackmanager',compact('trackmanagers'));
    }
    public function playlistmanager(){
        $playmanagers = playmanager::paginate(5);
        return view('playlist',compact('playmanagers'));
    }
}
