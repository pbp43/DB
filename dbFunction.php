
<?php

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('dbConnection.php');
//require_once('testrabbitMQClient.php');
$connection = dbConnection();

ini_set('display_errors', 'off');
ini_set('log_errors', 'on');
ini_set('error_log', dirname(__FILE__).'/logHistory.txt');
error_reporting(E_ALL);

function doLogin($username, $password){

        $connection = dbConnection();
        $password=sha1($password);
        //$username =mysql_real_escape_string($username);

        $query = "SELECT * FROM Users WHERE username = '$username' and  password = '$password'";

	$result = $connection->query($query);
print_r($result);

if($result){
      if($result->num_rows == 0){
          echo "Login Failed ! Try again!";
          $date=date('Y-m-d H:i:s');
          $txt = "$username: loging falied :$date";
          $myfile = file_put_contents('log.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
          return "False";
        }
      else
        {

                      echo "Login Successful.";
                        $date=date('Y-m-d H:i:s');
                      $txt = "$username: logged in :$date";
                      $myfile = file_put_contents('log.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
                        return "True";



           }
	}
 }

 function register($firstname, $lastname,$username, $password){

     $connection = dbConnection();
     $val=checkUsername($username);
     $password=sha1($password);
     if ($val== "good"){
     $newuser_query = "INSERT INTO Users VALUES ('$firstname', '$lastname','$username', '$password')";

     $result = $connection->query($newuser_query);

     print "You have successfully registered";
       $date=date('Y-m-d H:i:s');
     $txt = "$username:successfully registered :$date";
     $myfile = file_put_contents('log.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
     return "true";
   }
   else{
       $date=date('Y-m-d H:i:s');
     $txt = "$username:already exist . try with other name:$date";
     $myfile = file_put_contents('log.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
     return "false";}

 }
 function checkUsername($username){

    $connection = dbConnection();

    //Query to check if the username is taken
    $check_username = "SELECT * FROM Users WHERE username = '$username'";
    ($t = mysqli_query( $connection,  $check_username) ) or die( mysqli_error($connection) );


	$num = mysqli_num_rows($t);
	if ($num == 0 ){ echo "User does not exist";return "good";}
  else{
	echo"user already exists";return "bad" ;}
}


/////////////////////////////////////////////////////////////////



















function favFood($username,$item){
  $connection = dbConnection();


  $newuser_query = "INSERT INTO favFood VALUES ('$username', '$item')";

  $result = $connection->query($newuser_query);

  print "You have successfully add favFood";
  return "True";
  }

function  removeFavorite($username, $foodName){

          $connection = dbConnection();

          $removefavorite_query = "DELETE FROM favFood WHERE username = '$username' AND foodName = '$foodName'";
          $removefavorite_query_result = $connection->query($removefavorite_query);

          return $removefavorite_query_result;
      }

///////////////////////////////////////////////////////
function searchfood($item){
  $client = new rabbitMQClient("testRabbitMQ.ini","apiServer");
if (isset($argv[1]))
{
  $msg = $argv[1];
}
else
{
  $msg = "test message";
}

$request = array();
$request['type'] = "searchFood";
$request['item']=$item;
$response = $client->send_request($request);

//$response = $client->publish($request);

//echo "client received response: ".PHP_EOL;
//print_r($response);
return $response;
echo "\n\n";

echo $argv[0]." END".PHP_EOL;

}

function requestProcessor($request){

      echo "received request".PHP_EOL;
      var_dump($request);

      if(!isset($request['type']))
      {
        return "ERROR: unsupported message type";
      }

      switch ($request['type']){

        case "login":
          return doLogin($request['username'],$request['password']);

        case "validate_session":
          return doValidate($request['sessionId']);

        case "register":
          return register($request['firstname'],$request['lastname'],$request['username'],$request['password']);

        case "searchfood":
          return searchfood($request['foodid']);

          case "favFood":
            return favFood($request['username'],$request['item']);

      }

      return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

    $server = new rabbitMQServer("testRabbitMQ.ini","testServer");

    $server->process_requests('requestProcessor');
    exit();



?>
