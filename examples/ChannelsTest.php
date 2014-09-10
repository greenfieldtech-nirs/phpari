<?php


require_once "../vendor/autoload.php";
require_once "../phpari.php";

$conn      = new phpari("lnotik", "hwab7bk", "hello-world", "178.62.185.100", 8088, "/ari");
$channels  = new channels($conn);


echo '<pre>';

print_r($channels->channel_list());
$response    =  $channels->channel_originate(
    'SIP/7002',
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


print_r($response);

echo '</pre>';

//$response = $channel->channel_ringing_stop('4354354354345');

//print_r($response);

//echo "</pre>";


//$chClient->handlers();
//$chClient->execute();
//
//
//$chClient->channel_answer('1410090433.174');

/**
 * Get some basic information from ARI
 */
//$ariAsterisk = new asterisk($basicAriClient->ariEndpoint);
//$ariAsteriskInformation = $ariAsterisk->get_asterisk_info();


?>