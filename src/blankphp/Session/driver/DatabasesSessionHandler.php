<?php


namespace Blankphp\Session\Driver;


class DatabasesSessionHandler implements \SessionHandlerInterface
{

    /**
     * @return bool|void
     * Close the session
     */
    public function close(){
    }

    /**
     * @param string $session_id
     * @return bool|void
     * Destroy a session
     */
    public function destroy($session_id){

    }

    /**
     * @param int $maxlifetime
     * @return bool|void
     * Cleanup old sessions
     */
    public function gc($maxlifetime){

    }

    /**
     * @param string $save_path
     * @param string $name
     * @return bool|void
     * Initialize session
     */
    public function open($save_path, $name){

    }


    /**
     * @param string $session_id
     * @return string|void
     * Read session data
     */
    public function read($session_id){

    }

    /**
     * @param string $session_id
     * @param string $session_data
     * @return bool|void
     *  Write session data
     */
    public function write($session_id, $session_data){

    }

}