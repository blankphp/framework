<?php
if (!function_exists('app')) {
    function app($abstract)
    {
        $a = \BlankPhp\Application::getInstance();
        if ($a->has($abstract))
            return $a->make($abstract);
        else
            return $a->getSignal($abstract);
    }
}

if (!function_exists('config')) {
    function config($name = "", $default = null)
    {
        if (empty($name)) {
            return app('config')->all();
        }
        $descNames = explode('.', $name);
        $descNames = array_filter($descNames);
        return app('config')->get($descNames, $default);
    }
}


if (!function_exists('view')) {
    function view($view = null, $data = [], $makeFileName = true)
    {
        $factory = app(\BlankPhp\Contract\View::class);
        if (func_num_args() === 0) {
            return $factory;
        }
        return $factory->view($view, $data, $makeFileName);
    }
}

if (!function_exists('view_static')) {
    function view_static($view = null, $data = [], $time = 30000)
    {
        $factory = app('view.static');
        if (func_num_args() === 0) {
            return $factory;
        }
        return $factory->view($view, $data, $time);
    }
}


if (!function_exists('real_path')) {
    function real_path($path, $ff = null)
    {
        $static = config('app.static');
        $url = APP_PATH . '/' . $static . '/' . $path;
        if ($ff)
            $url = str_replace("\\", "/", $url);
        return $url;
    }
}

if (!function_exists('response')) {
    function response($a = null)
    {
        return \BlankPhp\Application::getInstance()->make('response');
    }
}
if (!function_exists('now')) {
    function now()
    {
        return date('Y-m-d H:i:s');
    }
}

if (!function_exists('dump')) {
    function dump($vars, $label = 'array', $return = false)
    {
        if (ini_get('html_errors')) {
            $content = "<div ><pre>\n";
            if ($label !== '') {
                $content .= "<strong>{$label} :</strong>\n";
            }
            $content .= htmlspecialchars(print_r($vars, true));
            $content .= "\n</pre>\n</div>";
        } else {
            $content = $label . " :\n" . print_r($vars, true);
        }
        if ($return) {
            return $content;
        }
        echo $content;
        return null;
    }
}