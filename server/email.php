<?php

//Handle Error Handling 
class Email_Functions{

	function __construct(){
		require_once 'config.php';
		require_once 'Mandrill/Mandrill.php';
	}

	//destructor
	function __destruct() {
		 
	}

	public function sendEmail($_userEmail, $_resetURL){
		
		$mandrill = new Mandrill(MANDRILL_KEY);

		// If are not using environment variables to specific your API key, use:
		// $mandrill = new Mandrill("YOUR_API_KEY")

		try{
		 
		        $message = array(
		                'subject' => 'Subject of Email',
		                'html' => $_resetURL, // or just use 'html' to support HTMl markup
		                'text' => $_resetURL, // or just use 'html' to support HTMl markup
		                'from_email' => SENDER_EMAIL,
		                'from_name' => SENDER_NAME, //optional
		                'to' => array(
		                        array( // add more sub-arrays for additional recipients
		                                'email' => $_userEmail,
		                                'name' => 'Recipient Name', // optional
		                                'type' => 'to' //optional. Default is 'to'. Other options: cc & bcc
		                                )
		                ),
		 
		                /* Other API parameters (e.g., 'preserve_recipients => FALSE', 'track_opens => TRUE',
		                  'track_clicks' => TRUE) go here */
		        );
		 
		    $result = $mandrill->messages->send($message);
		    return true;
		 
		} catch(Mandrill_Error $e) {
		 
		    //echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
		 	//$response = $common->generateResponse(false,(($e->getMessage()!=null) ? $e->getMessage():''), 3);
		 	return false;
		    //throw $e;
		}
	}
}
//?>