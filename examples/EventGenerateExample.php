<?php


require_once "../vendor/autoload.php";


try{

    $conn        = new phpari("lnotik", "hwab7bk", "hello-world", "178.62.185.100", 8088, "/ari"); //initiate connection object
    $event       = new events($conn);

    $valiables = array("var1"=>"cool");
    $response  = $event->event_generate('justName',"hello-world","141",null,'SIP/7008',"leosip",$valiables);



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