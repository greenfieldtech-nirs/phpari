<?php

/**
 * Created by PhpStorm.
 * User: WildCard
 * Date: 9/6/14
 * Time: 12:08 AM
 */

class bridges extends phpari
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
     * GET /bridges
     * List all active bridges in Asterisk.
     * @return bool
     */
    public function   bridges_list()
    {
        try {

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);

            $uri = "/bridges";
            $result = $this->pestObject->get($uri);
            return $result;



        } catch (Exception $e) {
            return false;
        }
    }


    /**
     * POST /bridges
     * Create a new bridge.
     * This bridge persists until it has been shut down,
     * or Asterisk has been shut down.
     * @param null $type      - Comma separated list of bridge type attributes (mixing, holding, dtmf_events, proxy_media).
     * @param null $bridgeId  - Unique ID to give to the bridge being created.
     * @param null $name      - Name to give to the bridge being created.
     * @return bool
     */
    public  function bridge_create($type = null,$bridgeId=null,$name = null)
    {

        try {

            if (is_null($type))
                throw new Exception("Type not provided or is null", 503);

            if (is_null($bridgeId))
                throw new Exception("BridgeID not provided or is null", 503);
            if (is_null($name))
                throw new Exception("Name not provided or is null", 503);


            $uri = "/bridges";

            $postOBJ = array(
                'type'     => $type ,
                'bridgeId' => $bridgeId ,
                'name'     => $name
            );


            $result = $this->pestObject->post($uri,$postOBJ);
            return $result;



        } catch (Exception $e) {
            return false;
        }

    }


    /**
     * POST /bridges
     * Create a new bridge or updates an existing one.
     * This bridge persists until it has been shut down,
     * or Asterisk has been shut down.
     *
     * @param null $type      - Comma separated list of bridge type attributes (mixing, holding, dtmf_events, proxy_media).
     * @param null $bridgeId  - Unique ID to give to the bridge being created.
     * @param null $name      - Name to give to the bridge being created.
     * @return bool
     */
    public  function bridge_create_update($type = null,$bridgeId=null,$name = null)
    {

        try {

            if (is_null($type))
                throw new Exception("Type not provided or is null", 503);

            if (is_null($bridgeId))
                throw new Exception("BridgeID not provided or is null", 503);
            if (is_null($name))
                throw new Exception("Name not provided or is null", 503);


            $uri = "/bridges/".$bridgeId;

            $postOBJ = array(
                'type'     => $type ,
                'name'     => $name
            );


            $result = $this->pestObject->post($uri,$postOBJ);
            return $result;



        } catch (Exception $e) {
            return false;
        }

    }

    /**
     * GET /bridges/{bridgeId}
     * Get bridge details.
     * @param null $bridgeId
     * @return bool
     */
    public  function bridge_details($bridgeId=null)
    {

        try {

            if (is_null($bridgeId))
                throw new Exception("BridgeID not provided or is null", 503);

            $uri    = "/bridges/".$bridgeId;
            $result = $this->pestObject->get($uri);
            return $result;

        } catch (Exception $e) {
            return false;
        }

    }


    /**
     * DELETE /bridges/{bridgeId}
     * Shut down a bridge.
     * If any channels are in this bridge,
     * they will be removed and resume whatever they were doing beforehand.
     * @param null $bridgeId
     * @return bool
     */
    public  function bridge_delete($bridgeId=null)
    {

        try {

            if (is_null($bridgeId))
                throw new Exception("BridgeID not provided or is null", 503);

            $uri    = "/bridges/".$bridgeId;
            $result = $this->pestObject->delete($uri);
            return $result;

        } catch (Exception $e) {
            return false;
        }

    }


    /**
     *
     * POST /bridges/{bridgeId}/addChannel
     * Add a channel to a bridge.
     *
     * @param null $bridgeId  - Bridge's id
     * @param null $channel   - (required) Ids of channels to add to bridge
     * @param null $role      - Channel's role in the bridge
     * @return bool
     */
    public  function bridge_addchannel($bridgeId=null , $channel = null , $role = null)
    {

        try {

            if (is_null($bridgeId))
                throw new Exception("BridgeID is not provided or is null", 503);

            if (is_null($channel))
                throw new Exception("Channel is not provided or is null", 503);
            if (is_null($role))
                throw new Exception("Role is  not provided or is null", 503);


            $postObj = array(
                'channel' => $channel,
                'role'    => $role
            );


            $uri    = "/bridges/".$bridgeId."/addChannel";
            $result = $this->pestObject->post($uri,$postObj);
            return $result;

        } catch (Exception $e) {
            return false;
        }

    }
    /**
     *
     * POST /bridges/{bridgeId}/addChannel
     * Remove a channel to a bridge.
     *
     * @param null $bridgeId  - Bridge's id
     * @param null $channel   - (required) Ids of channels to add to bridge
     * @return bool
     */
    public  function bridge_remove_channel($bridgeId=null , $channel = null)
    {

        try {

            if (is_null($bridgeId))
                throw new Exception("BridgeID is not provided or is null", 503);
            if (is_null($channel))
                throw new Exception("Channel is not provided or is null", 503);
            $delObj = array(
                'channel' => $channel,
            );

            $uri    = "/bridges/".$bridgeId."/removeChannel";
            $result = $this->pestObject->delete($uri,$delObj);
            return $result;

        } catch (Exception $e) {
            return false;
        }
    }

    /**
     *
     * POST /bridges/{bridgeId}/moh
     * Play music on hold to a bridge or change the MOH class that is playing.
     * @param null $bridgeId
     * @param null $mohClass
     * @return bool
     */
    public  function bridge_play_moh($bridgeId=null , $mohClass = null)
    {

        try {

            if (is_null($bridgeId))
                throw new Exception("BridgeID is not provided or is null", 503);
            if (is_null($mohClass))
                throw new Exception("Channel is not provided or is null", 503);


            $postObj = array(
                'mohClass' => $mohClass
            );

            $uri    = '/bridges/'.$bridgeId.'/moh';
            $result = $this->pestObject->post($uri,$delObj);
            return $result;

        } catch (Exception $e) {
            return false;
        }
    }

    /**
     *
     * DELETE /bridges/{bridgeId}/moh
     * Stop playing music on hold to a bridge.
     * This will only stop music on hold being played via POST bridges/{bridgeId}/moh.
     * @param null $bridgeId - Bridge's id
     * @return bool
     */
    public  function bridge_stop_moh($bridgeId=null)
    {

        try {

            if (is_null($bridgeId))
                throw new Exception("BridgeID is not provided or is null", 503);

            $uri    = '/bridges/'.$bridgeId.'/moh';
            $result = $this->pestObject->delete($uri);
            return $result;

        } catch (Exception $e) {
            return false;
        }
    }


    /**
     * POST /bridges/{bridgeId}/play
     * Start playback of media on a bridge.
     * The media URI may be any of a number of URI's. Currently sound:,
     * recording:, number:, digits:, characters:, and tone: URI's are supported.
     * This operation creates a playback resource that can be used to control the playback of media (pause, rewind, fast forward, etc.)
     *
     * @param null   $bridgeId      - Bridge's id
     * @param string $media         - (required) Media's URI to play.
     * @param string $lang          - For sounds, selects language for sound.
     * @param int    $offsetms      - Number of media to skip before playing.
     * @param int    $skipms        - Number of milliseconds to skip for forward/reverse operations.
     * @param null   $playbackId    - Playback Id.
     * @return bool
     */
    public  function bridge_start_playback($bridgeId=null , $media = null , $lang = null , $offsetms = null , $skipms = 3000 , $playbackId = null)
    {

        try {

            if (is_null($bridgeId))
                throw new Exception("BridgeID is not provided or is null", 503);
            if (is_null($media))
                throw new Exception("Media representation is not provided or is null", 503);
            if (is_null($lang))
                throw new Exception("lang is not provided or is null", 503);
            if (is_null($offsetms))
                throw new Exception("Offsetms is not provided or is null", 503);
            if (is_null($skipms))
                throw new Exception("Skimps is not provided or is null", 503);
            if (is_null($playbackId))
                throw new Exception("PlaybackId is not provided or is null", 503);


            $postObj = array(
                'media'       => $media,
                'lang'        => $lang,
                'offsetms'    => $offsetms,
                'skimps'      => $skipms,
                'playbackId'  => $playbackId

            );

            $uri    = '/bridges/'.$bridgeId.'/play';
            $result = $this->pestObject->post($uri,$postObj);
            return $result;

        } catch (Exception $e) {
            return false;
        }
    }


    /**
     *
     * POST /bridges/{bridgeId}/play/{playbackId}
     *
     * Start playback of media on a bridge.
     * The media URI may be any of a number of URI's.
     * Currently sound: and recording: URI's are supported.
     * This operation creates a playback resource that can be used
     * to control the playback of media (pause, rewind, fast forward, etc.)
     *
     * @param null   $bridgeId      - Bridge's id
     * @param string $media         - (required) Media's URI to play.
     * @param string $lang          - For sounds, selects language for sound.
     * @param int    $offsetms      - Number of media to skip before playing.
     * @param int    $skipms        - Number of milliseconds to skip for forward/reverse operations.
     * @param null   $playbackId    - Playback Id.
     * @return bool
     */
    public function bridge_start_playback_id($bridgeId = null , $media = null , $lang = null , $offsetms = null , $skipms = 3000 , $playbackId = null){

        try {

            if (is_null($bridgeId))
                throw new Exception("BridgeID is not provided or is null", 503);
            if (is_null($media))
                throw new Exception("Media representation is not provided or is null", 503);
            if (is_null($lang))
                throw new Exception("lang is not provided or is null", 503);
            if (is_null($offsetms))
                throw new Exception("Offsetms is not provided or is null", 503);
            if (is_null($skipms))
                throw new Exception("Skimps is not provided or is null", 503);
            if (is_null($playbackId))
                throw new Exception("PlaybackId is not provided or is null", 503);


            $postObj = array(
                'media'       => $media,
                'lang'        => $lang,
                'offsetms'    => $offsetms,
                'skimps'      => $skipms,

            );

            $uri    = '/bridges/'.$bridgeId.'/play/'.$playbackId;
            $result = $this->pestObject->post($uri,$postObj);
            return $result;

        } catch (Exception $e) {
            return false;
        }


    }


    /**
     *  POST /bridges/{bridgeId}/record
     *  Start a recording.
     *  This records the mixed audio from all
     *  channels participating in this bridge.
     *
     * @param null   $bridgeId
     * @param string $name                 - (required) Recording's filename
     * @param string $format               - (required) Format to encode audio in
     * @param int    $maxDurationSeconds   - Maximum duration of the recording, in seconds. 0 for no limit.
     * @param int    $maxSilenceSeconds    - Maximum duration of silence, in seconds. 0 for no limit.
     * @param string $ifExists             - Action to take if a recording with the same name already exists.
     * @param bool   $beep                 - Play beep when recording begins
     * @param string $terminateOn          - DTMF input to terminate recording.
     * @return bool
     */
    public function bridge_start_recording(
        $bridgeId           = null ,
        $name               = null ,
        $format             = null ,
        $maxDurationSeconds = 0 ,
        $maxSilenceSeconds  = 0 ,
        $ifExists           = "fail",
        $beep               = false,
        $terminateOn        = "none"

    ){

        try {

            if (is_null($bridgeId))
                throw new Exception("BridgeID is not provided or is null", 503);
            if (is_null($name))
                throw new Exception("Recording filename is not provided or is null", 503);
            if (is_null($format))
                throw new Exception("Format to encode audio in is not provided or is null", 503);


            $postObj = array(
                'name'                   => $name,
                'format'                 => $format,
                'maxDurationSeconds'     => $maxDurationSeconds,
                'maxSilenceSeconds'      => $maxSilenceSeconds,
                'ifExists'               => $ifExists,
                'beep'                   => $beep,
                'terminateOn'            => $terminateOn,
            );

            $uri    = '/bridges/'.$bridgeId.'/record';
            $result = $this->pestObject->post($uri,$postObj);
            return $result;

        } catch (Exception $e) {
            return false;
        }
    }

}



