<?php


require_once "../vendor/autoload.php";
require_once "../phpari.php";
require_once "../src/interfaces/endpoints.php";


$conn             = new phpari("lnotik", "hwab7bk", "hello-world", "178.62.185.100", 8088, "/ari");
//$conn             = new phpari("ariuser", "4r1u53r", "hello-world", "178.62.19.221", 8088, "/ari");

$cEndPoints       = new endpoints($conn);

echo '<pre>';
//print_r($cEndPoints->endpoints_list());

//$response = $cEndPoints->endpoint_sendmessage('SIP/7002','SIP/7001',"hi");
//print_r($response);
//
//$response = $cEndPoints->endpoints_tech('SIP');
//print_r($response);

//$response = $cEndPoints->endpoints_tech_details('SIP','7002');
//print_r($response);

$response = $cEndPoints->endpoint_sendmessage_intech('SIP','7002',"OK","SIP/7001@from-phones");
print_r($response);

////print_r($app->application_subscribe('stasis-app','ok','1410180487.0','SIP/7002','Up'));
////print_r($app->application_unsubscribe('hello-world','ok','1410179495.209','SIP/7002','on'));
echo '</pre>';
//
//
////$conn->execute();
////$conn->handlers();

///exit(0);


?>