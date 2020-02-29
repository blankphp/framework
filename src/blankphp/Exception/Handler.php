<?php


namespace Blankphp\Exception;


use Blankphp\Facade\Log;

class Handler
{

    public function handToConsole( $e){

    }

    public function handToRender( $e){
        if ($e instanceof Exception)
             $e->render();
        else{
            $e = new HttpException($e->getMessage(),500);
            $e->render();
        }
        Log::error('error_code: '.$e->getCode()." error_message： ".$e->getMessage(),$e->getTrace());
    }
}