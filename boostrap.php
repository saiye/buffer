<?php
/**
 * Created by 2020/3/20 0020 18:42
 * User: yuansai chen
 */



$fileArr=[
    __DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php',
    __DIR__.DIRECTORY_SEPARATOR.'App'.DIRECTORY_SEPARATOR.'functions.php',
    __DIR__.DIRECTORY_SEPARATOR.'App'.DIRECTORY_SEPARATOR.'App.php',
];
foreach($fileArr as $file){
    include_once $file;
}










