<?php
namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Tracker;
use DB;
class UserController extends Controller{

    public function fetchUser(Request $request){
        $deviceId=$request->deviceId;
        $user=User::where(["DEVICE_ID"=>$deviceId])->first();
        if($user==null)
            {
                $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $user=new User;
                $user->DEVICE_ID=$request->deviceId;
                $user->USER_ID=substr(str_shuffle($permitted_chars), 0, 6);
                $user->save();
               
            }
            return ["Status"=>$user->USER_ID];
    }

    public function addTracker(Request $request)
    {
        $user=User::where(["DEVICE_ID"=>$request->deviceId,"USER_ID"=>$request->userId])->first();
        if($user!=null)
        {
            $tracker=new Tracker;
            $tracker->USER_ID=$request->userId;
            $tracker->LATITUDE=$request->latitude;
            $tracker->LONGITUDE=$request->longitude;
            $tracker->save();
        }
        return ["Status"=>true];
    }
    public function fetchStatus(Request $request)
    {
        $count=Tracker::with('activeUser')
        ->where('USER_ID','!=',$request->userId)
        ->where(DB::raw("(3959 * acos(cos(radians(" . $request->latitude . ")) * cos (radians(LATITUDE) )*cos(radians(LONGITUDE)-radians(" . $request->longitude . "))
        +sin(radians(" . $request->latitude . "))*sin(radians(LATITUDE))))"), "<", env("SAFE_DISTANCE"))->count();
        return ["Status"=>$count];
    }
}