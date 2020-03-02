<?php


namespace Blankphp\Session\Driver;

use Helpers\File as FileHelper;

class File implements \SessionHandlerInterface
{
    private $config = [
        "prefix" => "session_",
        "dir" => '',
        'suffix' => '',
    ];
    protected $session_id;

    public function __construct($config = [])
    {
        $this->config = array_merge($this->config, $config);

    }

    public function getSessionId($session_id)
    {
        return $this->config["prefix"] . $session_id;
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
        FileHelper::delete($session_id);
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
        //便利所有文件,删除过期session
    }


    /**
     * @param string $session_id
     * @return string|void
     * Read session data
     */
    public function read($session_id)
    {
        return $this->valueParse(FileHelper::get($this->getSessionId($session_id)));
    }

    /**
     * @param string $session_id
     * @param string $session_data
     * @return bool|void
     *  Write session data
     */
    public function write($session_id, $session_data)
    {
        return $this->parseValue(FileHelper::put($this->getSessionId($session_id), $session_data));
    }

    public function parseValue($data = [])
    {

    }


    public function valueParse($data = [])
    {

    }

}