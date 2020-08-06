<?php

namespace App\Http\Controllers;

use App\Events\makeLog;
use App\models\Countries;
use App\models\ForgetPassword;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function loginRegisterPage()
    {
        $redirectBack = url()->previous();
        return view('auth.newLogin', compact(['redirectBack']));
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


    public function profile()
    {
        return redirect()->route('profile.setting');
        return view('profile.user.dashboard');
    }

    public function profileSetting()
    {
        $user = auth()->user();
        $country = Countries::select(['id', 'countryName'])->orderBy('countryName')->get();
        if($user->birthDate != null){
            $birthDate = explode('-', $user->birthDate);
            $user->day = $birthDate[2];
            $user->month = $birthDate[1];
            $user->year = $birthDate[0];
        }

        if($user->pic != null)
            $user->pic = asset('uploaded/users/'.$user->id.'/'.$user->pic);

        return view('profile.user.setting', compact(['user', 'country']));
    }

    public function uploadProfilePic(Request $request)
    {
        $user = auth()->user();

        $location = __DIR__ . '/../../../public/uploaded/users/' . $user->id;
        if(!file_exists($location))
            mkdir($location);
        $image = $request->file('pic');
        $dirs = 'uploaded/users/' . $user->id;
        $size = [
            [
                'width' => 150,
                'height' => null,
                'name' => '',
                'destination' => $dirs
            ]
        ];
        $fileName = resizeImage($image, $size);
        if($fileName != 'error'){

            if($user->pic != null && is_file($location.'/'.$user->pic))
                unlink($location.'/'.$user->pic);

            $user->pic = $fileName;
            $user->save();
            $pic = asset($dirs.'/'.$user->pic);
            echo json_encode(['status' => 'ok', 'result' => $pic]);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function updateProfileInfo(Request $request)
    {
        $user = auth()->user();

        $checkEmail = User::where('email', $request->email)->where('id', '!=', $user->id)->first();
        if($checkEmail == null)
            $user->email = $request->email;

        $user->name = $request->name;

        if($request->phone == 0)
            $user->phone = null;
        else
            $user->phone = $request->phone;

        if($request->country == 0)
            $user->countryId = null;
        else
            $user->countryId = $request->country;

        if($request->gender == -1)
            $user->gender = null;
        else
            $user->gender = $request->gender;

        if($request->birthDate != '0000-00-00')
            $user->birthDate = $request->birthDate;
        else
            $user->birthDate = null;

        $user->save();

        if($checkEmail != null)
            echo json_encode(['status' => 'email']);
        else
            echo json_encode(['status' => 'ok']);
    }

    public function updateProfilePassword(Request $request)
    {
        $user = auth()->user();
        if($request->password == $request->confirm){
            $user->password = Hash::make($request->password);
            $user->save();

            echo 'ok';
        }
        else
            echo 'nok';

        return;
    }

}
