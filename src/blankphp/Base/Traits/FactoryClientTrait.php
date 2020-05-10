<?php


namespace BlankPhp\Base\Traits;


use BlankPhp\Driver\DriverFactory;
use BlankPhp\Facade\Driver;

trait FactoryClientTrait
{
    /**
     * @var DriverFactory
     */
    private $factory;

    private function createFromFactory(string $name, string $tag = 'default')
    {
        return $this->getFactory()->factory($name, $tag);
    }

    private function getFactory()
    {
        if (empty($this->factory)) {
            $this->factory = Driver::getFromApp();
        }
        return $this->factory;
    }
}