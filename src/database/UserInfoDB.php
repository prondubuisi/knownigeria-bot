<?php
namespace App\database;
use App\Config;



 class UserInfoDB{

	public $host;
	public $DBName;
	public $username;
	public $password;
	public $input;

  function __construct() {

  	$this->host = Config::getHost();
  	$this->DBName = Config::getDBName();
    $this->username = Config::getUsername();
    $this->password = Config::getPassword();
    

   }


   public function connnectToDB() {


   	 $connection = pg_connect("host=$this->host dbname=$this->DBName user=$this->username password=$this->password sslmode=require");

     if(!$connection) {
       echo "Error : Unable to open database\n";
     } 
	 else {
      
      return $connection;
     }

   }

   public function addUser($userFacebookID) {


    $connection = $this->connnectToDB();

     
	 $result= pg_query_params($connection,'insert into user_info(facebook_id) values ($1)',array($userFacebookID));
	 
	if(!$result)
	{
	  echo pg_last_error($connection);
	} 
	else 
	{
	 // echo "Records created successfully\n";
	}
	
    $row = pg_affected_rows($result);
	return $row;



   }

   public function isUserAdded($userFacebookID) {


    $connection = $this->connnectToDB();

	$result = pg_query_params($connection,'select facebook_id from user_info where facebook_id = $1',array($userFacebookID));
	if(!$result) {
	  echo pg_last_error($connection);
	}

	else {
	 // echo "Records created successfully\n";
	}
	
	$rows = pg_num_rows($result);
    return $rows;


   }
   
   public function close_connection($connection) {

	    pg_close($connection);	
		 
	}
	      


   


}