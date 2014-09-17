<?php

/**
 * Created by PhpStorm.
 * User: WildCard
 * Date: 9/6/14
 * Time: 12:08 AM
 */

class events // extends phpari
{
    function __construct($connObject = null)
    {
        try {

            if (is_null($connObject)  || is_null($connObject->ariEndpoint))
                throw new Exception("Missing PestObject or empty string", 503);
            $this->pestObject = $connObject->ariEndpoint;
        } catch (Exception $e) {
            die("Exception raised: " . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine());
        }
    }
    /**
     * GET /events
     * WebSocket connection for events.
     */
    public function  events($app)
    {
        try {

            if (is_null($app))
                throw new Exception("App name is  not provided or is null", 503);

            $uri = "/events";
            $getObj = array('app'=>$app);

            $result = $this->pestObject->get($uri,$getObj);
            return $result;



        } catch (Exception $e) {
            return false;
        }
    }

    /**
     *
     * POST /events/user/{eventName}
     *
     *   Generate a user event.
     *
     * @param string $eventName     - Event name
     * @param string $application   - (required) The name of the application that will receive this event
     * @param string $channelID     - Name of the Channel     - source part
     * @param string $bridge        - Name of the  bridge     - source part
     * @param string $endpoint      - Name of the endpoints   - source part
     * @param string $deviceName    - The name of the device  - source part
     * @param array  $variables      - Ex. array("key1" => "value1" ,  "key2" => "value2")
     * @return bool
     */
    public function event_generate($eventName   = null,
                                   $application = null,
                                   $channelID   = null,
                                   $bridge      = null,
                                   $endpoint    = null,
                                   $deviceName  = null,
                                   $variables   = array()){

        try {

            if (is_null($application))
                throw new Exception("Application name is  not provided or is null", 503);
            if (is_null($eventName))
                throw new Exception("Event name is  not provided or is null", 503);


            $uri     = "/events/user/".$eventName;
            $postObj = array(
                'application'     => $application,
                'source'          => array(
                    'channel'     => $channelID,
                    'bridge'      => $bridge,
                    'endpoint'    => $endpoint,
                    'deviceState' => $deviceName
                ),
                'variables'       =>  $variables
            );

            $result = $this->pestObject->post($uri,$postObj);
            return $result;



        } catch (Exception $e) {
            return false;
        }


    }



}