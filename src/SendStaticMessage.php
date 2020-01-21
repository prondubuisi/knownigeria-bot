<?php

namespace App;
use App\Config;


class SendStaticMessage{

   public $userID;
   public $accessToken;
   public $input;
 

   function __construct($input) {

    $this->accessToken = Config::getAccessToken();
   

  }


  public function greetingText() {

      $greetingText =

       [

          "setting_type" => "greeting",
           "greeting"=>

           [

              "text" => "Hi {{user_first_name}}, i am Testi your knowledge 📚📚 Assistant, Click get started to see how well you know Nigeria 🇳🇬🇳🇬"

           ]
      ]; 

      $this->curlStaticResponse($greetingText); 



  }


    public function persistentMenu() {

         
   $persistentMenu = '{
  "persistent_menu":[
    {
      "locale":"default",
      "composer_input_disabled": false,
      "call_to_actions":[
        {
          "title":"👑Challenge",
          "type":"nested",
          "call_to_actions":[
            {
              "title":"🎮Geography",
              "type":"postback",
              "payload":"geography"
            },
            {
              "title":"🎮History",
              "type":"postback",
              "payload":"history"
            },
            {
              "title":"🎮Legend",
              "type":"postback",
              "payload":"legend"
            }
            
          ]
        },
        {
          "type":"web_url",
          "title":"Visit Edukitt",
          "url":"https://edukitt.herokuapp.com",
          "webview_height_ratio":"full"
        }
      ]
    },
    
  ]
}';
       $this->curlStaticResponse2($persistentMenu);



  }

  public function getStartedButton() {
      
           $getStartedButton = 

           [
              "setting_type" => "call_to_actions",
              "thread_state"=>"new_thread",
              "call_to_actions" =>

              [
            [
                "payload" => "welcome_user"
            ]
              ]
           ];

           $this->curlStaticResponse( $getStartedButton);


  }
    
   
   public function curlStaticResponse($input) {

       $accessToken = $this->accessToken;
               

      
       $curl = curl_init();
       curl_setopt($curl, CURLOPT_URL, 'https://graph.facebook.com/v2.6/me/thread_settings?access_token='.$accessToken);
       curl_setopt($curl, CURLOPT_POST, true);
       curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($input));
       curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
       curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
       $output = curl_exec($curl);
       curl_close($curl);
       


   } 

    public function curlStaticResponse2($input) {

       $accessToken = $this->accessToken;
               

      
       $curl = curl_init();
       curl_setopt($curl, CURLOPT_URL, 'https://graph.facebook.com/v2.6/me/messenger_profile?access_token='.$accessToken);
       curl_setopt($curl, CURLOPT_POST, true);
       curl_setopt($curl, CURLOPT_POSTFIELDS, $input);
       curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
       curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
       $output = curl_exec($curl);
       curl_close($curl);
       


   } 
 
 



}
