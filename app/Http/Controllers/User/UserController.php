<?php
namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
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
}