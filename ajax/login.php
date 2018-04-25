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

        $responseArray = [];

        //collect data from POST Request
        $email = Filter::String($_POST['email']);
        $email = strtolower($email);
        $password = $_POST['password'];

        //Query the user record matching the users input email
        try{
           $sql_Query = "SELECT * FROM users WHERE email = :email LIMIT 1";
           $stmt = $con->prepare($sql_Query);
           $stmt->execute(array(':email'=>$email));

           if ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
                $hashedPassword = $row['password'];
           }
           else {
                $responseArray['user_not_exists'] = Constant::$user_not_exists;
                echo json_encode($responseArray, JSON_PRETTY_PRINT);
                exit;
           }
        }
        catch(PDOException $ex){
            $responseArray['error'] = $ex->getErrorMessage();
            echo json_encode($responseArray, JSON_PRETTY_PRINT);
            exit;
        }

        //verify user password if the user email exists in the database
        if ( password_verify($password, $hashedPassword) == true ) {
            // $responseArray['password_matched'] = true;
            // $responseArray['redirect'] = "dashboard.php";
            $responseArray['redirect'] = "dashboard.php";
            $_SESSION['user_id'] = (int) $row['user_id'];
        }
        else {
            $responseArray['password_mismatched'] = Constant::$password_err;
        }

        echo json_encode($responseArray, JSON_PRETTY_PRINT);
        exit;
    }
    else {
        //Die and exit
        echo "Invalid POST Request";
        exit;
    }

?>