<?php


namespace BlankPhp\Database;


use BlankPhp\Database\Exception\ConnectErrException;

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
            $dbEngine = '';
            $option = array(\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC);
            self::$pdo = new \PDO($dsn, $db['username'], $db['password'], $option);
            self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(
                \PDO::ATTR_EMULATE_PREPARES,
                false
            );
            if (empty($dbEngine)) {
                $dbEngine = (string)self::$pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);
            }

            return self::$pdo;
        } catch (\PDOException $e) {
            throw new ConnectErrException($e->getMessage());
        }
    }

    public static function getPdo($array = []): \PDO
    {
        if (empty($array)) {
            return self::$pdo;
        }
        return self::pdo($array);
    }


}