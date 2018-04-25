<?php
    // if __CONFIG__ is not defined, do not load this file
    if( !defined('__CONFIG__') ) {
        exit('Config File is not defined.');
    }

    class Page {

        public static function ForceLogin(){
            // Forces user to logout and get redirected login page if session is does not exist
            if ( !isset($_SESSION['user_id']) ) {
                header("location:logout.php");
                exit;
            }
        }

        public static function ForceDashboard() {
            // Redirect user to Dashboard if already signed in preventing to open login and register page
            if ( isset($_SESSION['user_id']) ) {
                header("location:dashboard.php");
                exit;
            }
        }
    }
?>