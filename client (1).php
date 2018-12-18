<?php
    /*
        This file creates new rabbit MQ client instances
    */
    //  Required files
    require_once('/home/denny/dev_git/Rabbit/path.inc');
    require_once('/home/denny/dev_git/Rabbit/get_host_info.inc');
    require_once('/home/denny/dev_git/Rabbit/rabbitMQLib.inc');
    //  creates rabbitMq client instance for Database server
    function createClientForDb($request){
        $client = new rabbitMQClient("testRabbitMQ_db.ini", "testServer");

        if(isset($argv[1])){
            $msg = $argv[1];
        }
        else{
            $msg = "client";
        }


        $response = $client->send_request($request);

        return $response;
    }
    //  creates rabbitMq client instance for Rabbot MQ server


?>
