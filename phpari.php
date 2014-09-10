<?php
/**
 * Created by PhpStorm.
 * User: WildCard
 * Date: 9/6/14
 * Time: 12:06 AM
 */


require_once "src/interfaces/bridges.php";

class phpari
{


    protected  $bridges_obj;
    protected  $connect;

//    protected $ariEndpoint;
//    protected $stasisLoop;
//    protected $stasisLogger;
//    protected $logWriter;
//    protected $stasisClient;

    /**
     * @param null $ariUsername
     * @param null $ariPassword
     * @param null $stasisApplication
     * @param string $ariServer
     * @param int $ariPort
     * @param string $ariEndpoint
     *
     * Returns an array containing 4 objects: WebSocket, Pest, EventLoopFactory, Logger
     */
    public function __construct($ariUsername = null, $ariPassword = null, $stasisApplication = null, $ariServer = "127.0.0.1", $ariPort = 8080, $ariEndpoint = "/ari")
    {
        try {
            if ((is_null($ariUsername)) || (!strlen($ariEndpoint)))
                throw new Exception("Missing ARI username or empty string", 503);

            if ((is_null($ariPassword)) || (!strlen($ariPassword)))
                throw new Exception("Missing ARI password or empty string", 503);

            if ((is_null($stasisApplication)) || (!strlen($stasisApplication)))
                throw new Exception("Missing ARI stasis application name or empty string", 503);




            $result  =  $this->connect($ariUsername, $ariPassword, $stasisApplication, $ariServer, $ariPort, $ariEndpoint);
           // $this->init();

            return $result;



        } catch (Exception $e) {
            die("Exception raised: " . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine());
        }

    }

//    private function  init()
//    {
//        $this->$bridges_obj = new bridges($this);
//    }



    private function connect($ariUsername, $ariPassword, $stasisApplication, $ariServer , $ariPort , $ariEndpoint)
    {

        try {

            $this->ariEndpoint = new PestJSON("http://" . $ariServer . ":" . $ariPort . $ariEndpoint);
            $this->ariEndpoint->setupAuth($ariUsername, $ariPassword, "basic");

            $this->stasisLoop = \React\EventLoop\Factory::create();

            $this->stasisLogger = new \Zend\Log\Logger();
            $this->logWriter = new Zend\Log\Writer\Stream("php://output");
            $this->stasisLogger->addWriter($this->logWriter);



            $this->stasisClient = new \Devristo\Phpws\Client\WebSocket("ws://" . $ariServer . ":" . $ariPort . "/ari/events?api_key=" . $ariUsername . ":" . $ariPassword . "&app=" . $stasisApplication, $this->stasisLoop, $this->stasisLogger);

            return array("stasisClient" => $this->stasisClient, "stasisLoop" => $this->stasisLoop, "stasisLogger" => $this->stasisLogger, "ariEndpoint" => $this->ariEndpoint);

        } catch (Exception $e) {
            die("Exception raised: " . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine());
        }

    }



    public function handlers()
    {
        try {

            if(! isset($this->stasisClient))
                throw new Exception('Stasis client not ready or missing ...');


            $this->stasisClient->on("request", function ($headers) {
                $this->stasisLogger->notice("Request received! headers are : " . json_encode($headers));
            });

            $this->stasisClient->on("handshake", function ($response) {
                $this->stasisLogger->notice("Handshake received! " . json_encode($response));
            });
        } catch (Exception $e) {
            echo $e->getMessage();
            exit(99);
        }
    }

    public function execute()
    {
        try {

            if(! isset($this->stasisClient))
                throw new Exception('Stasis client not ready or missing ...');
            if(! isset($this->stasisLoop))
                throw new Exception('Stasis loop not ready or missing ...');

            $this->stasisClient->open();
            $this->stasisLoop->run();

        } catch (Exception $e) {
            echo $e->getMessage();
            exit(99);
        }
    }







}

