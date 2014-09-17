<?php

require_once "../vendor/autoload.php";
require_once "examples-config.php";

$conn              = new phpari(ARI_USERNAME, ARI_PASSWORD, "hello-world", ARI_SERVER, ARI_PORT, ARI_ENDPOINT);
$efa               = new events($conn);

header('Content-Type: application/json');
echo json_encode($efa->events('hello-world'));
exit(0);
?>