<?php

require_once "../vendor/autoload.php";

$conn              = new phpari("lnotik", "hwab7bk", "hello-world", "178.62.185.100", 8088, "/ari");
$cRecordings       = new recordings($conn);

header('Content-Type: application/json');
echo json_encode($cRecordings->recording_list());
exit(0);
?>