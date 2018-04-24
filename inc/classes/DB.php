<?php

// if __CONFIG__ is not defined, do not load this file
if( !defined('__CONFIG__') ) {
    exit('Config File is not defined.');
}

class DB {
    protected static $con;
    protected static $dsn = "mysql:charset=utf8; host=localhost; dbname=phploginsystem";
    protected static $dbusername = "ziemdiadmin";
    protected static $dbpassword = "ziemdiadmin";

    private function __construct() {

        try {
            self::$con = new PDO(self::$dsn, self::$dbusername, self::$dbpassword);
            self::$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$con->setAttribute(PDO::ATTR_PERSISTENT, false);
        }
        catch(PDOException $ex) {
            echo "Unable to connect to the database." . $ex->getMessage();
            exit;
        }
    }

    public static function getConnection() {

        //If this instance has not been started, start it
        if (!self::$con) {
            new DB();
        }

        //Return the writeable db connection
        return self::$con;

    }
}
?>