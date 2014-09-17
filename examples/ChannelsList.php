<?php

require_once "../vendor/autoload.php";
$conn      = new phpari("lnotik", "hwab7bk", "hello-world", "178.62.185.100", 8088, "/ari");
$channels  = new channels($conn);





//header('Content-Type: application/json');


echo json_encode(array('The first way'=>'$channels->channel_list()'));
echo "<br>";
echo json_encode($channels->channel_list());
echo "<br>";

echo json_encode(array('The Second way'=>'$conn->channels()->channel_list()'));
echo "<br>";
echo json_encode($conn->channels()->channel_list());
?>