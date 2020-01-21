<?php


namespace App;

Class Config {

   
  static $facebookCredentials = [

    'verifyToken' => 'verifyToken',

    'accessToken' => 'accessToken'


  ];
  
  static $DBCredentials = [
    
    'host' => 'host',
    'DBName' => 'DBName',
    'username' => 'username',
    'password' => 'password'

  ];
   
  static function getVerifyToken() {

    return  Config::$facebookCredentials['verifyToken'];

  }

  static function getAccessToken() {

    return  Config::$facebookCredentials['accessToken'];

  }

   static function getHost() {

    return  Config::$DBCredentials['host'];
                    
  }

   static function getDBName() {

    return  Config::$DBCredentials['DBName'];

  }

   static function getUsername() {

    return  Config::$DBCredentials['username'];

  }

   static function getPassword() {

    return  Config::$DBCredentials['password'];

  }

  

}
