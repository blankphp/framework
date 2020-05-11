<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/11
 * Time: 13:14
 */

namespace BlankPhp\Request;

use BlankPhp\Contract\Request as RequestContract;
use BlankPhp\Facade\Cache;
use BlankPhp\Facade\Cookie;
use BlankPhp\Facade\Session;

class Request implements RequestContract
{
    /**
     * @var string
     */
    public $uri;
    /**
     * @var string
     */
    public $method;
    /**
     * @var string[]
     */
    protected $request = [
        'get' => '',
        'post' => '',
        'files' => '',
        'input' => '',
    ];
    /**
     * @var \BlankPhp\Session\Session
     */
    public $session;
    /**
     * @var \BlankPhp\Cookie\Cookie
     */
    public $cookie;
    /**
     * @var array
     */
    public $input = [];
    /**
     * @var string
     */
    public $userIp;
    /**
     * @var string
     */
    public $serverIp;

    public $http;

    public $https;
    /**
     * @var string
     */
    public $userAgent;
    /**
     * @var string
     */
    public $language;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->getUri();
        $this->getMethod();
        $this->getRequest();
        $this->getFromServer();
        $this->getOther();
    }

    public function getOther(): void
    {
        $this->cookie = Cache::getFromApp();
        $this->session = Session::getFromApp();
    }

    public function getFromServer(): void
    {
        $this->getUserAgent();
        $this->getLanguage();
        $this->getServicePort();
    }

    /***
     * @param $value
     * @return array|string
     */
    public function stripSlashesDeep($value)
    {
        $value = is_array($value) ? array_map([$this, 'stripSlashesDeep'], $value) : stripslashes($value);
        return $value;
    }

    /**
     * @param $name
     * @param string $default
     * @return string
     */
    public function get($name, $default = ''): string
    {
        $this->{'_' . strtolower($this->method)}();
        if (isset($this->request[strtolower($this->method)][$name])) {
            return $this->request[strtolower($this->method)][$name];
        }

        $this->getRequest();
        foreach ($this->request as $item) {
            if (isset($item[$name])) {
                return $item[$name];
            }
        }
        return $default;
    }

    /**
     * @return $this
     */
    public function capture(): Request
    {
        return $this;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        if ($this->uri === null) {
            $url = $_SERVER['REQUEST_URI'];
            $position = strpos($url, '?');
            $url = $position === false ? $url : substr($url, 0, $position);
            $url = ltrim($url, '/');
            $urlArray = explode('/', $url);
            $urlArray = array_filter($urlArray);
            $file = explode('/', str_replace(DS, '/', PUBLIC_PATH . 'index.php'));
            $urlArray = array_diff($urlArray, $file);
            if ($urlArray) {
                $this->uri = '/' . implode('/', $urlArray);
            } else {
                $this->uri = '/';
            }
        }
        return $this->uri;

    }

    /***
     * @return mixed|string
     */
    public function getMethod()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method === 'POST') {
            $method = isset($this->request['post']['_method']) ? strtoupper($this->request['post']['_method']) : 'POST';
        }
        $this->method = $method;
        return $this->method;
    }

    /**
     * @param string $name
     * @return string| mixed
     */
    public function file($name = '')
    {
        if (empty($this->request['files']) && !empty($_FILES)) {
            $this->request['files'] = $_FILES;
            unset($_FILES);
        }
        return $this->request['files'][$name] ?? '';
    }

    public function __get($name): ?string
    {
        if (!isset($this->$name)) {
            return $this->get($name);
        }
        return $this->$name;
    }

    private function getRequest(): void
    {
        $this->_get();
        $this->_post();
        $this->_input();
        $this->file();
        $this->_cookie();
    }


    public function getUserAgent()
    {
        if (empty($this->userAgent))
            $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
        return $this->userAgent;
    }

    public function userIp()
    {
        if (empty($this->userIp)) {
            $this->userIp = $_SERVER['REMOTE_ADDR'];
        }
        return $this->userIp;
    }

    public function getLanguage()
    {
        if (empty($this->language)) {
            $this->language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        }
        return $this->language;
    }

    public function getHttp(): void
    {

    }

    public function getServicePort(): void
    {

    }

    private function _cookie($name = '', array $optionm = [])
    {
        return $this->cookie = Cookie::get($name);
    }


    private function _get($name = '', array $options = []): string
    {
        if (empty($this->request['get'])) {
            //是否进行过滤？递归的效率很成问题
            $this->request['get'] = $_GET !== null ? $this->stripSlashesDeep($_GET) : '';
            unset($_GET);
        }
        return $this->request['get'][$name] ?? '';
    }

    private function _post($name = '', array $options = []): string
    {
        if (empty($this->request['post'])) {
            $this->request['post'] = $_POST !== null ? $this->stripSlashesDeep($_POST) : '';
            unset($_POST);
        }
        return $this->request['post'][$name] ?? '';
    }

    private function _input($name = '', array $args = [])
    {
        if (empty($this->request['input'])) {
            $this->input = file_get_contents('php://input');
            if (strpos($this->input, '{') !== false) {
                $this->request['input'] = json_decode($this->input, true);
            } else {
                parse_str($this->input, $this->request['input']);
            }
        }
        if (empty($name)) {
            return $this->input;
        }
        return $this->request['input'][$name] ?? '';
    }


    public function __toArray(): array
    {
        return [
            'uri' => $this->uri,
            'method' => $this->method,
            'request' => $this->request,
            'session' => $this->session,
            'input' => $this->input,
            'userIp' => $this->userIp,
            'serverIp' => $this->serverIp,
            'http' => $this->http,
            'https' => $this->https,
            'userAgent' => $this->userAgent,
            'language' => $this->language,
        ];
    }

}