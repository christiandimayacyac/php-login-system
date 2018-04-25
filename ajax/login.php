<?php
    // define the __CONFIG__ to allow the config.php file
    define('__CONFIG__', true);

    // require include files and classes
    require_once "../inc/config.php";
    require_once "../inc/classes/Constant.php";
    require_once "../inc/classes/Filter.php";

    //Check if the POST came from an AJAX call
    if ( isset($_SERVER["HTTP_X_REQUESTED_WITH"]) ) {
        // echo "called from an AJAX";

        //always return in JSON format
        header('Content-Type: application/json');

        $response_array = [];

        //collect data from POST Request
        $email = Filter::String($_POST['email']);
        $email = strtolower($email);
        $password = $_POST['password'];

        //CODE TO GET USER INFO
        $user_found = User::Find($email, true);

        //If user found, verify password then set redirect URL then return RESPONSE, otherwise return an ERROR RESPONSE
        if ( $user_found ) {
            //retrieve the hashed password from the query
            $hashed_password = (string) $user_found['password'];

            //verify user password if the user email exists in the database
            if ( password_verify($password, $hashed_password) == true ) {
                //set a redirect URL
                $response_array['redirect'] = "dashboard.php";

                //create session if password matches
                $_SESSION['user_id'] = (int) $user_found['user_id'];
            }
            else {
                //password does not match, set an error message
                $response_array['password_mismatched'] = Constant::$password_err;
            }
        }
        else{
            //user does not exist, set an error message
            $response_array['user_not_exists'] = Constant::$user_not_exists;
        }

        //return AJAX response
        echo json_encode($response_array, JSON_PRETTY_PRINT);
        exit;
    }
    else {
        //Die and exit
        echo "Invalid POST Request&#33;";
        exit;
    }

?>