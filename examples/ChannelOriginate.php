<?php


require_once "../vendor/autoload.php";

$conn        = new phpari("lnotik", "hwab7bk", "hello-world", "178.62.185.100", 8088, "/ari");
$channels    = new channels($conn);
$response    =  $channels->channel_originate(
    'SIP/7001',
    $data    =  array(
        "extension"      => "7001",
        "context"        =>'from-phone',
        "priority"       => 1,
        "app"            => "",
        "appArgs"        => "",
        "callerid"       => "111",
        "timeout"        => -1,
        "channelId"      => '324234',
        "otherChannelId" => ""
    ),
    $valiables = array("var1"=>"cool")
);

header('Content-Type: application/json');
echo json_encode($response);
exit(0);

?>