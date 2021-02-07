<?php


namespace BlankPhp\Exception;


use BlankPhp\Application;

class Error
{
    protected static $handler;
    protected $app;

    public function register(Application $app): void
    {
        $this->app = $app;
        error_reporting(E_ALL);
        register_shutdown_function([__CLASS__, 'shutdown']);
        set_error_handler([__CLASS__, 'error']);
        set_exception_handler([__CLASS__, 'exception']);
    }

    public static function exception($e): void
    {
        $handler = self::getHandler();
        $sapi_type = php_sapi_name();
        if (strpos($sapi_type, 'cli') === 0) {
            $handler->handToConsole($e);
        } else {
            $handler->handToRender($e);
        }
    }

    public static function error(): void
    {
    }

    public static function shutdown(): void
    {
        $hah = error_get_last();
        var_dump($hah);
    }

    public static function getHandler()
    {
        if (empty(self::$handler)) {
            $class = config('app.exception_handler');
            self::$handler = new $class();
        }
        return self::$handler;
    }


}