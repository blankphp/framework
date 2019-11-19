<?php


namespace Blankphp\Database;


class DbConnect
{
    private static $pdo;

    public static function pdo(array $db = [])
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        try {
            $dsn = sprintf('%s:host=%s;dbname=%s;charset=%s',
                $db['driver'], $db['host'], $db['database'], $db['charset']);
            $option = array(\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC);
            self::$pdo = new \PDO($dsn, $db['username'], $db['password'], $option);
            self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(
                \PDO::ATTR_EMULATE_PREPARES,
                false
            );
            if (empty($dbEngine)) {
                $dbEngine = (string) self::$pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);
            }

            return self::$pdo;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public static function getPdo($array = [])
    {
        if (empty($array))
            return self::$pdo;
        else
            return self::pdo($array);
    }

    public function setConfig()
    {

    }

}