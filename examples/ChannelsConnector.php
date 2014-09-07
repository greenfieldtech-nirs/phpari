<?php
/**
 * Created by PhpStorm.
 * User: WildCard
 * Date: 9/6/14
 * Time: 12:38 AM
 */

require_once "../vendor/autoload.php";
require_once "../phpari.php";
require_once "../src/interfaces/asterisk.php";
require_once "../src/interfaces/channels.php";


class ChannelsConnector
{
    public function __construct()
    {
        //   $phpariObject = new phpari("ariuser", "4r1u53r", "hello-world", "178.62.19.221", 8088, "/ari");
        $phpariObject = new phpari("lnotik", "hwab7bk", "hello-world", "178.62.185.100", 8088, "/ari");




        $this->stasisClient  = $phpariObject->stasisClient;
        $this->stasisLoop    = $phpariObject->stasisLoop;
        $this->stasisLogger  = $phpariObject->stasisLogger;
        $this->ariEndpoint   = $phpariObject->ari_endpoint;





    }

    public function handlers()
    {
        try {
            $this->stasisClient->on("request", function ($headers) {
                $this->stasisLogger->notice("Request received!");
            });

            $this->stasisClient->on("handshake", function () {
                $this->stasisLogger->notice("Handshake received!");
            });
        } catch (Exception $e) {
            echo $e->getMessage();
            exit(99);
        }
    }

    public function execute()
    {
        try {
            $this->stasisClient->open();
            $this->stasisLoop->run();

        } catch (Exception $e) {
            echo $e->getMessage();
            exit(99);
        }
    }



}


$chClient = new  ChannelsConnector();
$channel  = new channels($chClient->ariEndpoint);
//
//
//
//
echo "<pre>";

//$response    =  $channel->channel_originate(
//    'SIP/7002',
//    $data    =  array(
//        "extension"      => "7002",
//        "context"        =>'from-phone',
//        "priority"       => 1,
//        "app"            => "",
//        "appArgs"        => "",
//        "callerid"       => "111",
//        "timeout"        => -1,
//        "channelId"      => '324234',
//        "otherChannelId" => ""
//    ),
//    $valiables = array("var1"=>"cool")
//);
//
//
//print_r($response);



//$response = $channel->channel_ringing_stop('4354354354345');

//print_r($response);

//echo "</pre>";


$chClient->handlers();
$chClient->execute();
//
//
//$chClient->channel_answer('1410090433.174');

/**
 * Get some basic information from ARI
 */
//$ariAsterisk = new asterisk($basicAriClient->ariEndpoint);
//$ariAsteriskInformation = $ariAsterisk->get_asterisk_info();





exit(0);