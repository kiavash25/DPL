<?php

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