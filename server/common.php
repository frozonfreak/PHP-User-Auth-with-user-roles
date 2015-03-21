<?php

//Handle Error Handling 
class Common_Functions{

	function __construct(){

	}

	//destructor
	function __destruct() {
		 
	}

	private $error_message = array(
										1 => 'All fields needs to be set',
										2 => 'Invalid Login',
										3 => 'Error retriving from database', 
										4 => 'Invalid Session',
										5 => 'Valid Session',
										6 => 'User not Authourised'
									);

	public function generateResponse($status, $data, $errorcode = 0){
		if($status){
			return array("status" => 0,
	                      "message"=> $data);
		}
		else{
			return array("status" => $errorcode,
	                      "message"=> $this->error_message[$errorcode]);	
		}
	}
}
?>