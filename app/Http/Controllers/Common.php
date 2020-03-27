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
    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg')
        $image = imagecreatefromjpeg($source);
    elseif ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($source);

    try{
        return imagejpeg($image, $destination, $quality);
    }
    catch (Exception $x) {
        dd($x);
        return false;
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
