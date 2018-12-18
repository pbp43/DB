
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
//////////////////////////////////////////

function favFood($username,$item){
  $connection = dbConnection();

 //echo "$username";
  $newuser_query = "INSERT INTO favFood VALUES ('$username', '$item',NOW())";

  $result = $connection->query($newuser_query);

          print "You have successfully add favFood";
          return "True";
  }
////////////////////////////////////////////////////////////////////

function  deletefav($username, $item){

          $connection = dbConnection();

          $removefavorite_query = "DELETE FROM favFood WHERE username = '$username' AND item = '$item'";
          $removefavorite_query_result = $connection->query($removefavorite_query);

                  print "You have successfully delete favFood";
                  return "True";


      }
//////////////////////////////////////////////////////
function  showfav($username){

                $connection = dbConnection();

                $check_username = "select * from favFood where username='$username'";


                ($t = mysqli_query( $connection,  $check_username) ) or die( mysqli_error($connection) );


                        while ($r=mysqli_fetch_array($t, MYSQLI_ASSOC))
                        {

                         $username = $r["username"];
                         $item = $r["item"];
                         //= $r["username"]$username="<br>user :$username";
                         $output .="<br>User is  : $username <br> ";
                         $output .="<br>item is  : $item <br> ";
                         //$output .=" Item : $item <br>";
                         //$output .=" Search Date : $date <br>";

                        }

                     	return $output;
            }

///////////////////////////////////////////////////////
function searchfood($username,$item){
  $connection = dbConnection();

 //echo "$username";
  $newuser_query = "INSERT INTO history VALUES ('$username', '$item',NOW())";

  $result = $connection->query($newuser_query);

  print "Search added to history";


return $response;
echo "\n\n";

echo $argv[0]." END".PHP_EOL;

}
/////////////////////////////////////////////////////////ORDER BY date limit 5
function recent($username){

   $connection = dbConnection();

   //Query to check if the username is taken
   $check_username = "SELECT * FROM history WHERE username = '$username' ORDER BY date limit 5";
   ($t = mysqli_query( $connection,  $check_username) ) or die( mysqli_error($connection) );


 while ($r=mysqli_fetch_array($t, MYSQLI_ASSOC))
	{
		$username = $r["username"];
		$date = $r["date"];
    $item= $r["item"];
		$output .="<br> User : $username <br> ";
		$output .=" Item : $item <br>";
		$output .=" Search Date : $date <br>";

	}

	//echo $output;
  return $output;

}
/////////////////////////////////////////////
function trackresults($username){

   $connection = dbConnection();


   //Query to check if the username is taken

   $check_username1 = "SELECT  * FROM calorietrack WHERE username = '$username' ";
   ($t2 = mysqli_query( $connection,  $check_username1) ) or die( mysqli_error($connection) );
   while ($r=mysqli_fetch_array($t2, MYSQLI_ASSOC))
   {

    $username= $r["username"];
    $item=$r["item"];
    $cal  =$r["calories"];
    $sodium=$r["sodium"];
    $protein=$r["protein"];
    $sugar= $r["sugar"];
    $carb=$r["carb"];
    $output .="<br>username: $username <br> ";
    $output .=" Item : $item <br>";
    $output .=" Calories : $cal <br>";
    $output .=" Sodium : $sodium <br>";
    $output .=" Protein : $protein <br>";
    $output .=" Sugar : $sugar <br>";
    $output .=" Carbohydrates : $carb <br>";
    //$output .=" Search Date : $date <br>";

   }

   $check_username = "SELECT  SUM(calories) FROM calorietrack WHERE username = '$username' ";

   ($t = mysqli_query( $connection,  $check_username) ) or die( mysqli_error($connection) );


   while ($r=mysqli_fetch_array($t, MYSQLI_ASSOC))
   {

    $calories = $r["SUM(calories)"];
    //= $r["username"]$username="<br>user :$username";
    $output .="<br>Total calories : $calories <br> ";
    //$output .=" Item : $item <br>";
    //$output .=" Search Date : $date <br>";

   }

	return $output;


}
///////////////////////////////////////////////////////
function addToCalorieTracker($username,$item){
  $output = calorietrack($username,$item);


  $connection = dbConnection();

  $iteminfo = $output['fields'];

  echo ("Item ID - ");
  echo ($iteminfo['item_id']);
  echo"<br>";
  echo ("Item Name - ");
  echo ($iteminfo['item_name']) ;
  echo"<br>";
  echo ("Brand Name - ");
  echo ($iteminfo['brand_name']);
  echo"<br>";
  echo ("$item Calories - ");
  $calories = $iteminfo['nf_calories'];
  echo ($calories);
  echo"<br>";
  echo ("$item Sodium - ");
  $sodium=$iteminfo['nf_sodium'];
  $protein=$iteminfo['nf_protein'];
  $sugar =$iteminfo['nf_sugars'];
  $carb =$iteminfo['nf_total_carbohydrate'];
  //$carb=1;
  echo"<br>";
  echo ("Serving Size Quantity - ");
  echo ($iteminfo['nf_serving_size_qty']);
  echo"<br>";
  echo ("Serving Size Unit - ");
  echo($iteminfo['nf_serving_size_unit']);
  echo"<br>";
  $newuser_query = "INSERT INTO calorietrack VALUES ('$username', '$item','$calories',NOW(),'$sodium','$protein','$sugar','$carb')";

  $result = $connection->query($newuser_query);

  $response = "True";
  echo $response;

return $response;
echo "\n\n";

echo $argv[0]." END".PHP_EOL;

}
///////////////////////////////////
function  deletetracker($username){

          $connection = dbConnection();

          $removefavorite_query = "DELETE FROM calorietrack WHERE username = '$username' ";
          $removefavorite_query_result = $connection->query($removefavorite_query);

                  print "You have successfully delete calorietrack";
                  return "True";


      }
/////////////////////////////////////////////////
function calorietrack($username,$item){
  $client = new rabbitMQClient("testRabbitMQ.ini", "apiServer");

  if(isset($argv[1])){
      $msg = $argv[1];
  }
  else{
      $msg = "client";
  }
  $request = array();
  $request['type'] = "calorietrack";
  $request['username'] = $username;
  $request['item'] = $item;

  $response = $client->send_request($request);

    return $response;
    echo "\n\n";

    echo $argv[0]." END".PHP_EOL;

}
/////////////////////////////////////////////
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

        case "search":
          return searchfood($request['username'],$request['item']);

          case "deletefav":
            return deletefav($request['username'],$request['item']);
          case "showfav":
            return showfav($request['username']);
          case "deletetracker":
                return deletetracker($request['username']);


          case "favFood":
              return favFood($request['username'],$request['item']);
          case "recent":
              return recent($request['username']);
          case "calorietrack":
            return addToCalorieTracker($request['username'],$request['item']);
          case "trackresults":
          return trackresults($request['username']);
      }

      return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

    $server = new rabbitMQServer("testRabbitMQ.ini","testServer");

    $server->process_requests('requestProcessor');
    exit();



?>
