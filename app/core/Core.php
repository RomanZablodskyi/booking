<?php

function __autoload($class) {
    $fileName = strtolower($class) . '.php';
    $expArr = explode('_', $class);
    if(empty($expArr[1])){
        $fileFolder = 'models';
    }else{
        switch($fileName){
            case 'controller':
                $fileFolder = 'controllers';
                break;

            case 'model':
                $fileFolder = 'models';
                break;
        }
    }

    $filePath = 'app/' . $fileFolder . '/' . $fileName;

    if(!file_exists($filePath)) {
        return false;
    }else{
        include_once ($filePath);
        return true;
    }
}