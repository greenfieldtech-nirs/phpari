<?php


include_once '../src/Connector.php';
include_once '../src/interfaces/applications.php';

$conn      = new phpari("lnotik", "hwab7bk", "hello-world", "178.62.185.100", 8088, "/ari");
$app       = new applications($conn);

echo '<pre>';
print_r($app->application_list());
//print_r($app->application_subscribe('stasis-app','ok','1410180487.0','SIP/7002','Up'));
//print_r($app->application_unsubscribe('hello-world','ok','1410179495.209','SIP/7002','on'));
echo '</pre>';


//$conn->execute();
//$conn->handlers();

exit(0);