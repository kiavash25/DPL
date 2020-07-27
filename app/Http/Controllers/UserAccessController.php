<?php

namespace App\Http\Controllers;

use App\models\Acl;
use App\models\Language;
use App\User;
use Illuminate\Http\Request;

class UserAccessController extends Controller
{
    public function list()
    {
        $aclList = [
            'activity',
            'destination',
            'package',
            'journal',
            'setting',
            'userAccess',
        ];

        $languageList = Language::all();

        $adminUser = User::where('level', 'admin')->get();
        foreach ($adminUser as $user) {
            $user->acl = Acl::where('userId', $user->id)->get();
            $user->aclName = Acl::where('userId', $user->id)->pluck('role')->toArray();
            $user->language = Acl::where('userId', $user->id)->where('role', 'language')->pluck('value')->toArray();
        }

        return view('profile.admin.userAccess.userAccessList', compact(['adminUser', 'aclList', 'languageList']));
    }

    public function aclStore(Request $request)
    {
        if(isset($request->userId) && isset($request->acl)){
            $user = User::find($request->userId);
            if($user != null){
                Acl::where('userId', $user->id)->where('role', '!=', 'language')->delete();
                foreach ($request->acl as $item){
                    $acl = new Acl();
                    $acl->userId = $user->id;
                    $acl->role = $item;
                    $acl->save();
                }

                $aclName = Acl::where('userId', $user->id)->pluck('role')->toArray();
                echo json_encode(['status' => 'ok', 'result' => $aclName]);
            }
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function languageStore(Request $request)
    {
        if(isset($request->userId) && isset($request->language)){
            $user = User::find($request->userId);
            if($user != null){
                Acl::where('userId', $user->id)->where('role', 'language')->delete();
                foreach ($request->language as $item){
                    $acl = new Acl();
                    $acl->userId = $user->id;
                    $acl->role = 'language';
                    $acl->value = $item;
                    $acl->save();
                }

                $langName = Acl::where('userId', $user->id)->where('role', 'language')->pluck('value')->toArray();
                echo json_encode(['status' => 'ok', 'result' => $langName]);
            }
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function disableAccess(Request $request)
    {
        if(isset($request->id)){
            $user = User::find($request->id);
            $user->level = 'user';
            $user->save();

            Acl::where('userId', $user->id)->delete();

            echo json_encode(['status' => 'ok']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }
}
