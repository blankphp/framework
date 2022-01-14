<?php

namespace Container;
use BlankPhp\Application;
use BlankPhp\Exception\ParameterLoopException;
use PHPUnit\Framework\TestCase;

class A{
    public $res;
    public function __construct(D $d,int $c,$e,bool $f,array $g)
    {
        $this->res =  "A".$c.$e.$f.json_encode($g);
    }
}

class B{
    public function __construct(C $c)
    {
        echo "B".PHP_EOL;
    }
}
class C{
    public function __construct(B $a)
    {
        echo "C".PHP_EOL;
    }
}

class D{
    public function __construct($d)
    {
        echo "ehco".$d;
    }
}

class ApplicationTest extends TestCase
{
    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testContainer(){
        $app = Application::getInstance();
        $a = $app->build(A::class);
        $this->assertEquals($a->res,'A0[]');
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testLoop(){
        $this->expectException(ParameterLoopException::class);
        $app = Application::getInstance();
        $app->build(B::class);
    }

    public function testNull(){
        $app = Application::getInstance();
        $app->build(D::class);
        $this->assertEmpty('','');
    }

}