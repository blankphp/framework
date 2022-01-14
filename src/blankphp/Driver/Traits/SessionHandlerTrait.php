<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Driver\Traits;

trait SessionHandlerTrait
{
    protected $gc = 35000;

    public function close(): bool
    {
        return true;
    }

    public function destroy($session_id):bool
    {
        return $this->delete($session_id);
    }

    public function gc($maxLifeTime): int|false
    {
        return $this->clearExpireData($maxLifeTime);
    }

    public function open($save_path, $name): bool
    {
        return true;
    }

    public function read($session_id):string|false
    {
        return $this->valueParse($this->get($session_id));
    }

    public function write($session_id, $session_data):bool
    {
        return $this->set($session_id, $this->parseValue($session_data), $this->gc);
    }
}
