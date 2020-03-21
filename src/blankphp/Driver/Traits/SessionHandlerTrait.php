<?php


namespace BlankPhp\Driver\Traits;


trait SessionHandlerTrait
{
    protected $gc = 35000;

    public function close()
    {
        return true;
    }

    public function destroy($session_id)
    {
        return $this->delete($session_id);
    }

    public function gc($maxlifetime)
    {
        return $this->clearExpireData();
    }

    public function open($save_path, $name)
    {
        return true;
    }

    public function read($session_id)
    {
        return $this->valueParse($this->get($session_id));
    }

    public function write($session_id, $session_data)
    {
        $this->set($session_id, $this->parseValue($session_data), $this->gc);
    }
}