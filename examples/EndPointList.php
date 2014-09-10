<?php


require_once "../vendor/autoload.php";

$conn             = new phpari("lnotik", "hwab7bk", "hello-world", "178.62.185.100", 8088, "/ari");
$cEndPoints       = new endpoints($conn);
$response         = $cEndPoints->endpoints_list();
header('Content-Type: application/json');
echo json_encode($response );
exit(0);


?>