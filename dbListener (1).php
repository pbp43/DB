<?php
    //Requried files
    require_once('path.inc');
    require_once('get_host_info.inc');
    require_once('rabbitMQLib.inc');
    require_once('dbFunction.php');
  /*  error_reporting(E_ALL);
    ini_set('display_errors', 'on');
    ini_set('log_errors', 'On');
    ini_set('error_log', dirname(__FILE__).'/../logging/log.txt');
    //logAndSendErrors();*/

    //This will route the request from server to function
    function requestProcessor($request){
        echo "received request".PHP_EOL;
        echo $request['type'];
        var_dump($request);

        if(!isset($request['type'])){
            return array('message'=>"ERROR: Message type is not supported");
        }
        switch($request['type']){

            //Login & Authentication request
            case "Login":
                echo "<br>in login<br>";
                $response_msg = doLogin($request['username'],$request['password']);
                break;

            //Check if username is already taken
            case "CheckUsername":
                echo "<br>in Checkusername";
                $response_msg = checkUsername($request['username']);
                echo "Result: " . $response_msg;
                break;

            //Check if email is valid
            case "CheckEmail":
                echo "<br>in CheckEmail";
                $response_msg = checkEmail($request['email']);
                break;


            //Send email with username and password
            case "SendEmail":
                echo "<br>in CheckEmail";
                $response_msg = sendEmail($request['email']);
                break;

            //New User registration
            case "Register":
                echo "<br>in register";
                $response_msg = register($request['firstname'], $request['lastname'],$request['username'], $request['password']);
                break;

            //Search cities of state
            case "CityByState":
                echo "<br>In search type";
                $response_msg = cityByState($request['state']);
                break;

            //Search for restaurants
            case "RestaurantInfo":
                echo "<br>In Restaurant info";
                $response_msg = restaurantInfo($request['state'], $request['city'], $request['cuisine_id']);
                break;

            //Enter suggestion for restaurant
            case "WriteSuggestion":
                echo "<br>In WriteSuggestion";
                $response_msg = writeSuggestion($request['username'], $request['dish_name'], $request['suggestion'], $request['restaurant_id']);
                break;

            //Enter reviews for restaurant
            case "WriteReview":
                $response_msg = writeReview($request['username'], $request['restaurant_id'], $request['rating'], $request['review_text']);
                break;

            //Get individual restaurant info
            case "UniqueRestaurantInfo":
                echo "going in function";
                $response_msg = uniqueRestaurantInfo($request['restaurant_id'], $request['username']);
                echo "out of function";
                break;

            //Get user profile
            case "UserProfile":
                $response_msg = userProfile($request['username']);
                break;

            //Add favorite
            case "AddFavorite":
                $response_msg = addFavorite($request['username'], $request['restaurant_id']);
                break;

            //Remove favorite
            case "RemoveFavorite":
                $response_msg = removeFavorite($request['username'], $request['restaurant_id']);
                break;

        }
        echo $response_msg;
        return $response_msg;
    }
    //creating a new server
    $server = new rabbitMQServer('testRabbitMQ.ini', 'testServer');

    //processes the request sent by client
    $server->process_requests('requestProcessor');

    //exit();
?>
