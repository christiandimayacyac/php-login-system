<?php
    // define the __CONFIG__ to allow the config.php file
    define('__CONFIG__', true);
    require_once "inc/config.php";

    if ( isset($_SESSION['user_id']) ){
        unset($_SESSION['user_id']);
    }


    session_destroy();
    session_regenerate_id(true);
    header("location:login.php");
?>