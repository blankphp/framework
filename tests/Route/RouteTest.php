<?php

namespace Route;

use BlankPhp\Contract\Kernel;
use BlankPhp\Response\Response;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    private $text="<style type=\"text/css\">
                *{ padding: 0; margin: 0;text-align: center }
                 body{
                  font-family: Helvetica, \"Microsoft YaHei\", Arial, sans-serif;
                    font-size: 14px;
                 color: #333}
                  h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; }
                  p{ line-height: 2em; font-size: 45px }</style>
                  <div style=\"padding: 50px;\">
                <h1>🚀</h1><p> BlankPhp V1<br/><span style=\"font-size:30px\">每日练习，刻意精进 <br>
                为phpWeb开发设计的高性能框架</span>
                </p><span style=\"font-size:22px;\">
                </span></div></script>
                <blankPhp id=\"dadad12596\"></blankPhp>";

    public function createApplication()
    {
        define('APP_PATH', __DIR__ . '/../');
        $app = \BlankPhp\Application::init();
        $kernel = $app->signal(\BlankPhp\Contract\Kernel::class,\BlankPhp\Kernel\HttpKernel::class);
        \BlankPhp\Facade\Route::get('/',function (){
            return $this->text;
        })->middleware('test');
        \BlankPhp\Facade\Route::get('/2',function (){
            return $this->text;
        })->middleware('test');
        $this->app = $app;
        return $kernel;
    }

    public function testBasicTest()
    {
        $response = $this->get('/');
        $this->assertEquals($this->text, $response);
    }

    public function get($uri)
    {
        return $this->call('GET', $uri, [], [], []);
    }


    public function call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        /** @var Kernel $kernel */
        $kernel = $this->createApplication();
        $server['REQUEST_METHOD'] = 'GET';
        $server['REQUEST_URI'] = '/';
        /** @var Response $response */
        $response = $kernel->handle(
            \BlankPhp\Request\TestRequest::create($method, $uri, $parameters, $cookies,
                $files, $server, $content
            )
        );
        return $response->returnSend();
    }

    public function testOk(){
        $this->assertStringStartsWith('1','111');
    }
}