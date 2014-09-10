<?php


require_once "../vendor/autoload.php";
require_once "../phpari.php";
require_once "../src/interfaces/recordings.php";


$conn             = new phpari("lnotik", "hwab7bk", "hello-world", "178.62.185.100", 8088, "/ari");
//$conn             = new phpari("ariuser", "4r1u53r", "hello-world", "178.62.19.221", 8088, "/ari");

$cRecordings       = new recordings($conn);

echo '<pre>';


print_r($cRecordings->recording_list());


echo '</pre>';



exit(0);

?>