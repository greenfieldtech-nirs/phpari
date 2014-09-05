<?php

/**
 * Created by PhpStorm.
 * User: WildCard
 * Date: 9/6/14
 * Time: 12:08 AM
 */
class channels extends phpari
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

    /**
     * Get the current list of active channels
     *
     * @return JSON|bool - false for a failure, JSON object for all other results
     */
    public function channel_list()
    {
        try {

            $result = false;

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);

            //TODO: Fill in the gaps!

            return $result;

        } catch (Exception $e) {
            return false;
        }
    }


    /**
     * Originate a call on a channel
     *
     * @param null (string) $endpoint - endpoint to originate the call to, eg: SIP/alice
     * @param null (JSON Object) $data - originate data
     * @param null (JSON Object) $variables - originate assigned variables
     * @return bool - false on success, Integer or True on failure
     *
     * $data structure:
     *
     * {
     *      "extension": (String) "The extension to dial after the endpoint answers",
     *      "context": (String) "The context to dial after the endpoint answers. If omitted, uses 'default'",
     *      "priority": (Long) "he priority to dial after the endpoint answers. If omitted, uses 1",
     *      "app": (String) "The application that is subscribed to the originated channel, and passed to the Stasis application",
     *      "appArgs": (String) "The application arguments to pass to the Stasis application",
     *      "callerid": (String) "CallerID to use when dialing the endpoint or extension",
     *      "timeout": (Integer) "Timeout (in seconds) before giving up dialing, or -1 for no timeout",
     *      "channelId": (String) "The unique id to assign the channel on creation",
     *      "otherChannelId": (String) "The unique id to assign the second channel when using local channels"
     * }
     *
     * $variables structure:
     *
     * {
     *      "variable_name": "value",
     * }
     *
     * eg.
     *
     * {
     *      "CALLERID(name): "Mark Spencer"
     * }
     *
     */

    public function channel_originate($endpoint = null, $data = null, $variables = null)
    {
        try {

            $result = false;

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);

            //TODO: Fill in the gaps!

            return $result;

        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get active channel details for an existing channel
     *
     * @param null (string) $channel_id - channel identifier to query
     * @return bool - false on success, Integer or True on failure
     */
    public function channel_get_details($channel_id = null)
    {
        try {

            $result = false;

            if (is_null($channel_id))
                throw new Exception("PEST Object not provided or is null", 503);

            //TODO: Fill in the gaps!

            $result = $this->pestObject->get("/channels/" . $channel_id);
            return $result;

        } catch (Exception $e) {
            return false;
        }
    }

    public function channel_delete($channel_id = null)
    {
        try {

            $result = false;

            if (is_null($channel_id))
                throw new Exception("Channel ID not provided or is null", 503);

            $result = $this->pestObject->delete("/channels/" . $channel_id);
            return $result;

        } catch (Exception $e) {
            return false;
        }
    }

    public function channel_continue()
    {
        try {

            $result = false;

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);

            //TODO: Fill in the gaps!

            return $result;

        } catch (Exception $e) {
            return false;
        }
    }

    public function channel_answer()
    {
        try {

            $result = false;

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);

            //TODO: Fill in the gaps!

            return $result;

        } catch (Exception $e) {
            return false;
        }
    }

    public function channel_ringing_start()
    {
        try {

            $result = false;

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);

            //TODO: Fill in the gaps!

            return $result;

        } catch (Exception $e) {
            return false;
        }
    }

    public function channel_ringing_stop()
    {
        try {

            $result = false;

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);

            //TODO: Fill in the gaps!

            return $result;

        } catch (Exception $e) {
            return false;
        }
    }

    public function channel_send_dtmf()
    {
        try {

            $result = false;

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);

            //TODO: Fill in the gaps!

            return $result;

        } catch (Exception $e) {
            return false;
        }
    }

    public function channel_mute()
    {
        try {

            $result = false;

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);

            //TODO: Fill in the gaps!

            return $result;

        } catch (Exception $e) {
            return false;
        }
    }

    public function channel_unmute()
    {
        try {

            $result = false;

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);

            //TODO: Fill in the gaps!

            return $result;

        } catch (Exception $e) {
            return false;
        }
    }

    public function channel_hold()
    {
        try {

            $result = false;

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);

            //TODO: Fill in the gaps!

            return $result;

        } catch (Exception $e) {
            return false;
        }
    }

    public function channel_unhold()
    {
        try {

            $result = false;

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);

            //TODO: Fill in the gaps!

            return $result;

        } catch (Exception $e) {
            return false;
        }
    }

    public function channel_moh_start()
    {
        try {

            $result = false;

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);

            //TODO: Fill in the gaps!

            return $result;

        } catch (Exception $e) {
            return false;
        }
    }

    public function channel_moh_stop()
    {
        try {

            $result = false;

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);

            //TODO: Fill in the gaps!

            return $result;

        } catch (Exception $e) {
            return false;
        }
    }

    public function channel_silence_start()
    {
        try {

            $result = false;

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);

            //TODO: Fill in the gaps!

            return $result;

        } catch (Exception $e) {
            return false;
        }
    }

    public function channel_silence_stop()
    {
        try {

            $result = false;

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);

            //TODO: Fill in the gaps!

            return $result;

        } catch (Exception $e) {
            return false;
        }
    }

    public function channel_playback($channel_id = null, $media = null, $lang = "en", $offsetms = 0, $skipms = 3000, $playbackid = null)
    {
        try {

            $result = false;

            if (is_null($media))
                throw new Exception("media URI not provided or is null", 503);

            if (is_null($channel_id))
                throw new Exception("channel ID not provided or is null", 503);

            //TODO: Fill in the gaps!

            $postData = array(
                "media" => $media,
                "lang" => $lang,
                "offsetms" => $offsetms,
                "skipms" => $skipms,
                "playbackId" => $playbackid
            );
            $result = $this->pestObject->post("/channels/" . $channel_id . "/play", $postData);

            return $result;

        } catch (Exception $e) {
            return false;
        }
    }

    public function channel_record()
    {
        try {

            $result = false;

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);

            //TODO: Fill in the gaps!

            return $result;

        } catch (Exception $e) {
            return false;
        }
    }

    public function channel_get_variable()
    {
        try {

            $result = false;

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);

            //TODO: Fill in the gaps!

            return $result;

        } catch (Exception $e) {
            return false;
        }
    }

    public function channel_set_variable()
    {
        try {

            $result = false;

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);

            //TODO: Fill in the gaps!

            return $result;

        } catch (Exception $e) {
            return false;
        }
    }

    public function channel_snoop_start()
    {
        try {

            $result = false;

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);

            //TODO: Fill in the gaps!

            return $result;

        } catch (Exception $e) {
            return false;
        }
    }

    public function channel_snoop_stop()
    {
        try {

            $result = false;

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);

            //TODO: Fill in the gaps!

            return $result;

        } catch (Exception $e) {
            return false;
        }
    }

}