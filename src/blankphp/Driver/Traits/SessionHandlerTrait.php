<?php


namespace BlankPhp\Driver\Traits;


trait SessionHandlerTrait
{
    protected $gc = 35000;

    public function close(): bool
    {
        return true;
    }

    public function destroy($session_id)
    {
        return $this->delete($session_id);
    }

    public function gc($maxLifeTime): bool
    {
        return $this->clearExpireData($maxLifeTime);
    }

    public function open($save_path, $name): bool
    {
        return true;
    }

    public function read($session_id)
    {
        return $this->valueParse($this->get($session_id));
    }

    public function write($session_id, $session_data)
    {
        return $this->set($session_id, $this->parseValue($session_data), $this->gc);
    }

}