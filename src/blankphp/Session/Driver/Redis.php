<?php


namespace Blankphp\Session\Driver;


use Blankphp\Application;

class Redis implements \SessionHandlerInterface
{

    private $redis = null;
    protected $expire = 35000;

    public function __construct($config = [])
    {
        $this->expire = $config['expire'];
        $this->redis = Application::getInstance()->make('redis', [config("db.redis.{$config["connect"]}")]);
    }

    /**
     * @return bool|void
     * Close the session
     */
    public function close()
    {
        return true;
    }

    /**
     * @param string $session_id
     * @return bool|void
     * Destroy a session
     */
    public function destroy($session_id)
    {
        return $this->redis->delete($session_id);
    }

    /**
     * @param int $maxlifetime
     * @return bool|void
     * Cleanup old sessions
     */
    public function gc($maxlifetime)
    {
        return true;
    }

    /**
     * @param string $save_path
     * @param string $name
     * @return bool|void
     * Initialize session
     */
    public function open($save_path, $name)
    {
        return true;
    }


    /**
     * @param string $session_id
     * @return string|void
     * Read session data
     */
    public function read($session_id)
    {
        return $this->redis->get($session_id, "");
    }

    /**
     * @param string $session_id
     * @param string $session_data
     * @return bool|void
     *  Write session data
     */
    public function write($session_id, $session_data)
    {
        return $this->redis->set($session_id, $session_data, $this->expire);
    }

    public function __destruct()
    {

    }

}