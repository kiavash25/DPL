<?php

namespace App\Http\Controllers;

use App\models\Journal;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    public static function test($get)
    {
        echo $get;
        return 2;
    }

    public static function keywordDensity($text, $keyword){
        $text = html_entity_decode($text);
        $text = strip_tags($text);
        $text = trueShowForTextArea($text);

        $arr = explode(' ', $text);
        $countAllWords = count($arr);
        if(count(explode(' ', $keyword)) == 1) {
            $keyInText = 0;
            foreach ($arr as $item) {
                if ($item == $keyword)
                    $keyInText++;
            }
        }
        else
            $keyInText = substr_count($text, $keyword);

        $keywordCount = count(explode(' ', $keyword));

        $keyWordDensity = (( (int)$keywordCount * (int)$keyInText) / (int)$countAllWords) * 100;
        $keyWordDensity = floor($keyWordDensity * 100)/100;

        return $keyWordDensity;
    }

    public static function keywordInText($text, $keyword, $textKind){
        if($textKind == 'firstP'){
            $startP = strpos($text, '<p');
            $start = strpos($text, '>', $startP);
            $end = strpos($text, '</p>', $start);
            $len = $end - $start;
            $p = substr($text, $start+1,  $len);
        }
        else if($textKind == 'common')
            $p = $text;
        else if($textKind == 'slug')
            $p = str_replace('_', ' ', $text);

        $p = html_entity_decode($p);
        $p = strip_tags($p);
        $p = trueShowForTextArea($p);

        if(count(explode(' ', $keyword)) == 1){
            $arr = explode(' ', $p);
            $keyInText = 0;
            foreach ($arr as $item){
                if($item == $keyword)
                    $keyInText++;
            }
        }
        else
            $keyInText = substr_count($p, $keyword);

        return $keyInText;
    }

    public static function uniqueJournalKeyword($keyword, $id)
    {
        if($id != 0)
            $journal = Journal::where('keyword', $keyword)->where('id', '!=', $id)->first();
        else
            $journal = Journal::where('keyword', $keyword)->first();

        if($journal != null)
            return false;
        else
            return true;
    }

    public static function uniqueJournalSeoTitle($seoTitle, $id)
    {
        if($id != 0)
            $journal = Journal::where('seoTitle', $seoTitle)->where('id', '!=', $id)->first();
        else
            $journal = Journal::where('seoTitle', $seoTitle)->first();

        if($journal != null)
            return false;
        else
            return true;
    }

    public static function uniqueJournalSlug($slug, $id)
    {
        if($id != 0)
            $journal = Journal::where('slug', $slug)->where('id', '!=', $id)->first();
        else
            $journal = Journal::where('slug', $slug)->first();

        if($journal != null)
            return false;
        else
            return true;
    }

    public static function myWordsCount($text){

        $text = html_entity_decode($text);
        $text = strip_tags($text);
        $text = trueShowForTextArea($text);

        $arr = explode(' ', $text);
        $words = [];
        $errorWords = [];
        $wordCount = count($arr);

        foreach ($arr as $word) {
            if (array_key_exists($word, $words)) {
                $words[$word] = $words[$word] + 1;
            } else {
                $words[$word] = 1;
            }
        }
        foreach ($words as $word => $count){
            $count = floor($count / $wordCount * 100);
            $words[$word] = $count;
            if($count > 10)
                array_push($errorWords, $word);
        }

        arsort($words);

        return [$words, $errorWords];
    }

    public static function keywordDensityInTitle($text, $keyword){

        $totalCount = 0;
        $keyCount = 0;

        for($i = 1; $i < 6; $i++){
            $header = 'h'.$i;

            $s = 0;
            while(true){
                $st = strpos($text, '<'.$header, $s);

                if(!is_int($st))
                    break;

                $start = strpos($text, '>', $st);
                $end = strpos($text, '</' . $header . '>', $start);
                $len = $end - $start;
                $txt = substr($text, $start+1,  $len);

                $txt = html_entity_decode($txt);
                $txt = strip_tags($txt);
                $txt = trueShowForTextArea($txt);

                $arr = explode(' ', $txt);
                $totalCount++;
//                $totalCount += count($arr);

                if(count(explode(' ', $keyword)) == 1) {
                    $keyInText = 0;
                    foreach ($arr as $item) {
                        if ($item == $keyword)
                            $keyInText++;
                    }
                    $keyCount += $keyInText;
                }

                $keyCount += substr_count($txt, $keyword);

                $s = $end;
            }
        }

        $keywordCount = count(explode(' ', $keyword));

        $zeroTitle = false;
        if($totalCount == 0)
            $zeroTitle = true;

        if(!$zeroTitle) {
            $keyWordDensity = ((int)$keyCount / (int)$totalCount) * 100;
            $keyWordDensity = floor($keyWordDensity * 100) / 100;
        }
        else
            $keyWordDensity = 9999;

        return $keyWordDensity;
    }

    public static function allWordCountInP($text){
        $countWordInP = [0];
        $s = 0;
        $parNumError = 0;
        while(true){
            $st = strpos($text, '<p', $s);

            if(!is_int($st) || $st == -1)
                break;

            $start = strpos($text, '>', $st);
            $end = strpos($text, '</p>', $start);
            $len = $end - $start;
            $p = substr($text, $start+1,  $len);

            $p = html_entity_decode($p);
            $p = strip_tags($p);

            $numWord = count(explode(' ', $p));
            $countWordInP[0] += $numWord;
            array_push($countWordInP, $numWord);

            if($numWord > 150)
                $parNumError = count($countWordInP)-1;
            $s = $end;
        }

        return [$countWordInP, $parNumError];
    }

    public static function getAllTitles($text){
        $titels = array();

        $s = 0;
        while(true){
            $st = strpos($text, '</h', $s);
            if(!is_int($st) || $st == -1)
                break;

            $h = substr($text, $st+2, 2);
            array_push($titels, $h);
            $s = $st + 2;
        }

        $error = false;

        for($i = 0; $i < count($titels); $i++){
            if(($i + 1) < count($titels) && !$error) {
                switch ($titels[$i]){
                    case 'h1':
                        if(!($titels[$i+1] == 'h1' || $titels[$i+1] == 'h2'))
                            $error = true;
                        break;
                    case 'h2':
                        if(!($titels[$i+1] == 'h1' || $titels[$i+1] == 'h2' || $titels[$i+1] == 'h3'))
                            $error = true;
                        break;
                    case 'h3':
                        if(!($titels[$i+1] == 'h1' || $titels[$i+1] == 'h2' || $titels[$i+1] == 'h3' || $titels[$i+1] == 'h4'))
                            $error = true;
                        break;
                }
            }
            else
                break;
        }

        return [$titels, $error];
    }

    public static function sentencesCount($text){
        $overCount = 0;
        $s = 0;
        $totalCount = 0;
        $i = 0;
        while(true){
            $st = strpos($text, '<p', $s);
            if(!is_int($st) || $st == -1)
                break;

            $start = strpos($text, '>', $st);
            $end = strpos($text, '</p>', $start);
            $len = $end - $start;

            $p = substr($text, $start+1,  $len);
            $p = html_entity_decode($p);
            $p = strip_tags($p);

            if($p != null){
                $ss = 0;
                $i++;
                while(true){
                    $stSenc1 = strpos($p, '.', $ss);
                    $stSenc2 = strpos($p, '؟', $ss);
                    $stSenc3 = strpos($p, '!', $ss);

                    if($stSenc1 == false && $stSenc2 == false && $stSenc3 == false)
                        break;

                    if($stSenc1 == false)
                        $stSenc1 = 99999999999;
                    if($stSenc2 == false)
                        $stSenc2 = 99999999999;
                    if($stSenc3 == false)
                        $stSenc3 = 99999999999;

                    if($stSenc2 > $stSenc3)
                        $stSenc2 = $stSenc3;
                    if($stSenc1 > $stSenc2)
                        $stSenc = $stSenc2;
                    else
                        $stSenc = $stSenc1;

                    if(!is_int($stSenc) || $stSenc == -1)
                        break;

                    $len = $stSenc - $ss;
                    $sentences = substr($p, $ss,  $len);
                    $ss = $stSenc + 1;

                    $exp = explode(' ', $sentences);
                    if(count($exp) > 20)
                        $overCount++;

                    $totalCount++;
                }
            }
            $s = $end;
        }

        if($totalCount > 0)
            $percent = ($overCount / $totalCount) * 100;
        else
            $percent = 0;

        $percent = floor($percent * 100)/100;

        return $percent;
    }

    public static function imgInText($text){
        $imgCount = 0;
        $haveAlt = 0;
        $s = 0;
        while(true){
            $st = strpos($text, '<img', $s);
            if(!is_int($st) || $st == -1)
                break;

            $end = strpos($text, '>', $st);
            $len = $end - $st;
            $img = substr($text, $st,  $len);

            $alt1=  strpos($img, 'alt=""');
            $alt2 = strpos($img, 'alt="');

            if($alt1 === false && $alt2 !== false )
                $haveAlt++;

            $imgCount++;

            $s = $end;
        }

        return [$imgCount, $haveAlt];
    }

}
