<?php 
	class Account {
			
		private $errorsArray;
		private $db;

		public function __construct($db) {
			$this->db = $db;
			$this->errorsArray = array();
		}
		
		public function loginUser($email, $password) {
			try{
			   $sql_Query = "SELECT * FROM users WHERE email = :email";
			   $stmt = $this->db->prepare($sql_Query);
			   $stmt->execute(array(':email'=>$email));
				if ( $rs=$stmt->fetch() ){
				   $hashed_password = $rs['password'];
				   $user_id = $rs['user_id'];
				   if ( password_verify($password, $hashed_password) ){
						//TODO: Separate creating sessions to another function
						$encEmail = getEncryptedValue(Constants::$usernameEncKey, $email);
						$encUserId = getEncryptedValue(Constants::$userIdEncKey, $user_id);
						//Initialize and Create Sessions
						$_SESSION['loggedInUser'] = $encEmail;
						$_SESSION['userId'] = $encUserId;

						// redirect to index page
						redirectTo('index');
				   }
				   else{
					   //show password incorrect
					   array_push($this->errorsArray, Constants::$password_err);
				   }
				}
				else{
					array_push($this->errorsArray, Constants::$username_not_exists_err);
				}
			}
			catch(PDOException $ex){
			   
			}
		}


		public function register($username, $firstname, $lastname, $email, $email2, $password, $password2) {
			$this->validateData($username, Constants::$un_min_max_id );
			$this->validateData($firstname, Constants::$fn_min_max_id);
			$this->validateData($lastname, Constants::$ln_min_max_id);
		    $this->validateEmailData($email, $email2, Constants::$em_min_max_id);
			$this->validatePasswordData($password, $password2, Constants::$pw_min_max_id);
			
			
			if ( !empty($this->errorsArray) ){
				return false;
			}
			else{
				return $this->insertUserRecord($username, $firstname, $lastname, $email, $password);
			}
		}



		// DATABASE OPERATIONS -----------------------------------------------------
		
		private function insertUserRecord($username, $firstname, $lastname, $email, $password) {
			$uploads_dir = "assets/uploads/images/";
			$default_prof_pic = "default_pic.png";
			$hashed_pw = getHashValue($password);
			
			try{
				$sql_Query = "INSERT INTO users (username, firstname, lastname, email, password, profilepic) VALUES (:un, :fn, :ln, :em, :pw, :px)";

				$stmt = $this->db->prepare($sql_Query);

				$stmt->execute(array(':un'=>$username, ':fn'=>$firstname, ':ln'=>$lastname, ':em'=>$email, ':pw'=>$hashed_pw, ':px'=>$uploads_dir . $default_prof_pic));

				if ( $stmt->rowCount() == 1 ){
					return true;
				}
				else{
					return false;
				}
			}
			catch(PDOException $ex){
				//throw DB error
				return false;
			}
			return false;
		}

		public function updateProfile($curUserId, $username, $email, $password="") {
			$updated = false;
			
			try{
			   if ( $password != "" ) {
					$sql_Update = "UPDATE users SET username = :username, email = :email, password = :password WHERE userid = :id LIMIT 1";
					$stmt = $this->db->prepare($sql_Update);
					$stmt->execute(array(':username'=>$username, ':email'=>$email, ':password'=>$password, ':id'=>$curUserId));
			   }
			   else{
					$sql_Update = "UPDATE users SET username = :username, email = :email WHERE userid = :id LIMIT 1";
					$stmt = $this->db->prepare($sql_Update);
					$stmt->execute(array(':username'=>$username, ':email'=>$email, ':id'=>$curUserId));
				}
			   

			   if ( $stmt->rowCount() == 1 ) {
				   $updated = true;
			   }
			}
			catch(PDOException $ex){
				array_push($this->errorsArray, Constants::$db_update_error . $ex->getMessage()); 
			}

			return $updated;
		}
		

		// END DATABASE OPERATIONS ------------------------------------------------
	


		
		//VALIDATION FUNCTIONS---------------------------------------------------------------------------------------------------------------
		
		public function validateProfile($username, $email, $password, $password1, $password2){
			$this->validateData($username, Constants::$un_min_max );
			$this->validateEmail($email);
			if ( $password != "" || !empty($password) ) {
				$this->validatePasswordData($password, $password1, Constants::$pw_min_max_id);
			}
			
		}


		private function validateData($rawData, $min_max_id) {
			if( !(strlen($rawData) >= $min_max_id['min'])  && strlen($rawData) <= $min_max_id['max'] || empty($rawData)){
				array_push($this->errorsArray, $this->getLengthErrMessage($min_max_id['id'], $min_max_id)); 
				return false;
			}
			
			$excluded_fields = array("Username", "First name", "Last name");
			if ( !$this->isAlphaNumeric($rawData) && (in_array($min_max_id['id'], $excluded_fields)) ) {
				array_push($this->errorsArray, $min_max_id['id'] . " : " . Constants::$non_alphanum_err); 
				return false;
			}
			
			//Check if USERNAME exists in the database

			if ( $min_max_id['id'] == "Username" ) {
				if ( checkIfExists($this->db, $rawData, "users", "username") ){
					array_push($this->errorsArray, $rawData . " : " . Constants::$username_exists_err); 
					return false;
				}
			}

			if ( $min_max_id['id'] == "" ) {
				if ( !checkIfExists($this->db, $rawData, "users", "username") ){
					// array_push($this->errorsArray, $rawData . " : " . Constants::$username_not_exists_err); S	
					// return false;
				}
			}

			return true;
		}
		
		
		private function isAlphaNumeric($rawData){
			$isValid = true;
				if ( preg_match('/[^A-Za-z0-9]/', $rawData) ) {
					return	$isValid = false;
				}
			return $isValid;
		}

		private function validateEmail($rawEmail){
			if ( !filter_var($rawEmail, FILTER_VALIDATE_EMAIL) ){
				array_push($this->errorsArray, "Email is not valid"); 
				return;
			}
		}

		private function validateEmailData($rawEmail1, $rawEmail2, $min_max_id) {

			if ( $rawEmail1 != $rawEmail2 ){
				array_push($this->errorsArray, Constants::$email_unmatch_err); 
				return;
			}

			if ( !filter_var($rawEmail1, FILTER_VALIDATE_EMAIL) ){
				array_push($this->errorsArray, "Email is not valid"); 
				return;
			}

			if ( !$this->validateData($rawEmail1, $min_max_id) ){
				return;
			}

			//TODO: Check if EMAIL exists in the database
		}
		
		private function validatePasswordData($rawPassword1, $rawPassword2) {

			if ( $rawPassword1 != $rawPassword2 ){
				array_push($this->errorsArray, "Passwords do not match"); 
			}

			if ( !$this->validateData($rawPassword1,  Constants::$pw_min_max_id) ){
				return;
			}
		}

		public function isMatch($raw_password, $user_id) {
			try{
			   $sql_Query = "SELECT password FROM users WHERE userid = :userid LIMIT 1";
			   $stmt = $this->db->prepare($sql_Query);
			   $stmt->execute(array(':userid'=>$user_id));

			   if ( $rs=$stmt->fetch() ) {
					//hash the current password sent by the user
					$enc_password = $rs['password'];
				
					//match it with the passwrod from the db record
					// $isMatch = password_verify($raw_password, $enc_password);

					if ( password_verify($raw_password, $enc_password) == false ) {
						array_push($this->errorsArray, Constants::$password_err);
					}
			   }
			   else{
				   // redirect to index page -> suspicious data manipulation from the client
					// redirectTo('index');
					// exit();
					array_push($this->errorsArray, "SUSPICIOUS REQUEST");
			   }
			}
			catch(PDOException $ex){
				array_push($this->errorsArray, Constants::$db_read_error);
			}

			return $this->errorsArray;
		}

		//RETRIEVAL FUNCTIONS---------------------------------------------------------------------------------------------------------------

		private function getLengthErrMessage($identifier, $reqLength) {
			$errString = $identifier . " must be " . $reqLength['min'] . " to " . $reqLength['max'] . " cahracters";
			return $errString;
			
		}


		public function getUserInfo($curUserId) {
			$rs = null;
			try{
				$sql_Query = "SELECT * FROM users WHERE userid = :curUserId";
				$stmt = $this->db->prepare($sql_Query);
				$stmt->execute(array(':curUserId'=>$curUserId));

				if ( !$rs=$stmt->fetch() ){
					array_push($this->errorsArray, Constants::$db_read_error); 
				}
			 }
			 catch(PDOException $ex){
				array_push($this->errorsArray, Constants::$db_read_error . $ex->getMessage()); 
			 }
			 return $rs;
		}


		public function getErrors(){
			if ( !empty($this->errorsArray) ){
				return $errors = $this->errorsArray;
			}
			return $errors="";
		}
		


		//RETRIEVAL FUNCTIONS---------------------------------------------------------------------------------------------------------------

		public function clearErrors(){
			$this->errorsArray = [];
		}
		
		

		// private function getUserProfile(){
			
		// }
	}
?>