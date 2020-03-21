<?php


namespace Blankphp\Console\Publish;


use Blankphp\Console\Console;
use Blankphp\Exception\Exception;
use BlankQwq\Helpers\Str;

class PublishConsole extends Console
{

    public function vendor($name = "", $other = "")
    {
        //发布某个包
        if (empty($name)) {
            return "[name] is empty!!";
        }
        if (!class_exists($name)) {
            $name = Str::makeClassName($name, "\BlankPhp\Provider\\");
        }
        $provider = $this->app->make($name);
        $result = $provider->publish($other);
        return $result ? "Publish [{$name}] is ok" : "Publish [{$name}] error";
    }

}