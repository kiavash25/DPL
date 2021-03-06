<?php

use App\models\Activity;
use App\models\Destination;
use Carbon\Carbon;
use PHPMailer\PHPMailer\PHPMailer;

function storeImage($source, $destination){
    try {
        move_uploaded_file($source, $destination);
        return true;
    }
    catch (Exception $x){
        return false;
    }
}

function compressImage($source, $destination, $quality){

    try {
        move_uploaded_file($source, $destination);
        return true;
    }
    catch (Exception $x){
        return false;
    }
//    $info = getimagesize($source);
//
//    if ($info['mime'] == 'image/jpeg')
//        $image = imagecreatefromjpeg($source);
//    elseif ($info['mime'] == 'image/png')
//        $image = imagecreatefrompng($source);
//
//    try{
//        return imagejpeg($image, $destination, $quality);
//    }
//    catch (Exception $x) {
//        return false;
////        dd($x);
//    }
}

//    http://image.intervention.io/
function resizeImage($pic, $size){
    try {
        $image = $pic;
        $randNum = random_int(100,999);
        $fileName = time() . $randNum. '.' . $image->getClientOriginalExtension();
        foreach ($size as $item){
            $input['imagename'] = $item['name'] .  $fileName ;
            $destinationPath = public_path($item['destination']);
            $img = \Image::make($image->getRealPath());
            $width = $img->width();
            $height = $img->height();

            if($item['width'] != null && $width > $item['width'])
                $width = $item['width'];

            if($item['height'] != null && $height > $item['height'])
                $height = $item['height'];

            $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$input['imagename']);
        }
        return $fileName;
    }
    catch (Exception $exception){
        return 'error';
    }
}

function makeSlug($name){

    $name = str_replace(':', '', $name);
    $name = str_replace('\\', '', $name);
    $name = str_replace('|', '', $name);
    $name = str_replace('/', '', $name);
    $name = str_replace('*', '', $name);
    $name = str_replace('?', '', $name);
    $name = str_replace('<', '', $name);
    $name = str_replace('>', '', $name);
    $name = str_replace('"', '', $name);
    $name = str_replace(' ', '_', $name);

    return $name;
}

function commaMoney($_money){
    $nMoney = '';
    $j = 0;
    $split = str_split($_money);
    for ($i = count($split)-1; $i >= 0 ; $i--){
        if($j % 3 == 0 && $j != 0)
            $nMoney = ','.$nMoney;
        $nMoney = $split[$i] . $nMoney;
        $j++;
    }
    return $nMoney;
}

function trueShowForTextArea($text){
    $breaks = array("<br />","<br>","<br/>");
    $text = str_ireplace("\r\n", "", $text);
    $text = str_ireplace($breaks, "\r\n", $text);

    return $text;
}

function getKindPic($dir, $pic, $kind){
    $loc = __DIR__ .'/../../../public/' . $dir . '/' . $kind . '_' . $pic;
    if(is_file($loc) && $kind != '')
        return asset($dir. '/' . $kind . '_' . $pic);
    else
        return asset($dir. '/' . $pic);
}

function getMinPackage($pack, $kindPic = 'min'){
    $destPack = Destination::select(['id', 'name', 'slug'])->find($pack->destId);
    $pack->mainActivity = Activity::find($pack->mainActivityId);
    if($pack->langSource == 0){
        $picFolder = $pack->id;
        $picName = $pack->pic;
    }
    else{
        $picFolder = $pack->langSource;
        $picName = \App\models\Package::find($pack->langSource)->pic;
    }
    $loc = 'uploaded/packages/' . $picFolder;
    $pack->pic = getKindPic($loc, $picName, $kindPic);
    $pack->description = strip_tags($pack->description);
    if($pack->sDate != null) {
        $pack->sD = Carbon::createFromFormat('Y-m-d', $pack->sDate)->format('d');
        $pack->sM = Carbon::createFromFormat('Y-m-d', $pack->sDate)->format('M');
    }
    else{
        $pack->sD = 'Call';
        $pack->sM = 'Us';
    }
    if ($destPack != null)
        $pack->url = route('show.package', ['slug' => $pack->slug]);

    return $pack;
}

function emptyFolder($dir){
    try {
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file) {
                if ($file != '.' && $file != '..') {
                    if (is_dir($dir . '/' . $file))
                        emptyFolder($dir . '/' . $file);
                    else
                        unlink($dir . '/' . $file);
                }
            }


            $files = scandir($dir);
            if (count($files) == 2)
                rmdir($dir);
            else
                return false;

            return true;
        } else
            return false;
    }
    catch(\Exception $exception){return false;}
}

function generateRandomString($length = 20) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

//email
function forgetPassEmail($userName, $email, $newPassword){
    $header = __('Forget password');
    $view = \View::make('email.forgetPass', compact(['header', 'userName', 'newPassword']));
    $html = $view->render();

    if(sendEmail($html, $header, $email))
        return true;
    else
        return false;
}

function sendEmail($text, $subject, $to){

    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = 0;
        $mail->CharSet = "UTF-8";
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $text;
        $mail->setFrom('support@discoverpersialand.com', 'dpl');
        $mail->addAddress($to);
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => true,
                'verify_peer_name' => true,
                'allow_self_signed' => true
            )
        );
        $mail->send();

        return true;
    }
    catch (Exception $e) {
        return false;
    }
}

function checkAcl($userId, $role){
    $user = \App\User::find($userId);
    if($user->level == 'admin') {
        $acl = \App\models\Acl::where('userId', $userId)->where('role', $role)->first();
        return $acl != null;
    }
    else if($user->level == 'superAdmin')
        return true;
    else
        return false;
}
