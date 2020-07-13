<?php


namespace BlankPhp\Log\Driver;


use BlankPhp\Exception\Exception;
use BlankPhp\Log\Log;
use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;

class Daily extends Log
{
    public function __construct($config)
    {
        parent::__construct('daily', $config['handlers'], $config['process'] ?? []);
        $this->pushHandler(new StreamHandler($config['path'], $config['level']));
    }

    public function setFormatter($formatter)
    {
        return $this->getHandlers()[0]->setFormatter($formatter);
    }
}