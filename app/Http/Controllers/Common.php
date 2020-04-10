<?php

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
