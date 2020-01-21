<?php
namespace App;
use App\Config;
class SendQuickReplies {

	public $userID;
	public $accessToken;
    public $input;

	function __construct($input) {

	    $this->userID = $input['entry'][0]['messaging'][0]['sender']['id'];
	    $this->accessToken = Config::getAccessToken();
	    $this->input = $input;

    }

    public function getStartedQuickReply() {

	     $userID = $this->userID;
	          
	     $quickReply =
	     [

	        'recipient'=>
	        [
	          'id'=> $userID
	        ],

	        'message'=>
	         [
	            'text'=> 'Choose a Category',
	            'quick_replies'=>
	            [
	                [
	                  'content_type'=>'text',
	                  'title'=>'🗺Geography🗺',
	                  'payload'=>'geography'
	                ],
	                [
	                  'content_type'=>'text',
	                  'title'=>'📒History📒',
	                  'payload'=>'history'
	                ],
	                [
	                  'content_type'=>'text',
	                  'title'=>'👩‍🌾Legend👩‍🌾',
	                  'payload'=>'legend'
	                ]
	            ]
	        ]
	     ];

	     $this->curlResponse($quickReply);


    }

     public function resetQuickReply() {

	     $userID = $this->userID;
	          
	     $quickReply =
	     [

	        'recipient'=>
	        [
	          'id'=> $userID
	        ],

	        'message'=>
	         [
	            'text'=> 'Which category do you want to reset?',
	            'quick_replies'=>
	            [
	                [
	                  'content_type'=>'text',
	                  'title'=>'🗺Restart Geography🗺',
	                  'payload'=>'geography_questions|0'
	                ],
	                [
	                  'content_type'=>'text',
	                  'title'=>'📒Restart History📒',
	                  'payload'=>'history_questions|0'
	                ],
	                [
	                  'content_type'=>'text',
	                  'title'=>'👩‍🌾Restart Legend👩‍🌾',
	                  'payload'=>'legend_questions|0'
	                ]
	            ]
	        ]
	     ];

	     $this->curlResponse($quickReply);


    }


     public function dynamicQuickReply($replyContent) {

	     $userID = $this->userID;
	          
	     $quickReply =
	     [

	        'recipient'=>
	        [
	          'id'=> $userID
	        ],

	        'message'=>
	         [
	            'text'=> "What would you like to do next?",
	            'quick_replies'=>  $replyContent
	            
	        ]
	     ];

	     $this->curlResponse($quickReply);


    }

    public function questionOptionsQuickReply($payload) {

    	 $userID = $this->userID;
	          
	     $quickReply =
	     [

	        'recipient'=>
	        [
	          'id'=> $userID
	        ],

	        'message'=>
	         [
	            'text'=> 'What\'s your answer',
	            'quick_replies'=>
	            [
	                [
	                  'content_type'=>'text',
	                  'title'=>'Option A',
	                  'payload'=>$payload[0]
	                ],
	                [
	                  'content_type'=>'text',
	                  'title'=>'Option B',
	                  'payload'=>$payload[1]
	                ],
	                [
	                  'content_type'=>'text',
	                  'title'=>'Option C',
	                  'payload'=>$payload[2]
	                ],

	                [
	                  'content_type'=>'text',
	                  'title'=>'Option D',
	                  'payload'=>$payload[3]
	                ]
	            ]
	        ]
	     ];

	     $this->curlResponse($quickReply);





    }

    
    public function curlResponse($response) {

      $accessToken = $this->accessToken;
	    $input=  $this->input;
	
	    $curl = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token='.$accessToken);
	    curl_setopt($curl, CURLOPT_POST, 1);
	    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($response));
	    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

      if(!empty($input)) {
	        
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	        $result = curl_exec($curl);
      }

      curl_close($curl); 

	  }


}