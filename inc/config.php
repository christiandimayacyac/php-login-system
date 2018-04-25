<?php
    // if __CONFIG__ is not defined, do not load this file
    if( !defined('__CONFIG__') ) {
        exit('Config File is not defined.');
    }

    //Start Session if not yet started
    if ( !isset($_SESSION) ) {
        session_start();
    }


    //Allow Error Reporting
    //Use only during dev
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');

    //Include the DB.php file for DB connection
    include_once "classes/DB.php";

    //Create New DB Connection
    $con = DB::getConnection();

?>