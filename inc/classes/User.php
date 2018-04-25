<?php
    // if __CONFIG__ is not defined, do not load this file
    if( !defined('__CONFIG__') ) {
        exit('Config File is not defined.');
    }

    class User {

        private $con;

        public $user_email;
        public $user_password;
        public $user_reg_time;

        public function __construct(int $user_id) {
            $this->con = DB::getConnection();

            $sql_query = "SELECT * FROM users WHERE user_id = :user_id LIMIT 1";
            $stmt = $this->con->prepare($sql_query);
            $stmt->execute(array(":user_id"=>$user_id));

            if ( $rs = $stmt->fetch(PDO::FETCH_OBJ) ) {
                $this->user_email = $rs->email;
                $this->user_password = $rs->password;
                $this->user_reg_time = $rs->reg_time;
            }
            else {
                //user does not exist, redirect to logout.php
                header("location:logout.php");
                exit;
            }

            
        }
        
        public static function Find($email, $return_assoc = false) {
            $con = DB::getConnection();

            //Query the user record matching the users input email
            // try{
                $sql_Query = "SELECT * FROM users WHERE email = :email LIMIT 1";
                $stmt = $con->prepare($sql_Query);
                $stmt->execute(array(':email'=>$email));
    
                //checks if callee needs records or just checks record existence
                if ( $return_assoc ) {
                    return $stmt->fetch(PDO::FETCH_ASSOC);
                }
                else{
                    $user_found = (boolean) $stmt->rowCount();
                    return $user_found;
                }
            // }
            // catch(PDOException $ex){
            //     $responseArray['error'] = $ex->getErrorMessage();
            //     echo json_encode($responseArray, JSON_PRETTY_PRINT);
            //     exit;
            // }

        }

        public static function Insert($email, $hashed_password) {
            $con = DB::getConnection();

            try{
                $sqlUpdate = "INSERT INTO users(email, password) VALUES (LOWER(:email), :hashed_password)";
                 $stmt = $con->prepare($sqlUpdate);
                 $stmt->execute(array(":email"=>$email, ":hashed_password"=>$hashed_password));

                 if ( $stmt->rowCount() == 1 ) {
                     //retrieve the last UserId Appended
                     $user_id = $con->lastInsertId();

                     //Create a UserId Session
                     $_SESSION['user_id'] = (int) $user_id;
                     
                     //Add a flag for is_logged_in status
                     $insert_info['is_logged_in'] = true;

                     //set a redirect URL
                     $insert_info['redirect'] = "dashboard.php";               
                 }
                 else {
                     $insert_info['error'] = Constant::db_insert_error;
                 }
             }
             catch(PDOException $ex){
                 $insert_info['error'] = $ex->getErrorMessage();
             }

             //return Insert Info values
             return $insert_info;
        }

    }
?>