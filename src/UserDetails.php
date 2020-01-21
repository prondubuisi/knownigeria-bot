<?php
namespace App;
use App\Config;

class UserDetails {

	public $accessToken;
	public $userID;
	public $firstName;
	public $lastName;


 function __construct($input) {

    $this->accessToken = Config::getAccessToken();
    $this->userID = $input['entry'][0]['messaging'][0]['sender']['id'];
 }

 public function getFirstName() {

 	$this->getName();
 	return $this->firstName;

 }


 public function getLastName() {

 	$this->getName();
 	return $this->lastName;
 	
 }



 function getName() {

    
    $accessToken = $this->accessToken;
    $userID = $this->userID;
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/v2.6/$userID?fields=first_name,last_name&access_token=$accessToken");
	$result = curl_exec($ch);
	curl_close($ch);
	$details = json_decode($result);
	
	 $this->firstName = $details->first_name; 

	 $this->lastName = $details->last_name;


 }

}


?>