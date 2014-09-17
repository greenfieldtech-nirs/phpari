<?php


require_once "../vendor/autoload.php";


try{

    $conn        = new phpari("lnotik", "hwab7bk", "hello-world", "178.62.185.100", 8088, "/ari"); //initiate connection object
    $channels    = new channels($conn); //2 ways


    $postData = file_get_contents("php://input"); //let's get a POST  request

//    if(! ($postData)  || empty($postData))
//        throw new Exception('The post data is empty and the channel ID  is missing',503);
//
//    $postDataObject  = json_decode($postData,FALSE);
//    $channelID       =  $postDataObject->channelID;
//    $endpoint        =  $postDataObject->endpoint;
//    $extension       =  $postDataObject->extension;
//    $context         =  $postDataObject->from-phone;



    $response    = $conn->channels()->channel_originate(

            'SIP/7008',

            $data    =  array(
                "extension"      => "7001",
                "context"        =>'from-phone',
                "priority"       => 1,
                "app"            => "",
                "appArgs"        => "",
                "callerid"       => "111",
                "timeout"        => -1,
                "channelId"      => '324234',
                "otherChannelId" => ""
            ),
            $valiables = array("var1"=>"cool")
        );

    header('Content-Type: application/json');
    echo json_encode($response);
    exit(0);
}

catch (Exception $e)
{
    header('Content-Type: application/json');
    echo json_encode(array('status'=>$e->getCode(),'message'=>$e->getMessage()));
}


?>