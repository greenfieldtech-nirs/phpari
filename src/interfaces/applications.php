<?php

/**
 * Created by PhpStorm.
 * User: WildCard
 * Date: 9/6/14
 * Time: 12:08 AM
 */




class applications // extends phpari
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
     * @return bool
     */
    public function   applications_list()
    {
        try {

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);

            $uri = "/applications";
            $result = $this->pestObject->get($uri);
            return $result;



        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param null $applicationName
     * @return mixed
     */
    public function  application_details($applicationName = null)
    {
        try {

            if (is_null($applicationName))
                throw new Exception("Application name not provided or is null", 503);

            $uri = "/applications";
            $result = $this->pestObject->get($uri);
            return $result;

        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param null $applicationName
     * @param null $bridge
     * @param null $channelId
     * @param null $endpoint
     * @param null $deviceState
     * @return bool
     */
    public  function application_subscribe($applicationName = null ,$bridge = null , $channelId = null, $endpoint = null , $deviceState = null)
    {
        try {

            if (is_null($applicationName))
                throw new Exception("Application name not provided or is null", 503);
            if (is_null($bridge))
                throw new Exception("Bridge id not provided or is null", 503);
            if (is_null($channelId))
                throw new Exception("Channel id not provided or is null", 503);

            if (is_null($endpoint))
                throw new Exception("End point  not provided or is null", 503);

            if (is_null($deviceState))
                throw new Exception("Device state name is not provided or is null", 503);



            $postObjParams = array(
                'channel'     => $channelId,
                'bridge'      => $bridge,
                'endpoint'    => $endpoint,
                'deviceState' => $deviceState
            );


            $postObj['eventSource'] = $postObjParams;



            $uri = "/applications/".$applicationName."/subscription";
            $result = $this->pestObject->post($uri,$postObj);
            return $result;

        } catch (Exception $e) {
            return false;
        }

    }


    /**
     * @param null $applicationName
     * @param null $bridge
     * @param null $channelId
     * @param null $endpoint
     * @param null $deviceState
     * @return bool
     */
    public  function application_unsubscribe($applicationName = null ,$bridge = null , $channelId = null, $endpoint = null , $deviceState = null)
    {
        try {

            if (is_null($applicationName))
                throw new Exception("Application name not provided or is null", 503);
            if (is_null($bridge))
                throw new Exception("Bridge id not provided or is null", 503);
            if (is_null($channelId))
                throw new Exception("Channel id not provided or is null", 503);

            if (is_null($endpoint))
                throw new Exception("End point  not provided or is null", 503);

            if (is_null($deviceState))
                throw new Exception("Device state name is not provided or is null", 503);



            $postObjParams = array(
                'channel'     => $channelId,
                'bridge'      => $bridge,
                'endpoint'    => $endpoint,
                'deviceState' => $deviceState
            );


            $postObj['eventSource'] = $postObjParams;



            $uri = "/applications/".$applicationName."/subscription";
            $result = $this->pestObject->delete($uri,$postObj);
            return $result;

        } catch (Exception $e) {
            return false;
        }

    }
}





