<?php
/**
 * Created by PhpStorm.
 * User: WildCard
 * Date: 9/6/14
 * Time: 12:38 AM
 */

require_once "../vendor/autoload.php";

class BasicAriConnector
{
    public function __construct()
    {
        //   $phpariObject = new phpari("ariuser", "4r1u53r", "hello-world", "178.62.19.221", 8088, "/ari");
        $phpariObject = new phpari("lnotik", "hwab7bk", "hello-world", "178.62.185.100", 8088, "/ari");

        $this->ariEndpoint   = $phpariObject->ariEndpoint;
        $this->stasisClient  = $phpariObject->stasisClient;
        $this->stasisLoop    = $phpariObject->stasisLoop;
        $this->stasisLogger  = $phpariObject->stasisLogger;


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

            $this->stasisClient->on("message", function ($message) {

                print_r($message->getData());

                $this->stasisLogger->notice($message->getData());
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

$basicAriClient = new BasicAriConnector();

/**
 * Get some basic information from ARI
 */
$ariAsterisk = new asterisk($basicAriClient->ariEndpoint);
$ariAsteriskInformation = $ariAsterisk->get_asterisk_info();
$ariChannels = new channels($basicAriClient);
$ariAsteriskChannels = $ariChannels->channel_list();

//print_r($ariAsteriskInformation);
//print_r($ariAsteriskChannels);

$basicAriClient->handlers();
$basicAriClient->execute();

exit(0);