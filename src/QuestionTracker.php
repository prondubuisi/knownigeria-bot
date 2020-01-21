<?php
namespace App;
use App\database\QuestionTrackerDB;


class QuestionTracker {
     
     public $questionTrackerDB;

	function __construct() {
       
       $this->questionTrackerDB = new QuestionTrackerDB();

	}

	function getUserTrack($category) {

   $geographyArray = ["geography_questions","geography"];
   $legendArray = ["legend_questions","legend"];
   $historyArray = ["history_questions","history"];

	if(in_array($category, $geographyArray) )

		return "geo_question_id";
	else if (in_array($category, $legendArray)) 
		
		return "leg_question_id";
	
  else if(in_array($category, $historyArray))

	return "his_question_id";

   return "bae";

}
function addUserToTracker($userID) {
	

	if(!$this->isUserInTracker($userID))

		$this->questionTrackerDB->addUser($userID);


}

function isUserInTracker($userID) {
    
	if($this->questionTrackerDB->isUserAdded($userID))

		return true;

	return false;
}

function updateTracker($category,$offset,$userID) {
  
  $track = $this->getUserTrack($category);

  $this->questionTrackerDB->updateLatestQuestion($track,$offset,$userID);




 
}

function getLatestQuestion($userCurrentTrack, $userID) { 

 return $this->questionTrackerDB->getLatestQuestion( $userCurrentTrack, $userID);

}

}



?>