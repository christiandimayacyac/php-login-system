<?php
    // define the __CONFIG__ to allow the config.php file
    define('__CONFIG__', true);
    require_once "inc/config.php";

    Page::ForceLogin();

    header("location:dashboard.php");
    
?>
