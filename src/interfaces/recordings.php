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


    /**
     * GET /recordings/stored/{recordingName}
     * Get a stored recording's details.
     * @param null $recordingName
     * @return bool
     */
    public function recording_detail($recordingName = null)
    {
        try {

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);

            $uri = "/recordings/stored/".$recordingName;
            $result = $this->pestObject->get($uri);
            return $result;

        } catch (Exception $e) {
            return false;
        }

    }

    public function delete_recording($recordingName = null)
    {
        try {

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);

            $uri = "/recordings/stored/".$recordingName;
            $result = $this->pestObject->delete($uri);
            return $result;



        } catch (Exception $e) {
            return false;
        }
    }
    /**
     *
     * POST /recordings/stored/{recordingName}/copy
     * Copy a stored recording.
     *
     * @param null $recordingName
     * @param null $destinationRecordingName
     * @return bool
     */
    public function recording_stored_copy($recordingName = null , $destinationRecordingName= null)
    {
        try {


            if (is_null($recordingName))
                throw new Exception("Recording name is not provided or is null", 503);
            if (is_null($destinationRecordingName))
                throw new Exception("Destination recording name  not provided or is null", 503);

            $uri     = "/recordings/stored/".$recordingName."/copy";
            $postOBJ = array(
                'destinationRecordingName' => $destinationRecordingName
            );


            $result = $this->pestObject->post($uri,$postOBJ);
            return $result;



        } catch (Exception $e) {
            return false;
        }

    }

    /**
     * GET /recordings/live/{recordingName}
     * List live recordings.
     * @param null $recordingName
     * @return bool
     */
    public function recordings_live_list($recordingName = null)
    {
        try {


            if (is_null($recordingName))
                throw new Exception("Recording name is not provided or is null", 503);


            $uri     = "/recordings/live/".$recordingName;
            $result  = $this->pestObject->get($uri);
            return $result;



        } catch (Exception $e) {
            return false;
        }

    }

    /**
     *  DELETE /recordings/live/{recordingName}
     *  Stop a live recording and discard it.
     *
     * @param null $recordingName
     * @return bool
     */
    public function recordings_live_stop_n_discard($recordingName = null)
    {

        try {
            if (is_null($recordingName))
                throw new Exception("Recording name is not provided or is null", 503);

            $uri     = "/recordings/live/".$recordingName;
            $result  = $this->pestObject->delete($uri);
            return $result;

        } catch (Exception $e) {
            return false;
        }

    }


    /**
     *
     * POST /recordings/live/{recordingName}/stop
     * Stop a live recording and store it.
     *
     * @param null $recordingName
     * @return bool
     */
    public  function  recordings_live_stop_n_store($recordingName = null)
    {

        try {
            if (is_null($recordingName))
                throw new Exception("Recording name is not provided or is null", 503);

            $uri     = "/recordings/live/".$recordingName."/stop";
            $result  = $this->pestObject->delete($uri);
            return $result;

        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * POST /recordings/live/{recordingName}/pause
     *
     * Pause a live recording. Pausing a recording suspends silence detection,
     * which will be restarted when the recording is unpaused.
     * Paused time is not included in the accounting for maxDurationSeconds.
     *
     * @param null $recordingName
     * @return bool
     */
     public  function  recordings_live_pause($recordingName = null)
    {

        try {
            if (is_null($recordingName))
                throw new Exception("Recording name is not provided or is null", 503);

            $uri     = "/recordings/live/".$recordingName."/pause";
            $result  = $this->pestObject->post($uri);
            return $result;

        } catch (Exception $e) {
            return false;
        }
    }


    /**
     *  DELETE /recordings/live/{recordingName}/pause
     *  Unpause a live recording.
     *
     * @param null $recordingName
     * @return bool
     */
    public  function  recordings_live_unpause($recordingName = null)
    {

        try {
            if (is_null($recordingName))
                throw new Exception("Recording name is not provided or is null", 503);

            $uri     = "/recordings/live/".$recordingName."/pause";
            $result  = $this->pestObject->delete($uri);
            return $result;

        } catch (Exception $e) {
            return false;
        }
    }

    /**
     *  POST /recordings/live/{recordingName}/mute
     *  Mute a live recording. Muting a recording suspends silence detection, which will be restarted when the recording is unmuted.
     *
     * @param null $recordingName
     * @return bool
     */
    public  function  recordings_live_mute($recordingName = null)
    {

        try {
            if (is_null($recordingName))
                throw new Exception("Recording name is not provided or is null", 503);

            $uri     = "/recordings/live/".$recordingName."/mute";
            $result  = $this->pestObject->post($uri);
            return $result;

        } catch (Exception $e) {
            return false;
        }
    }



    /**
     *  DELETE /recordings/live/{recordingName}/mute
     *  Unmute a live recording.
     *
     * @param null $recordingName
     * @return bool
     */
    public  function  recordings_live_unmute($recordingName = null)
    {

        try {
            if (is_null($recordingName))
                throw new Exception("Recording name is not provided or is null", 503);

            $uri     = "/recordings/live/".$recordingName."/mute";
            $result  = $this->pestObject->delete($uri);
            return $result;

        } catch (Exception $e) {
            return false;
        }
    }




}






