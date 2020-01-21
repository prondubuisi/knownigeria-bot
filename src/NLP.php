<?php
namespace App;
use App\UserDetails;
use DialogFlow\Client;
use App\SendMessage;
use App\SendQuickReplies;

class NLP {
  

public $userFirstName;
public $sendMessage;
public $sendQuickReplies;

function __construct($input) {
 
 $userDetails = new UserDetails($input);
 $this->userFirstName = $userDetails->getFirstName();
 $this->sendMessage = new SendMessage($input);
 $this->sendQuickReplies = new SendQuickReplies($input);

}


function call($textMessage) {

      
 try {

    $client = new Client('dialogflow_client key');

    $query = $client->get('query', [
        'query' => $textMessage,
        'sessionId' => time(),
    ]);

    $response = json_decode((string) $query->getBody(), true);
    $response =  $response['result']['fulfillment']['speech'];

    return $this->parseString($response);


} catch (\Exception $error) {
    
    error_log($error);
}

}




function parseString($text) {

	

	if (strpos($text, '#') !== false) {

		$response = str_replace("#", "", $text);

		$this->sendMessage->text($response);

		$this->sendQuickReplies->getStartedQuickReply();
    

    }

    else if(strpos($text, '$') !== false) {

    	return str_replace("$", $this->userFirstName,$text);
         
    }
    else {

    	$this->sendMessage->text($text);
    }
 
 

}


}


?>