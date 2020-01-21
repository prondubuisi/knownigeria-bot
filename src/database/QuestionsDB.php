<?php

namespace App\database;
use App\Config;

class QuestionsDB {

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

  public function getQuestion($category,$offset = 0){

  	 $connection = $this->connnectToDB();
  	 $query = "select question_id as id, question, first_option as option1, second_option as option2, third_option as option3, fourth_option as
               option4, image_url, '$category' as category from $category where block_question = false and question_id > $1 limit 1";
  	 $result= pg_query_params($connection,$query,array($offset));

  	 if(!$result)
	{
	  echo pg_last_error($connection);
	} 
	else 
	{
	 // echo "Records created successfully\n";
	}
	
		$questionData = pg_fetch_assoc($result);
	  return $questionData;

  }

   public function getCorrectOption($category,$offset){

     $connection = $this->connnectToDB();
     $query = "select correct_option as answer from $category where question_id = $1 limit 1";
     $result= pg_query_params($connection,$query,array($offset));

     if(!$result)
  {
    echo pg_last_error($connection);
  } 
  else 
  {
   // echo "Records created successfully\n";
  }
  
    $questionData = pg_fetch_assoc($result);
    return $questionData['answer'];

  }

   public function getLastQuestionID($category){

     $connection = $this->connnectToDB();
     $query = "select question_id from $category order by  question_id using > limit 1";
     $result= pg_query($connection,$query);

     if(!$result)
  {
    echo pg_last_error($connection);
  } 
  else 
  {
   // echo "Records created successfully\n";
  }
  
    $questionID = pg_fetch_assoc($result);
    return $questionID['question_id'];

  }

  


  public function close_connection($connection) {

	    pg_close($connection);	
		 
 }
	      



}


?>