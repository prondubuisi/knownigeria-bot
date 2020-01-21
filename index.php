<?php 
require 'vendor/autoload.php'; 
use  App\VerifyBot;
use App\ReceivedMessage;
use App\SendMessage;
use App\SendStaticMessage;
use App\SendQuickReplies;
use App\database\UserInfoDB;
use App\UserDetails;
use App\Questions;
use App\QuestionTracker;
use App\NLP;



use DialogFlow\Client;


global $sendMessage;
global $sendQuickReplies;
global $userInfoDB;
global  $questionTracker;

 global $userID;
 global $input;
 global $questions;
 global $nlp;




globaL $userFirstName;


$verifyBot = new VerifyBot();


 
$input = json_decode(file_get_contents('php://input'), true);

$userInfoDB = new UserInfoDB();
$questionTracker = new QuestionTracker();

$questions = new Questions($input);
$userID = $input['entry'][0]['messaging'][0]['sender']['id'];


$receivedMessage = new ReceivedMessage($input);
$sendMessage = new SendMessage($input);
$sendStaticMessage = new SendStaticMessage($input);
$sendQuickReplies = new SendQuickReplies($input);
$userDetails = new UserDetails($input);

$nlp = new NLP($input); 

$sendStaticMessage->greetingText();
$sendStaticMessage->getStartedButton();
$sendStaticMessage->persistentMenu();

global $userFirstName;
$userFirstName = $userDetails->getFirstName(); 

$quickReplyMessageArray = ["🗺Geography🗺","📒History📒","👩‍🌾Legend👩‍🌾","Option A","Option B","Option C", "Option D","🗺Try Geography🗺", "📒Try History📒","👩‍🌾RTry Legend👩‍🌾","🗺Restart Geography🗺", "📒Restart History📒","👩‍🌾Restart Legend👩‍🌾"]; 


 
if(!empty($receivedMessage->textMessage && !in_array($receivedMessage->textMessage, $quickReplyMessageArray) )) { 

   $nlp->call($receivedMessage->textMessage);

 // $sendMessage->text($response);
         
}
 
 
else if(!empty($receivedMessage->buttonMessage)) {
	

	$message = getPayloadMessage($receivedMessage->buttonMessage);
    $offset = getPayloadOffset($receivedMessage->buttonMessage);

    processButtonMessages($message, $offset);
    

}

else if(!empty($receivedMessage->quickReplyMessage ))
{
    $message = getPayloadMessage($receivedMessage->quickReplyMessage);
    $offset = getPayloadOffset($receivedMessage->quickReplyMessage);
    $answer = getPayloadAnswer($receivedMessage->quickReplyMessage);
    
    processButtonMessages($message, $offset,$answer);
}




function processButtonMessages($message, $offset, $answer = null) {

	global $sendMessage;
    global $sendQuickReplies;
    global $userFirstName;
    global $userID;
    global $input;
    global $questions;
     global $nlp;
     global  $questionTracker;

    addUserToDB();
    $questionTracker->addUserToTracker($userID);
    
    $category_array = ['geography_questions','legend_questions','history_questions'];

    if(in_array($message, $category_array) ) {

        $questionTracker->updateTracker($message,$offset,$userID);
        
        if($answer == "ANSWER") {

             $response = $nlp->call("correct");

             $sendMessage->text($response);
              $questions->deliverQuestion($message,$offset);
          //congratulate
          //get next question

          }
          else if($answer == "reset") {

            
             $questions->deliverQuestion($message,$offset);

          }
        else {
        	 //tell correct answer
        	 //get next question
            $response = $nlp->call("wrong");
            $answer = $questions->getQuestionAnswer($message,$offset);

             $sendMessage->text($response);
             $sendMessage->text($answer);
        	 $questions->deliverQuestion($message,$offset);
        }

        //update question tracker

        
       

    }

       



	if($message == "geography"){
       

       $userCurrentTrack =   $questionTracker->getUserTrack($message);
      $offset =   $questionTracker->getLatestQuestion( $userCurrentTrack, $userID);

        
       // $sendMessage->text($userCurrentTrack."usercurrenttrack".$offset."offset");
       $questions->deliverQuestion("geography_questions", $offset);

		

		

	}
	else if($message == "history") {

		 $userCurrentTrack =  $questionTracker->getUserTrack($message);
      $offset =  $questionTracker->getLatestQuestion( $userCurrentTrack, $userID);
          //$sendMessage->text($userCurrentTrack."usercurrenttrack".$offset."offset");
         $questions->deliverQuestion("history_questions",$offset);

	}
	else if($message == "legend") {

		 $userCurrentTrack =  $questionTracker->getUserTrack($message);
      $offset =   $questionTracker->getLatestQuestion( $userCurrentTrack, $userID);
      // $sendMessage->text($userCurrentTrack."usercurrenttrack".$offset."offset");
      $questions->deliverQuestion("legend_questions",$offset);
       
	}
	else if($message == 'welcome_user') {


        $sendMessage->text("Welcome " .$userFirstName." No long talk talk😀😄. The ⛲fountain of knowledge awaits. Lets see how much you know 🇳🇬🇳🇬Nigeria");

        $sendQuickReplies->getStartedQuickReply();

        

	}
  else if($message == "reset") {

		 $sendQuickReplies->resetQuickReply();

  }

	
}

function getPayloadMessage($buttonMessage) {

	$payload = explode("|",$buttonMessage);
	return $payload[0];
}

function getPayloadOffset($buttonMessage) {

	$payload = explode("|",$buttonMessage);
	
	if($payload[1])

		return $payload[1];
	return 0;
}

function getPayloadAnswer($buttonMessage) {

	$payload = explode("|",$buttonMessage);
	return $payload[2];
}

function addUserToDB() {

	  global $userInfoDB;
	  global $userID;

	 if(!$userInfoDB->isUserAdded($userID))
  
              $userInfoDB->addUser($userID);



}









 
