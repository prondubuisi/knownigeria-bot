<?php
namespace App;
use App\database\QuestionsDB;
use App\SendQuickReplies;
use App\SendMessage;
use App\QuestionTracker;
use App\database\QuestionTrackerDB;

class Questions {

	public $questionsDB;
	public $sendQuickReplies;
	public $sendMessage;
	public $questionTracker;
	public $userID;



function __construct($input) {

	$this->questionsDB = new QuestionsDB();
	$this->sendQuickReplies = new SendQuickReplies($input);
	$this->sendMessage = new SendMessage($input);
	$this->questionTracker = new QuestionTracker();
	$this->questionTrackerDB = new QuestionTrackerDB();
	$this->userID = $input['entry'][0]['messaging'][0]['sender']['id'];


}

function hasReachedLimit($userID) {

     //get each category question last id
	//get  current tracker id 
	//compare to current tracker id
	//build quick reply based on findings
	$questionCategories = ["geography_questions","history_questions","legend_questions"];
	$lastQuestionIDs = [];
	$lastTrackerIDs = [];

	foreach ($questionCategories as $category) {

		$questionID = $this->getCategoryLastQuestionID($category);

		$trackerCategory = $this->questionTracker->getUserTrack($category);
        $trackerID = $this->questionTrackerDB->getLatestQuestion($trackerCategory,$userID);

		
		array_push($lastQuestionIDs,$questionID);
		array_push($lastTrackerIDs,$trackerID);
		//$this->sendMessage->text($questionID."questionID".$trackerCategory."trackerCategory".$trackerID."lastTrackerID");
	}

 $this->buildQuestionLimitQuickReply($lastQuestionIDs, $lastTrackerIDs);    


}

function buildQuestionLimitQuickReply($lastQuestionIDs, $lastTrackerIDs ) {

	$counter = 0;
	$questionCategories = ["geography_questions","history_questions","legend_questions"];
	$tryTitle = ["🗺Try Geography🗺", "📒Try History📒","👩‍🌾RTry Legend👩‍🌾"];
	$tryPayload = ["geography","history","legend"];
	$resetTitle = ["🗺Restart Geography🗺", "📒Restart History📒","👩‍🌾Restart Legend👩‍🌾"];
	$resetPayload = ['geography_questions|0|reset','history_questions|0|reset','legend_questions|0|reset'];



	$replyArray = [];

	foreach ($lastQuestionIDs as $lastQuestionID) {

           //$this->sendMessage->text($lastQuestionID."$lastQuestionID".$lastTrackerIDs[$counter]."$lastTrackerIDs");
             
          if($lastQuestionID == $lastTrackerIDs[$counter]) {

             // $this->sendMessage->text("entered resetOption");
          	//build resetQuestion
          	$quickReply = $this->buildResetQuestionsQuickReply($resetTitle[$counter],$resetPayload[$counter]);
            
            


           }
           else {
               
               $quickReply = $this->buildTryQuestionsQuickReply($tryTitle[$counter], $tryPayload[$counter]);

           }

          //buildTryQuestionQuickReply
        array_push($replyArray,$quickReply);
		$counter++;
	}

    $this->sendMessage->text("Coolio👏🏿👏🏿, you have exhausted questions in this category🔥🔥");
	$this->sendQuickReplies->dynamicQuickReply($replyArray);



}

function buildResetQuestionsQuickReply($title,$payload) {

	
	               
	          $quickReply =   [
	                  'content_type'=>'text',
	                  'title'=>$title,
	                  'payload'=>$payload
	                ];

	          return $quickReply;
	               


}

function buildTryQuestionsQuickReply($title,$payload) {

              $quickReply =   [
	                  'content_type'=>'text',
	                  'title'=>$title,
	                  'payload'=>$payload
	                ];

	          return $quickReply;

}

function getCategoryLastQuestionID($category) {
    
   return $this->questionsDB->getLastQuestionID($category);
}

function deliverQuestion($category, $offset = 0) {
	
 
 $questionDetails = $this->getQuestion($category,$offset);



     if( !empty($questionDetails['question'])) {

			$this->sendMessage->text("Question : ".$questionDetails['question']);
			$this->sendMessage->image($questionDetails['question_image']);
			$this->sendMessage->text("Option A : ".$questionDetails['option1']);
			$this->sendMessage->text("Option B: ".$questionDetails['option2']);
			$this->sendMessage->text("Option C : ".$questionDetails['option3']);
			$this->sendMessage->text("Option D : ".$questionDetails['option4']);
		
			
			$this->sendQuickReplies->questionOptionsQuickReply($questionDetails['payload']);


	    }
	    else 
	    	$this->hasReachedLimit($this->userID);
}

function getQuestion($category, $offset = 0) {

  
   return  $this->processQuestion($this->questionsDB->getQuestion($category,$offset));

}

function processQuestion($questionDetails) {

    
		$questionOptions = [
		 		$questionDetails['option1'],
		 		$questionDetails['option2'],
		 		$questionDetails['option3'],
		 		$questionDetails['option4']
		 	];


		 	shuffle($questionOptions);

         

         $questionPayload = $this->prepareQuestionPayload($questionDetails['category'],$questionDetails['id'],$questionOptions);
		 $questionOptions  = $this->prepareQuestionOptions($questionOptions);
		

		 	
		 	return [
		 		      'question' => $questionDetails['question'],
		 		      'question_image' =>  $questionDetails['image_url'],
		 		      'option1' => $questionOptions[0],
		 		      'option2' => $questionOptions[1],
		 		      'option3' => $questionOptions[2],
		 		      'option4' => $questionOptions[3],
		 		      'payload' => $questionPayload

		 		];

}

function prepareQuestionOptions($options) {

	   $strippedOption;
	   $optionsArray = [];

       foreach ($options as $option) {
       	   
       	  $strippedOption =  explode("|", $option);
       	  array_push($optionsArray, $strippedOption[0]);
       }

       return $optionsArray;
}

function prepareQuestionPayload($category,$questionID, $options) {
       
       $strippedOption;
	   $payloadArray = [];

       foreach ($options as $option) {
       	   
       	  $strippedOption =  explode("|", $option);
       	  array_push($payloadArray, "$category|$questionID|".$strippedOption[1]);
       	 
       }

       return $payloadArray;

       
}

function getQuestionAnswer($category,$option) {

	
  $answer = $this->questionsDB->getCorrectOption($category,$option);
  return $this->formatQuestionAnswer($answer);
}

function formatQuestionAnswer($answer) {

    return "Correct Option is : $answer ✅✅";
}


}


?>