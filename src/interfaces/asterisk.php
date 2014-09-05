<?php

/**
 * Created by PhpStorm.
 * User: WildCard
 * Date: 9/6/14
 * Time: 12:08 AM
 */
class asterisk extends phpari
{

    function __construct($pestObject = null)
    {
        try {

            if (is_null($pestObject))
                throw new Exception("Missing PestObject or empty string", 503);

            $this->pestObject = $pestObject;

        } catch (Exception $e) {
            die("Exception raised: " . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine());
        }
    }

    public function get_asterisk_info($filter = null) {

        try {

            $result = false;

            switch ($filter) {
                case "build":
                case "system":
                case "config":
                case "status":
                    break;
                default:
                    $filter = null;
                    break;
            }

            $uri = "/asterisk/info";
            $uri .= (!is_null($filter))?'?only='.$filter:'';

            $result = json_decode($this->pestObject->get($uri));

            return $result;

        } catch (Exception $e) {
            return false;
        }

    }

    public function get_global_variable($variable = null) {

        try {

            $result = false;

            if (is_null($variable))
                throw new Exception("Global variable name not provided or is null", 503);

            $uri = "/asterisk/variable?variable=" . $variable;

            $jsonResult = json_decode($this->pestObject->get($uri));

            $result = $jsonResult->value;

            return $result;

        } catch (Exception $e) {
            return false;
        }

    }

    public function set_global_variable($variable = null, $value = null) {

        try {

            $result = false;

            if (is_null($variable))
                throw new Exception("Global variable name not provided or is null", 503);

            if (is_null($value))
                throw new Exception("Global variable value not provided or is null", 503);

            $uri = "/asterisk/variable";
            $postData = array("variable"=>$variable, "value"=>$value);

            $result = $this->pestObject->post($uri, $postData);

            return $result;

        } catch (Exception $e) {
            return false;
        }

    }
}