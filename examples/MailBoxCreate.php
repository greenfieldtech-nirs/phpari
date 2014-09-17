<?php

require_once "../vendor/autoload.php";
require_once "examples-config.php";

$conn              = new phpari(ARI_USERNAME, ARI_PASSWORD, "hello-world", ARI_SERVER, ARI_PORT, ARI_ENDPOINT);
$mailboxes         = new mailboxes($conn);

header('Content-Type: application/json');
echo json_encode($mailboxes->mailbox_change_state('testMailBox',0,0));
exit(0);
?>