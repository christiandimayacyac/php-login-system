<?php
    // define the __CONFIG__ to allow the config.php file
    define('__CONFIG__', true);

    require_once "../inc/config.php";
    require_once "../inc/classes/Constant.php";
    require_once "../inc/classes/Filter.php";

    //Check if the POST came from an AJAX call
    if ( isset($_SERVER["HTTP_X_REQUESTED_WITH"]) ) {
        // echo "called from an AJAX";

        //always return in JSON format
        header('Content-Type: application/json');

        $responseArray = [];

        //retrieve email from the data sent through AJAX
        $email = Filter::String($_POST['email']);
        $password = $_POST['password'];

        $user_found = User::Find($email, false);

        //verify if user email does not exists in the database
        if ( $user_found ) {
            //return an error message
            $responseArray['error'] = Constant::$email_exists_err;
            $responseArray['is_logged_in'] = false;
        }
        else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $add_user_process_info = User::Insert($email, $hashedPassword);

        }
        //return data
        echo json_encode($add_user_process_info, JSON_PRETTY_PRINT);
    }
    else{
        //Die and exit.
        exit("Invalid POST Request!");
    }

?>