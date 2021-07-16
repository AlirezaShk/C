<?php
require_once "config.lib.php";


class DB
{
    private static $host = DB_HOST;
    private static $dbName = DB_NAME;
    private static $user = DB_USER;
    private static $pwd = DB_PASS;

    public static function connect()
    {
        $dsn = "mysql:host=" . self::$host . ';dbname=' . self::$dbName;
        try {
            $pdo = new PDO($dsn, self::$user, self::$pwd);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $pdo->exec("SET NAMES 'utf8';");
            return $pdo;
        } catch (PDOException $e) {
            echo $e->getMessage() . "<br>" . $e->getFile()  . "<br>" .  $e->getLine();
            exit();
        }
        return FALSE;
    }
    public static function disConnect(PDO &$db)
    {
        $db->query('KILL CONNECTION_ID()');
        $db = NULL;
    }
}