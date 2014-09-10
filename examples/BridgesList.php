<?php


require_once "../vendor/autoload.php";

$conn      = new phpari("lnotik", "hwab7bk", "hello-world", "178.62.185.100", 8088, "/ari");
$bridges   = new bridges($conn);

header('Content-Type: application/json');
echo json_encode($bridges->channel_list());
