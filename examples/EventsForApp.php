<?php

require_once "../vendor/autoload.php";

$conn              = new phpari("lnotik", "hwab7bk", "hello-world", "178.62.185.100", 8088, "/ari");
$efa               = new events($conn);

header('Content-Type: application/json');
echo json_encode($efa->events('hello-world'));
exit(0);
?>