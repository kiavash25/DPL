<?php

namespace App\Http\Controllers;

use App\Events\makeLog;
use App\models\ForgetPassword;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function loginRegisterPage()
    {
        return view('auth.newLogin');
    }

    public function forgetPassword(Request $request)
    {
        if(isset($request->email)){
            $user = User::where('email', $request->email)->first();
            if($user != null){
                $lastSend = ForgetPassword::where('userId', $user->id)->latest()->first();
                if($lastSend == null || Carbon::now()->diffInMinutes($lastSend->created_at) > 5){
                    $newPassword = generateRandomString(6);
                    $user->password = Hash::make($newPassword);
                    $user->save();

                    $newForget = new ForgetPassword();
                    $newForget->userId = $user->id;
                    $newForget->save();

                    event(new makeLog([
                        'userId' => $user->id,
                        'subject' => 'forgetPasswords',
                        'referenceTable' => 'forget_passwords',
                        'referenceId' => $newForget->id,
                    ]));

                    forgetPassEmail($user->name, $user->email, $newPassword);
                    echo 'ok';
                }
                else
                    echo 'beforeSent';
            }
            else
                echo 'notUser';
        }
        else
            echo 'nok';

        return;
    }

}
