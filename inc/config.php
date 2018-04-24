<?php
    // if __CONFIG__ is not defined, do not load this file
    if( !defined('__CONFIG__') ) {
        exit('Config File is not defined.');
    }

    //Include the DB.php file for DB connection
    include_once "classes/DB.php";

    //Create New DB Connection
    $con = DB::getConnection();

?>