<?php

require_once "../vendor/autoload.php";

$conn              = new phpari("lnotik", "hwab7bk", "hello-world", "178.62.185.100", 8088, "/ari");
$mailboxes         = new mailboxes($conn);

header('Content-Type: application/json');
echo json_encode($mailboxes->mailbox_change_state('testMailBox',0,0));
exit(0);
?>