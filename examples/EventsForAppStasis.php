<?php
/**
 * Created by PhpStorm.
 * User: WildCard
 * Date: 9/6/14
 * Time: 12:38 AM
 */

require_once "../vendor/autoload.php";
require_once "examples-config.php";

class EventsForAppStasis
{

    private $conn;
    private $events;

    public function __construct()
    {
        //   $phpariObject = new phpari("ariuser", "4r1u53r", "hello-world", "178.62.19.221", 8088, "/ari");
        $conn     = new phpari(ARI_USERNAME, ARI_PASSWORD, "hello-world", ARI_SERVER, ARI_PORT, ARI_ENDPOINT);
        $this->events = new events($this->conn);


    }

    public function handlers()
    {
        try {
            $this->conn->stasisClient->on("request", function ($headers) {
                $this->conn->stasisLogger->notice("Request received!");
            });

            $this->conn->stasisClient->on("handshake", function () {
                $this->conn->stasisLogger->notice("Handshake received!");
            });

            $this->conn->stasisClient->on("message", function ($message) {


                $this->stasisLogger->notice(json_encode($message));
                $this->stasisLogger->notice($this->events->events('hello-world'));
            });

        } catch (Exception $e) {
            echo $e->getMessage();
            exit(99);
        }
    }

    public function execute()
    {
        try {
            $this->conn->stasisClient->open();
            $this->conn->stasisLoop->run();

        } catch (Exception $e) {
            echo $e->getMessage();
            exit(99);
        }
    }

}

$eventForApp = new EventsForAppStasis();
$eventForApp->handlers();
$eventForApp->execute();

exit(0);