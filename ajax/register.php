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

        $arrayData = [];

        //retrieve email from the data sent through AJAX
        $email = Filter::String($_POST['email']);
        $password = $_POST['password'];

        //verify if user email does not exists in the database
        // $sqlQuery = "SELECT user_id FROM users WHERE email = LOWER(:email) LIMIT 1";
        try {
            // $sqlQuery = "SELECT user_id FROM users WHERE email = :email LIMIT 1";
            $sqlQuery = "SELECT user_id FROM users WHERE email = LOWER(:email) LIMIT 1";
            $stmt = $con->prepare($sqlQuery);
            $stmt->execute(array(":email"=>$email));

            if ( $stmt->rowCount() == 1 ) {
                //return an error message
                $arrayData['error'] = Constant::$email_exists_err;
                $arrayData['is_logged_in'] = false;
            }
            else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                try{
                   $sqlUpdate = "INSERT INTO users(email, password) VALUES (LOWER(:email), :hashedPassword)";
                    $stmt = $con->prepare($sqlUpdate);
                    $stmt->execute(array(":email"=>$email, ":hashedPassword"=>$hashedPassword));

                    //retrieve the last UserId Appended
                    $user_id = $con->lastInsertId();

                    //Create a UserId Session
                    $_SESSION['user_id'] = (int) $user_id;
                    
                    //Add a flag for is_logged_in status
                    $arrayData['is_logged_in'] = true;

                    //set a redirect URL
                    $arrayData['redirect'] = "dashboard.php";
                }
                catch(PDOException $ex){
                    $arrayData['error'] = $ex->getErrorMessage();
                }
            }
        }
        catch(PDOException $ex) {
            $arrayData['error'] = $ex->getErrorMessage();
            
        }

        //return data
        echo json_encode($arrayData, JSON_PRETTY_PRINT);
        exit;
        
    }
    else{
        //Die and exit.
        exit("Invalid POST Request!");
    }

?>