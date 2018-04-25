<?php

    // Forces user to logout and get redirected login page if session is does not exist
    function ForceLogin() {
        if ( !isset($_SESSION['user_id']) ) {
            header("location:logout.php");
            exit;
        }
    }

    // Redirect user to Dashboard if already signed in preventing to open login and register page
    function ForceDashboard() {
        if ( isset($_SESSION['user_id']) ) {
            header("location:dashboard.php");
            exit;
        }
    }

?>