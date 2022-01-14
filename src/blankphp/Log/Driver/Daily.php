<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Log\Driver;

use BlankPhp\Log\Log;
use Monolog\Handler\StreamHandler;

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
