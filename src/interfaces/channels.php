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

            $uri = "/channels";
            $result = $this->pestObject->get($uri);
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

            if (is_null($endpoint))
                throw new Exception("End point not provided or is null", 503);

            if (is_null($data))
                throw new Exception("End point not provided or is null", 503);

            $originateData = array();
            $originateData['endpoint']  = $endpoint;
            $originateData = array_merge($originateData, $data);
            $originateData['variables'] = $variables;


            $uri = "/channels";
            $result = $this->pestObject->post($uri, $originateData);
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



            if (is_null($channel_id))
                throw new Exception("PEST Object not provided or is null", 503);

            $result = $this->pestObject->get("/channels/" . $channel_id);
            return $result;

        } catch (Exception $e) {
            return false;
        }
    }


    /**
     * Delete / hangup  an  active channel
     *
     * @param null (string) $channel_id - channel identifier to query
     * @return bool - false on success, Integer or True on failure
     */
    public function channel_delete($channel_id = null)
    {
        try {

            // $result = false;

            if (is_null($channel_id))
                throw new Exception("Channel ID not provided or is null", 503);

            $result = $this->pestObject->delete("/channels/" . $channel_id);
            return $result;

        } catch (Exception $e) {
            return false;
        }
    }


    /**
     * continue  an  active channel
     *
     * @param null (string) $channel_id - channel identifier to query
     * @return bool - false on success, Integer or True on failure
     */
    public function channel_continue($channel_id = null)
    {
        try {

            // $result = false;

            if (is_null($channel_id))
                throw new Exception("Channel ID not provided or is null", 503);

            $result = $this->pestObject->post("/channels/" . $channel_id ."/continue");
            return $result;



        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Answer   an  active channel
     *
     * @param null (string) $channel_id - channel identifier to query
     * @return bool - false on success, Integer or True on failure
     */
    public function channel_answer($channel_id = null)
    {
        try {

            // $result = false;

            if (is_null($channel_id))
                throw new Exception("Channel ID not provided or is null", 503);

            $result = $this->pestObject->post("/channels/" . $channel_id ."/answer",NULL);
            return $result;



        } catch (Exception $e) {
            return false;
        }
    }


    /**
     * Indicate ringing to an active channel
     *
     *
     * @param null $channel_id
     * @return bool
     */
    public function channel_ringing_start($channel_id = null)
    {
        try {

            // $result = false;

            if (is_null($channel_id))
                throw new Exception("Channel ID not provided or is null", 503);

            $result = $this->pestObject->post("/channels/" . $channel_id ."/ring",NULL);
            return $result;



        } catch (Exception $e) {
            return false;
        }
    }


    public function channel_ringing_stop($channel_id = null)
    {
        try {

            // $result = false;

            if (is_null($channel_id))
                throw new Exception("Channel ID not provided or is null", 503);

            $result = $this->pestObject->delete("/channels/" . $channel_id ."/ring");
            return $result;



        } catch (Exception $e) {
            return false;
        }
    }


    /**
     *   Send provided DTMF to a given channel.
     *
     *   dtmf:     "string - DTMF To send".
     *   before:   "int - Amount of time to wait before DTMF digits (specified in milliseconds) start"
     *   between:  "int = 100 - Amount of time in between DTMF digits (specified in milliseconds)"
     *   duration: "int = 100 - Length of each DTMF digit (specified in milliseconds)"
     *   after:    "int - Amount of time to wait after DTMF digits (specified in milliseconds) end"
     *
     *
     *
     * @param null $channel_id
     * @param null $dtmf
     * @param int $before
     * @param int $between
     * @param int $duration
     * @param int $after
     * @return bool
     */
    public function channel_send_dtmf($channel_id = null , $dtmf  = null , $before = 1000 , $between = 100, $duration = 100 , $after = 1000 )
    {
        try {

            $result = false;

            if (is_null($channel_id))
                throw new Exception("Channel ID not provided or is null", 503);

            if (is_null($dtmf))
                throw new Exception("The dtmfObject not provided or is null", 503);
            //TODO: Fill in the gaps!


            $dtmfObject = array(
                'dtmf'     => $dtmf,
                'before'   => $before,
                'between'  => $between,
                'duration' => $duration,
                'after'    => $after
            );




            $uri = "/channels/".$channel_id."/dtmf";
            $result = $this->pestObject->post($uri, $dtmfObject);

            return $result;

        } catch (Exception $e) {
            return false;
        }
    }


    /**
     * Mute a channel
     *
     *
     * @param null $channel_id
     * @param string $direction
     * @return bool
     */
    public function channel_mute($channel_id = null,$direction  = 'both')
    {
        try {

            // $result = false;

            if (is_null($channel_id))
                throw new Exception("Channel ID not provided or is null", 503);

            $cDirection = array('direction' => $direction);


            $result = $this->pestObject->post("/channels/" . $channel_id ."/mute",$cDirection);
            return $result;



        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Unmute a channel
     *
     *
     * @param null $channel_id
     * @param string $direction
     * @return bool
     */
    public function channel_unmute($channel_id = null,$direction  = 'both')
    {
        try {

            // $result = false;

            if (is_null($channel_id))
                throw new Exception("Channel ID not provided or is null", 503);

            $cDirection = array('direction' => $direction);


            $result = $this->pestObject->delete("/channels/" . $channel_id ."/unmute",$cDirection);
            return $result;



        } catch (Exception $e) {
            return false;
        }
    }


    /**
     * Hold a channel
     *
     * @param null $channel_id
     * @return bool
     */
    public function channel_hold($channel_id = null)
    {
        try {

            // $result = false;

            if (is_null($channel_id))
                throw new Exception("Channel ID not provided or is null", 503);




            $result = $this->pestObject->post("/channels/" . $channel_id ."/hold",null);
            return $result;



        } catch (Exception $e) {
            return false;
        }
    }


    /**
     * Remove a channel from hold.
     *
     * @param null $channel_id
     * @return bool
     */
    public function channel_unhold($channel_id = null)
    {
        try {

            // $result = false;

            if (is_null($channel_id))
                throw new Exception("Channel ID not provided or is null", 503);


            $result = $this->pestObject->delete("/channels/" . $channel_id ."/hold");
            return $result;



        } catch (Exception $e) {
            return false;
        }
    }


    /**

     * Play music on hold to a channel. Using media operations such as /play on a channel
     * playing MOH in this manner will suspend MOH without resuming automatically.
     * If continuing music on hold is desired, the stasis application must reinitiate music on hold.
     *
     * @param null $channel_id
     * @param null $mohClass
     * @return bool
     */
    public function channel_moh_start($channel_id = null , $mohClass = null)
    {
        try {

            // $result = false;

            if (is_null($channel_id))
                throw new Exception("Channel ID not provided or is null", 503);
            if (is_null($mohClass))
                throw new Exception("mohClass not provided or is null", 503);


            $postMoh = array('mohClass' => $mohClass);
            $result  = $this->pestObject->post("/channels/" . $mohClass ."/moh",$postMoh);
            return $result;

        } catch (Exception $e) {
            return false;
        }
    }




    public function channel_moh_stop($channel_id = null)
    {
        try {

            // $result = false;

            if (is_null($channel_id))
                throw new Exception("Channel ID not provided or is null", 503);

            $result  = $this->pestObject->delete("/channels/" . $channel_id ."/moh");
            return $result;

        } catch (Exception $e) {
            return false;
        }
    }


    /**
     * Play silence to a channel.
     * Using media operations such as /play on a channel playing silence in this manner will
     * suspend silence without resuming automatically.
     *
     * @param null $channel_id
     * @return bool
     */
    public function channel_silence_start($channel_id = null)
    {
        try {



            if (is_null($channel_id))
                throw new Exception("Channel ID not provided or is null", 503);

            $result  = $this->pestObject->post("/channels/" . $channel_id ."/silence",null);
            return $result;

        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Stop playing silence to a channel
     *
     *
     * @param null $channel_id
     * @return bool
     */
    public function channel_silence_stop($channel_id = null)
    {
        try {

            if (is_null($channel_id))
                throw new Exception("Channel ID not provided or is null", 503);

            $result  = $this->pestObject->delete("/channels/" . $channel_id ."/silence");
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
                "media"      => $media,
                "lang"       => $lang,
                "offsetms"   => $offsetms,
                "skipms"     => $skipms,
                "playbackId" => $playbackid
            );
            $result = $this->pestObject->post("/channels/" . $channel_id . "/play", $postData);

            return $result;

        } catch (Exception $e) {
            return false;
        }
    }


    /**
     * Start a recording. Record audio from a channel.
     * Note that this will not capture audio sent to the channel.
     * The bridge itself has a record feature if that's what you want.
     *
     * @param null $channel_id        - (required) ChannelID
     * @param null $name string       - (required) Recording's filename
     * @param null $format string     - (required) Format to encode audio in
     * @param int $maxDurationSeconds - Maximum duration of the recording, in seconds. 0 for no limit
     * @param int $maxSilenceSeconds  - Maximum duration of silence, in seconds. 0 for no limit
     * @param string $ifExists        - Action to take if a recording with the same name already exists.
     * @param bool $beep              - Play beep when recording begins
     * @param string $terminateOn     - DTMF input to terminate recording
     * @return bool
     */
    public function channel_record(
        $channel_id         = null ,
        $name               = null ,
        $format             = null,
        $maxDurationSeconds = 0 ,
        $maxSilenceSeconds  = 0,
        $ifExists           = 'fail' ,
        $beep               = true ,
        $terminateOn        = "none"
    )
    {
        try {

            if (is_null($channel_id))
                throw new Exception("Channel ID not provided or is null", 503);
            if (is_null($name))
                throw new Exception("Recording's filename is not provided or is null", 503);
            if (is_null($format))
                throw new Exception("Format to encode audio is not provided or is null", 503);

            $postData = array(
                "name"               => $name,
                "format"             => $format,
                "maxDurationSeconds" => $maxDurationSeconds,
                "maxSilenceSeconds"  => $maxSilenceSeconds,
                "ifExists"           => $ifExists,
                "beep"               => $beep,
                "terminateOn"        => $terminateOn

            );

            $result = $this->pestObject->post("/channels/" . $channel_id . "/record", $postData);
            return $result;

        }
        catch(Exception $e){
            return false;
        }

    }


    /**
     *
     * Get the value of a channel variable or function
     *
     *
     * @param null $channel_id
     * @param null $variable
     * @return bool
     */
    public function channel_get_variable($channel_id = null,$variable = null)
    {
        try {

            if (is_null($channel_id))
                throw new Exception("Channel ID not provided or is null", 503);
            if (is_null($variable))
                throw new Exception("The variable  is not provided or is null", 503);


            $getObject = array( 'variable'=>$variable );
            $result  = $this->pestObject->get("/channels/" . $channel_id ."/variable",$getObject);
            return $result;

        } catch (Exception $e) {
            return false;
        }
    }


    /**
     *
     * Set the value of a channel variable or function.
     *
     *
     * @param null $channel_id
     * @param null $variable
     * @param null $value
     * @return bool
     */
    public function channel_set_variable($channel_id = null , $variable = null , $value = null)
    {
        try {

            if (is_null($channel_id))
                throw new Exception("Channel ID not provided or is null", 503);
            if (is_null($variable))
                throw new Exception("The variable  is not provided or is null", 503);

            $postObject = array( 'variable'=>$variable , 'value' => $value );
            $result  = $this->pestObject->post("/channels/" . $channel_id ."/variable",$postObject);
            return $result;

        } catch (Exception $e) {
            return false;
        }
    }


    /**
     *
     * Start snooping. Snoop (spy/whisper) on a specific channel
     *
     * @param null   $channel_id - Channel ID
     * @param string $spy        - Direction of audio to spy on
     * @param string $whisper    - Direction of audio to whisper into
     * @param null   $app        - (required) Application the snooping channel is placed into
     * @param null   $appArgs    - The application arguments to pass to the Stasis application
     * @param null   $snoopId    - Unique ID to assign to snooping channel
     * @return bool
     */
    public function channel_snoop_start(
        $channel_id = null,
        $spy        = "none",
        $whisper    = "none",
        $app        = null,
        $appArgs    = null,
        $snoopId    = null

    )
    {
        try {

            if (is_null($channel_id))
                throw new Exception("Channel ID not provided or is null", 503);
            if (is_null($app))
                throw new Exception("The application the snooping channel is placed into is not provided or is null", 503);

            $postObject = array(
                'spy'     => $spy,
                'whisper' => $whisper,
                'app'     => $app,
                'appArgs' => $appArgs,
                'snoopId' => $snoopId
            );

            $result  = $this->pestObject->post("/channels/" . $channel_id ."/snoop",$postObject);
            return $result;

        } catch (Exception $e) {
            return false;
        }
    }


    /**
     *
     * Start snooping. Snoop (spy/whisper) on a specific channel.
     *
     * @param null   $channel_id - Channel ID
     * @param string $spy        - Direction of audio to spy on
     * @param string $whisper    - Direction of audio to whisper into
     * @param null   $app        - (required) Application the snooping channel is placed into
     * @param null   $appArgs    - The application arguments to pass to the Stasis application
     * @param null   $snoopId    - Unique ID to assign to snooping channel
     * @return bool
     */
    public function channel_snoop_start_id(
        $channel_id = null,
        $spy        = "none",
        $whisper    = "none",
        $app        = null,
        $appArgs    = null,
        $snoopId    = null

    )
    {
        try {

            if (is_null($channel_id))
                throw new Exception("Channel ID not provided or is null", 503);
            if (is_null($app))
                throw new Exception("The application the snooping channel is placed into is not provided or is null", 503);

            $postObject = array(
                'spy'     => $spy,
                'whisper' => $whisper,
                'app'     => $app,
                'appArgs' => $appArgs,

            );

            $result  = $this->pestObject->post("/channels/" . $channel_id ."/snoop/".$snoopId,$postObject);
            return $result;

        } catch (Exception $e) {
            return false;
        }
    }


    /**
     * TODO : ASK NIR ABOUT IT
     *
     *
     * @return bool
     */
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