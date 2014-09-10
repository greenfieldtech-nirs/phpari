<?php

/**
 * Created by PhpStorm.
 * User: WildCard
 * Date: 9/6/14
 * Time: 12:08 AM
 */

class recordings extends phpari
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
     * GET /recordings/stored
     * List recordings that are complete.
     * @return bool
     */
    public function   recording_list()
    {
        try {

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);

            $uri = "/recordings/stored";
            $result = $this->pestObject->get($uri);
            return $result;



        } catch (Exception $e) {
            return false;
        }
    }



}






