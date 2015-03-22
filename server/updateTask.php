<?php
	session_start();
	header('Access-Control-Allow-Origin: '. $_SERVER['HTTP_ORIGIN'] );
    header('Access-Control-Allow-Credentials: true' );
    header('Access-Control-Request-Method: *');
    header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: *,x-requested-with,Content-Type');
    header('X-Frame-Options: DENY');

	//Decode received JSON data
	$data = file_get_contents("php://input");
	$receivedData = json_decode($data);


	//Handle all common function as Error handling
	include_once 'common.php';

	//Handle User Authentication, Registration, Password Reset
	include_once 'user.php';	

	$common 		= new Common_Functions();
	$user 			= new User_Functions();


	if(isset($receivedData->{"type"})){
		$response = '';
		switch ($receivedData->{"type"}) {
		    case 'verifyLogin':
		        if(isset($receivedData->{"email"}) && isset($receivedData->{"password"})){
		        	$_userEmail = $receivedData->{"email"};
		        	$_userPass 	= $receivedData->{"password"};
		        	
		        	$response = $user -> verifyLogin($_userEmail, $_userPass);
		        }
		        else{
		        	$response = $common->generateResponse(false,'',1);
		        }
		        echo json_encode($response);
		    break;

		    case 'verifySessionAndAuth':
		    	if(isset($receivedData->{"email"}) && isset($receivedData->{"token"}) && isset($receivedData->{"url"})){
		        	$_userEmail 	= $receivedData->{"email"};
		        	$_userToken 	= $receivedData->{"token"};
		        	$_userURL 		= $receivedData->{"url"};
		        	
		        	$response = $user -> verifySessionAndAuth($_userEmail, $_userToken, $_userURL);
		        }
		        else{
		        	$response = $common->generateResponse(false,'',1);
		        }
		        echo json_encode($response);
		    break;

		    case 'forgotpassword':
		    	if(isset($receivedData->{"email"})){
		    		$_userEmail 	= $receivedData->{"email"};

		    		$response = $user -> forgotpassword($_userEmail);
		    	}
		    	else{
		        	$response = $common->generateResponse(false,'',1);
		        }
		        echo json_encode($response);
		    break;

		    case 'resetpassword':
		    	if(isset($receivedData->{"email"}) && isset($receivedData->{"token"}) && isset($receivedData->{"password"})){
		        	$_userEmail 	= $receivedData->{"email"};
		        	$_userToken 	= $receivedData->{"token"};
		        	$_userPass 		= $receivedData->{"password"};
		        	
		        	$response = $user -> resetpassword($_userEmail, $_userToken, $_userPass);
		        }
		        else{
		        	$response = $common->generateResponse(false,'',1);
		        }
		        echo json_encode($response);
		    break;

		    case 'registerUser':
		    	if(isset($receivedData->{"name"}) && isset($receivedData->{"email"}) && isset($receivedData->{"password"})){
		        	$_userName = $receivedData->{"name"};
		        	$_userEmail = $receivedData->{"email"};
		        	$_userPass 	= $receivedData->{"password"};
		        	
		        	$response = $user -> registerUser($_userName, $_userEmail, $_userPass);
		        }
		        else{
		        	$response = $common->generateResponse(false,'',1);
		        }
		        echo json_encode($response);
		    break;
		}
	}
	else {

	    $response = $common->generateResponse(false,'',1);
	    echo json_encode($response);
	}
?>