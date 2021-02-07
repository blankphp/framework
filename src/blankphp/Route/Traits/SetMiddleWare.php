<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/18
 * Time: 19:19
 */

namespace BlankPhp\Route\Traits;


trait SetMiddleWare
{
    protected $tempMiddleware;
    protected $currentController;
    public $middleware;

    /**
     * @return string| mixed
     */
    public function getMiddleWare()
    {
        return $this->middleware['alice'] ?? '';
    }

    /**
     * @return string| mixed
     */
    public function getGroupMiddleware()
    {
        return $this->middleware['group'] ?? '';
    }

    /**
     * @param $middleware
     */
    public function setMiddleWare($middleware): void
    {
        $this->middleware = $middleware;
    }


    public function getOneMiddleWare($array)
    {
        //引用中间件别名[然后获取].
        if (isset($array['middleware'])) {
            return $array['middleware'];
        }

        return [];
    }

    public function setOneMiddleWare($uri, $method): void
    {
        if (!empty($this->tempMiddleware)) {
            $this->route[$uri][$method]['middleware']['alice'] = [];
            $this->route[$uri][$method]['middleware']['alice'][] = $this->tempMiddleware;
        }
    }

    public function setCurrentController($uri, $method): void
    {
        $this->currentController = array($uri, $method);
    }

    public function getCurrentController()
    {
        return $this->currentController;
    }

    public function init(): void
    {
        $this->currentController = '';
    }
}