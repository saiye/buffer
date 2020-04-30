<?php
/**
 * Created by 2020/3/20 0020 18:46
 * User: yuansai chen
 */


class App
{

    public function run()
    {
       /* spl_autoload_register(function ($class) {
            $file = dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
            if (is_file($file)) {
                include_once $file;
            }
        },true,true);*/
        //Receive command line arguments
        $arg = getopt('c:');
        if (isset($arg['c'])) {
                $class = 'App' .str_replace('/','\\',$arg['c']);
                (new  $class())->run();
        } else {
            die('Please enter parameters! For example run:    php index.php   -c Process/Test' . PHP_EOL);
        }
    }
}