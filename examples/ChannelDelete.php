<?php

require_once "../vendor/autoload.php";

try{
    $conn        = new phpari("lnotik", "hwab7bk", "hello-world", "178.62.185.100", 8088, "/ari");
    $channels    = new channels($conn);
    $postData = file_get_contents("php://input");

    if(! ($postData)  || empty($postData))
        throw new Exception('The post data is empty and the channel ID  is missing',503);

    $postDataObject  = json_decode($postData,FALSE);
    $channelID       =  $postDataObject->channelID;
    $response        = $channels->channel_delete($channelID);

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