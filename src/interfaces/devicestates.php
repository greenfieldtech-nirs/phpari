<?php

/**
 * Class devicestates
 */
class devicestates //extends phpari
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
     *
     *
     * @return bool
     */
    public function devicestates_list()
    {
        try {

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);

            $uri = "/deviceStates";
            $result = $this->pestObject->get($uri);
            return $result;



        } catch (Exception $e) {
            return false;
        }
    }


    /**
     *
     * GET /deviceStates/{deviceName}
     * Retrieve the current state of a device.
     */
    public function devicestate_currentstate($deviceName = null)
    {
        try {

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);


            $uri = "/deviceStates/".$deviceName;
            $result = $this->pestObject->get($uri);

            return $result;



        } catch (Exception $e) {
            return false;
        }
    }

    /**
     *
     *  PUT /deviceStates/{deviceName}
     *  Change the state of a device controlled by ARI.
     *  (Note - implicitly creates the device state).
     *
     *
     * @param null $deviceName
     * @param null $deviceState
     * @return bool
     */
    public function devicestate_changestate($deviceName = null, $deviceState = null)
    {
        try {

            if (is_null($deviceName))
                throw new Exception("Device name is not provided or is null", 503);
            if (is_null($deviceState))
                throw new Exception("Device state name is  not provided or is null", 503);


            $putObj = array(
                'deviceState' =>$deviceState
            );

            $uri    = "/deviceStates/".$deviceName;
            $result = $this->pestObject->put($uri,$putObj);

            return $result;

        } catch (Exception $e) {
            return false;
        }
    }


    /**
     *  DELETE /deviceStates/{deviceName}
     *  Destroy a device-state controlled by ARI.
     *
     * @param null $deviceName
     * @return bool
     */
    public function devicestate_deletestate($deviceName = null)
    {
        try {

            if (is_null($deviceName))
                throw new Exception("Device name is not provided or is null", 503);

            $uri    = "/deviceStates/".$deviceName;
            $result = $this->pestObject->delete($uri);

            return $result;

        } catch (Exception $e) {
            return false;
        }
    }





}


