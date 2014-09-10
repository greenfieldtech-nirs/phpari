<?php


require_once "../vendor/autoload.php";
require_once "../phpari.php";

$conn      = new phpari("lnotik", "hwab7bk", "hello-world", "178.62.185.100", 8088, "/ari");
$bridges   = new bridges($conn);








echo '<pre>';

print_r($bridges->bridges_list());

echo '</pre>';
