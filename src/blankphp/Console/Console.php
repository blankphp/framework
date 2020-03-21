<?php


namespace BlankPhp\Console;


class Console
{
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function printLn($message){
        echo $message.PHP_EOL;
        return true;
    }



}