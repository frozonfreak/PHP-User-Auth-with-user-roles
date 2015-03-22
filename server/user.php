<?php

//Handle User data
class User_Functions{

	function __construct(){
		require_once 'common.php';
		require_once 'config.php';
		require_once 'email.php';
	}

	//destructor
	function __destruct() {
		 
	}

	 private $ADMIN_ALLOWED = array (
										"home", 
										"member"
									);

 	private $MEMBER_ALLOWED = array (
										"member"
									);


	public function verifyLogin($_userEmail, $_userPass){

		$common 		= new Common_Functions();
		$_sessionKey 	= md5(uniqid(rand(), TRUE)); //Generate Hash

		try{
			$db = new PDO(DB_STRING, DB_USER, DB_PASSWORD);
			$sql = $db->prepare("UPDATE users SET remember_token=:remember_token , updated_at = NOW() WHERE email=:user_email AND password = :user_pass");
			$sql->bindParam(':remember_token', $_sessionKey, PDO::PARAM_STR);
			$sql->bindParam(':user_email', $_userEmail, PDO::PARAM_STR);
			$sql->bindParam(':user_pass', $_userPass, PDO::PARAM_STR);
			$sql->execute();
			
			if($sql->rowCount()){
				$response = $common->generateResponse(true,$_sessionKey);
			}
			else{
				$response = $common->generateResponse(false,'',2);
			}
		}
		catch(Exception $e){
			$response = $common->generateResponse(false,(($e->getMessage()!=null) ? $e->getMessage():''),3);
		}

		return $response;
	}

	public function verifySessionAndAuth($_userEmail, $_userToken, $_userURL){
		$common 		= new Common_Functions();

		//Check for valid Session
		$response = $this->verifySession($_userEmail, $_userToken);

		//If valid Session Check for Auth - Full Access for ROOT. No check done
		if($response["status"] == 0 && $response["message"] == ROOT){
			$response["message"] = 'Success';
		}
		else if($response["status"] == 0 && $response["message"] == ADMIN){

			$_urlPath = explode('/',$_userURL);

			if(in_array($_urlPath[count($_urlPath) - 1], $this->ADMIN_ALLOWED))
				$response["message"] = 'Success';
			else
				$response = $common->generateResponse(false,'',6);
		}
		else if($response["status"] == 0 && $response["message"] == MEMBER){
			$_urlPath = explode('/',$_userURL);

			if(in_array($_urlPath[count($_urlPath) - 1], $this->MEMBER_ALLOWED))
				$response["message"] = 'Success';
			else
				$response = $common->generateResponse(false,'',6);
		}
		//else deny access
		else{
			$response = $common->generateResponse(false,'',6);
		}
		return $response;
	}

	//Check if session exists and if session time is within limit
	public function verifySession($_userEmail, $_userToken){
		$common 		= new Common_Functions();

		try{
			$db = new PDO(DB_STRING, DB_USER, DB_PASSWORD);
			$sql = $db->prepare("SELECT role from users WHERE email = :user_email AND remember_token = :remember_token AND TIME_TO_SEC( TIMEDIFF( NOW( ) , updated_at) ) <:session_time");
			$sql->bindParam(':remember_token', $_userToken, PDO::PARAM_STR);
			$sql->bindParam(':user_email', $_userEmail, PDO::PARAM_STR);
			$sql->bindParam(':session_time', $maxSessionTime = DEFAULT_SESSION_TIME, PDO::PARAM_INT);

			$sql->execute();
			$row = $sql->fetch(PDO::FETCH_ASSOC);
		
			if($row){
				$response = $common->generateResponse(true,$row["role"]);
			}
			else{
				$response = $common->generateResponse(false,'',4);
			}
		}
		catch(Exception $e){
			$response = $common->generateResponse(false,(($e->getMessage()!=null) ? $e->getMessage():''),3);
		}

		return $response;
	}


	//Generate reset token and send email to user
	public function forgotpassword($_userEmail){
		$common 		= new Common_Functions();
		$email 			= new Email_Functions();

		$_token 	 	= md5(uniqid(rand(), TRUE)); //Generate Hash

		try{
			$db = new PDO(DB_STRING, DB_USER, DB_PASSWORD);
			$sql = "REPLACE INTO password_resets VALUES ((SELECT email FROM users WHERE email=:user_email), :token, NOW())";
			$res = $db->prepare($sql);
			$output = $res->execute(array(':user_email' => $_userEmail, ':token' => $_token));
			$db = null;
			if($output){
				//Send email
				$res = $email->sendEmail($_userEmail, DEFAULT_HOST.$_userEmail.'/'.$_token);
				//If email successfull then send response to user
				if($res)
					$response = $common->generateResponse(true,'Success');
				else
					$response = $common->generateResponse(false,'',8);	
			}
			else{
				$response = $common->generateResponse(false,'',7);
			}
		}
		catch(Exception $e){
			$response = $common->generateResponse(false,(($e->getMessage()!=null) ? $e->getMessage():''), 3);
		}

		return $response;

	}

	public function resetpassword($_userEmail, $_userToken, $_userPass){
		$common 		= new Common_Functions();

		try{
			//Update password is token is correct
			$db = new PDO(DB_STRING, DB_USER, DB_PASSWORD);
			$sql = $db->prepare("UPDATE password_resets, users SET users.password = :user_pass WHERE password_resets.token = :token AND password_resets.email = :user_email AND password_resets.email = users.email");
			$sql->bindParam(':token', $_userToken, PDO::PARAM_STR);
			$sql->bindParam(':user_email', $_userEmail, PDO::PARAM_STR);
			$sql->bindParam(':user_pass', $_userPass, PDO::PARAM_STR);
			//$sql->execute();
			$db = null;
			if($sql->execute()){

				//DELETE password token
				try{
					$db = new PDO(DB_STRING, DB_USER, DB_PASSWORD);
					$sql = "DELETE FROM password_resets WHERE email=?";
					$res = $db->prepare($sql);
					$res->execute(array($_userEmail));
					$db = null;
					if($res){
						$response = $common->generateResponse(true,'Success');
					}
					else{
						$response = $common->generateResponse(false,'',10);	
					}
				}
				catch(Exception $e){
						$response = $common->generateResponse(false,(($e->getMessage()!=null) ? $e->getMessage():''), 3);
				}
			}
			else{
				$response = $common->generateResponse(false,'',7);
			}
		}
		catch(Exception $e){
			$response = $common->generateResponse(false,(($e->getMessage()!=null) ? $e->getMessage():''),3);
		}

		return $response;		
	}

	public function registerUser($_userName, $_userEmail, $_userPass){
		$common 		= new Common_Functions();
		$_sessionKey 	= md5(uniqid(rand(), TRUE)); //Generate Hash

		try{
			$db = new PDO(DB_STRING, DB_USER, DB_PASSWORD);
			$sql = "INSERT INTO users(name, email, role, password, remember_token, created_at, updated_at) 
					values (:name, :email, :role, :password, :remember_token, NOW(), NOW())";
			$res = $db->prepare($sql);
			$output = $res->execute(array(':name' => $_userName, ':email' => $_userEmail, ':password' => $_userPass, ':role' => 'MEMBER', ':remember_token' => $_sessionKey));
			$db = null;
			if($output){
				$response = $common->generateResponse(true,"Success");
			}
			else{
				$response = $common->generateResponse(false,'',11);	
			}
		}
		catch(Exception $e){
				$response = $common->generateResponse(false,(($e->getMessage()!=null) ? $e->getMessage():''),3);
		}
		return $response;
	}
}
?>