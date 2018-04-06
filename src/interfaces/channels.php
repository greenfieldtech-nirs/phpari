<?php

namespace phpari\interfaces;
use phpari\helpers\parsing_helper;

/**
     * phpari - A PHP Class Library for interfacing with Asterisk(R) ARI
     * Copyright (C) 2014  Nir Simionovich
     *
     * This library is free software; you can redistribute it and/or
     * modify it under the terms of the GNU Lesser General Public
     * License as published by the Free Software Foundation; either
     * version 2.1 of the License, or (at your option) any later version.
     *
     * This library is distributed in the hope that it will be useful,
     * but WITHOUT ANY WARRANTY; without even the implied warranty of
     * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
     * Lesser General Public License for more details.
     *
     * You should have received a copy of the GNU Lesser General Public
     * License along with this library; if not, write to the Free Software
     * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
     * Also add information on how to contact you by electronic and paper mail.
     *
     * Greenfield Technologies Ltd., hereby disclaims all copyright interest in
     * the library `phpari' (a library for creating smart telephony applications)
     * written by Nir Simionovich and its respective list of contributors.
     */
    class channels //extends phpari
    {
        private $phpariObject;
        private $pestObject;

        function __construct($connObject = NULL)
        {
            try {

                if (is_null($connObject) || is_null($connObject->ariEndpoint))
                    throw new \Exception("Missing PestObject or empty string", 503);

                $this->phpariObject = $connObject;
                $this->pestObject = $connObject->ariEndpoint;


            } catch (\Exception $e) {
                die("Exception raised: " . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine());
            }
        }

        /**
         * Get the current list of active channels
         *
         * @return object|bool - false for a failure, JSON object for all other results
         */
        public function show()
        {
            try {

                $result = FALSE;

                if (is_null($this->pestObject))
                    throw new \Exception("PEST Object not provided or is null", 503);

                $uri = "/channels";
                $result = $this->pestObject->get($uri);

                return $result;


            } catch (\Pest_NotFound $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Pest_BadRequest $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Exception $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            }
        }

        public function channel_list()
        {
            return $this->show();
        }

        /**
         * Originate a call on a channel
         *
         * @param null (string)                              $endpoint   - endpoint to originate the call to, eg:
         *                                                               SIP/alice
         * @param null (string)                              $channel_id - Assign a channel ID for the newly created
         *                                                               channel
         * @param null (JSON_STRING|JSON_OBJECT|ASSOC_ARRAY) $data       - originate data
         * @param null (JSON_STRING|JSON_OBJECT|ASSOC_ARRAY) $variables  - originate assigned variables
         *
         * @return bool - false on success, Integer or True on failure
         *
         * $data structure:
         *
         * {
         *      "extension": (String) "The extension to dial after the endpoint answers",
         *      "context": (String) "The context to dial after the endpoint answers. If omitted, uses 'default'",
         *      "priority": (Long) "he priority to dial after the endpoint answers. If omitted, uses 1",
         *      "app": (String) "The application that is subscribed to the originated channel, and passed to the Stasis
         *      application",
         *      "appArgs": (String) "The application arguments to pass to the Stasis application",
         *      "callerId": (String) "CallerID to use when dialing the endpoint or extension",
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

        public function originate($endpoint = NULL, $channel_id = NULL, $data = NULL, $variables = NULL)
        {
            try {

                $inputParser = new parsing_helper();


                if (is_null($endpoint))
                    throw new \Exception("End point not provided or is null", 503);

                $originateData = array();
                $originateData['endpoint'] = $endpoint;

                if (!is_null($data))
                    $originateData = array_merge($originateData, $inputParser->parseRequestData($data));

                if (!is_null($variables))
                    $originateData['variables'] = $inputParser->parseRequestData($variables);

                $uri = (is_null($channel_id)) ? "/channels" : "/channels/" . $channel_id;
                $result = $this->pestObject->post($uri, $originateData);

                return $result;

            } catch (\Pest_NotFound $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Pest_BadRequest $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Exception $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            }


        }

        public function channel_originate($endpoint = NULL, $channel_id = NULL, $data = NULL, $variables = NULL)
        {
            return $this->originate($endpoint, $channel_id, $data, $variables);
        }

        /**
         * Get active channel details for an existing channel
         *
         * @param null (string) $channel_id - channel identifier to query
         *
         * @return bool - false on success, Integer or True on failure
         */
        public function getDetails($channel_id = NULL)
        {
            try {

                if (is_null($channel_id))
                    throw new \Exception("PEST Object not provided or is null", 503);

                $result = $this->pestObject->get("/channels/" . $channel_id);

                return $result;

            } catch (\Pest_NotFound $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Pest_BadRequest $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Exception $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            }
        }

        public function channel_get_details($channel_id = NULL)
        {
            return $this->getDetails($channel_id);
        }

        /**
         * Delete / hangup  an  active channel
         *
         * @param null (string) $channel_id - channel identifier to query
         *
         * @return bool - false on success, Integer or True on failure
         */
        public function delete($channel_id = NULL)
        {
            try {

                // $result = false;

                if (is_null($channel_id))
                    throw new \Exception("Channel ID not provided or is null", 503);

                $result = $this->pestObject->delete("/channels/" . $channel_id);

                return $result;

            } catch (\Pest_NotFound $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Pest_BadRequest $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Exception $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            }
        }

        public function channel_delete($channel_id = NULL)
        {
            return $this->delete($channel_id);
        }

        /**
         * @param null $channel_id
         * @param null $context
         * @param null $extension
         * @param null $priority
         *
         * @return bool
         */
        public function resume($channel_id = NULL, $context = NULL, $extension = NULL, $priority = NULL)
        {
            try {

                // $result = false;

                if (is_null($channel_id))
                    throw new \Exception("Channel ID not provided or is null", 503);
                if (is_null($context))
                    throw new \Exception("Content not provided or is null", 503);
                if (is_null($extension))
                    throw new \Exception("Extension not provided or is null", 503);
                if (is_null($priority))
                    throw new \Exception("Priority not provided or is null", 503);

                $postArray = array('context' => $context, 'extension' => $extension, 'priority' => (int)$priority);

                $result = $this->pestObject->post("/channels/" . $channel_id . "/continue", $postArray);

                return $result;


            } catch (\Pest_NotFound $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Pest_BadRequest $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Exception $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            }
        }

        public function channel_continue($channel_id = NULL, $context = NULL, $extension = NULL, $priority = NULL)
        {
            return $this->resume($channel_id, $context, $extension, $priority);
        }

        /**
         * Answer   an  active channel
         *
         * @param null (string) $channel_id - channel identifier to query
         *
         * @return bool - false on success, Integer or True on failure
         */
        public function answer($channel_id = NULL)
        {
            try {

                // $result = false;

                if (is_null($channel_id))
                    throw new \Exception("Channel ID not provided or is null", 503);

                $result = $this->pestObject->post("/channels/" . $channel_id . "/answer", array());

                return $result;


            } catch (\Pest_NotFound $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Pest_BadRequest $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Exception $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            }
        }

        public function channel_answer($channel_id = NULL)
        {
            return $this->answer($channel_id);
        }

        /**
         * Indicate ringing to an active channel
         *
         *
         * @param null $channel_id
         *
         * @return bool
         */
        public function indicateRingingStart($channel_id = NULL)
        {
            try {

                // $result = false;

                if (is_null($channel_id))
                    throw new \Exception("Channel ID not provided or is null", 503);

                $result = $this->pestObject->post("/channels/" . $channel_id . "/ring", array());

                return $result;


            } catch (\Pest_NotFound $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Pest_BadRequest $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Exception $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            }
        }

        public function channel_ringing_start($channel_id = NULL)
        {
            return $this->indicateRingingStart($channel_id);
        }

        public function indicateRingingStop($channel_id = NULL)
        {
            try {

                // $result = false;

                if (is_null($channel_id))
                    throw new \Exception("Channel ID not provided or is null", 503);

                $result = $this->pestObject->delete("/channels/" . $channel_id . "/ring");

                return $result;


            } catch (\Pest_NotFound $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Pest_BadRequest $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Exception $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            }
        }

        public function channel_ringing_stop($channel_id = NULL)
        {
            return $this->indicateRingingStop($channel_id);
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
         * @param int  $before
         * @param int  $between
         * @param int  $duration
         * @param int  $after
         *
         * @return bool
         */
        public function sendDtmf($channel_id = NULL, $dtmf = NULL, $before = 1000, $between = 100, $duration = 100, $after = 1000)
        {
            try {

                $result = FALSE;

                if (is_null($channel_id))
                    throw new \Exception("Channel ID not provided or is null", 503);

                if (is_null($dtmf))
                    throw new \Exception("The dtmfObject not provided or is null", 503);

                $dtmfObject = array(
                    'dtmf'     => $dtmf,
                    'before'   => $before,
                    'between'  => $between,
                    'duration' => $duration,
                    'after'    => $after
                );


                $uri = "/channels/" . $channel_id . "/dtmf";
                $result = $this->pestObject->post($uri, $dtmfObject);

                return $result;

            } catch (\Pest_NotFound $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Pest_BadRequest $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Exception $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            }
        }

        public function channel_send_dtmf($channel_id = NULL, $dtmf = NULL, $before = 1000, $between = 100, $duration = 100, $after = 1000)
        {
            return $this->sendDtmf($channel_id, $dtmf, $before, $between, $duration, $after);
        }

        /**
         * Mute a channel
         *
         *
         * @param null   $channel_id
         * @param string $direction
         *
         * @return bool
         */
        public function mute($channel_id = NULL, $direction = 'both')
        {
            try {

                // $result = false;

                if (is_null($channel_id))
                    throw new \Exception("Channel ID not provided or is null", 503);

                $cDirection = array('direction' => $direction);


                $result = $this->pestObject->post("/channels/" . $channel_id . "/mute", $cDirection);

                return $result;

            } catch (\Pest_NotFound $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Pest_BadRequest $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Exception $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            }
        }

        public function channel_mute($channel_id = NULL, $direction = 'both')
        {
            return $this->mute($channel_id, $direction);
        }

        /**
         * Unmute a channel
         *
         *
         * @param null   $channel_id
         * @param string $direction
         *
         * @return bool
         */
        public function unmute($channel_id = NULL, $direction = 'both')
        {
            try {

                // $result = false;

                if (is_null($channel_id))
                    throw new \Exception("Channel ID not provided or is null", 503);

                $cDirection = array('direction' => $direction);


                $result = $this->pestObject->delete("/channels/" . $channel_id . "/unmute", $cDirection);

                return $result;


            } catch (\Pest_NotFound $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Pest_BadRequest $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Exception $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            }
        }

        public function channel_unmute($channel_id = NULL, $direction = 'both')
        {
            return $this->unmute($channel_id, $direction);
        }

        /**
         * Hold a channel
         *
         * @param null $channel_id
         *
         * @return bool
         */
        public function hold($channel_id = NULL, $action = "start")
        {
            try {

                // $result = false;

                if (is_null($channel_id))
                    throw new \Exception("Channel ID not provided or is null", 503);

                switch ($action) {
                    case "stop":
                        $result = $this->pestObject->delete("/channels/" . $channel_id . "/hold");
                        break;
                    case "start":
                    default:
                        $result = $this->pestObject->post("/channels/" . $channel_id . "/hold", array());
                        break;
                }

                return $result;

            } catch (\Pest_NotFound $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Pest_BadRequest $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Exception $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            }
        }

        public function channel_hold($channel_id = NULL)
        {
            return $this->hold($channel_id, "start");
        }

        public function channel_unhold($channel_id = NULL)
        {
            return $this->hold($channel_id, "stop");
        }

        /**
         * Play music on hold to a channel. Using media operations such as /play on a channel
         * playing MOH in this manner will suspend MOH without resuming automatically.
         * If continuing music on hold is desired, the stasis application must reinitiate music on hold.
         *
         * @param null $channel_id
         * @param null $mohClass
         *
         * @return bool
         */
        public function mohStart($channel_id = NULL, $mohClass = NULL)
        {
            try {

                // $result = false;

                if (is_null($channel_id))
                    throw new \Exception("Channel ID not provided or is null", 503);
                if (is_null($mohClass))
                    throw new \Exception("mohClass not provided or is null", 503);


                $postMoh = array('mohClass' => $mohClass);
                $result = $this->pestObject->post("/channels/" . $channel_id . "/moh", $postMoh);

                return $result;

            } catch (\Pest_NotFound $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Pest_BadRequest $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Exception $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            }
        }

        public function channel_moh_start($channel_id = NULL, $mohClass = NULL)
        {
            return $this->mohStart($channel_id, $mohClass);
        }

        /**
         * Stop music on hold on cpecified channel
         *
         * @param null $channel_id
         *
         * @return bool
         */
        public function mohStop($channel_id = NULL)
        {
            try {

                // $result = false;

                if (is_null($channel_id))
                    throw new \Exception("Channel ID not provided or is null", 503);

                $result = $this->pestObject->delete("/channels/" . $channel_id . "/moh");

                return $result;

            } catch (\Pest_NotFound $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Pest_BadRequest $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Exception $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            }
        }

        public function channel_moh_stop($channel_id = NULL)
        {
            return $this->mohStop($channel_id);
        }

        /**
         * Start/Stop Playing silence to a channel.
         * Using media operations such as /play on a channel playing silence in this manner will
         * suspend silence without resuming automatically.
         *
         * @param null $channel_id
         *
         * @return bool
         */
        public function silence($channel_id = NULL, $action = "start")
        {
            try {

                if (is_null($channel_id))
                    throw new \Exception("Channel ID not provided or is null", 503);

                switch ($action) {
                    case "stop":
                        $result = $this->pestObject->delete("/channels/" . $channel_id . "/silence");
                        break;
                    case "start":
                    default:
                        $result = $this->pestObject->post("/channels/" . $channel_id . "/silence", array());
                        break;
                }

                return $result;

            } catch (\Pest_NotFound $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Pest_BadRequest $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Exception $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            }

        }

        public function channel_silence_start($channel_id = NULL)
        {
            return $this->silence($channel_id, "start");
        }

        public function channel_silence_stop($channel_id = NULL)
        {
            return $this->silence($channel_id, "stop");
        }

        public function playback($channel_id = NULL, $media = NULL, $lang = "en", $offsetms = 0, $skipms = 3000, $playbackid = NULL)
        {
            try {

                $result = FALSE;

                if (is_null($media))
                    throw new \Exception("media URI not provided or is null", 503);

                if (is_null($channel_id))
                    throw new \Exception("channel ID not provided or is null", 503);

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

            } catch (\Pest_NotFound $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Pest_BadRequest $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Exception $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            }
        }

        public function channel_playback($channel_id = NULL, $media = NULL, $lang = "en", $offsetms = 0, $skipms = 3000, $playbackid = NULL)
        {
            return $this->playback($channel_id, $media, $lang, $offsetms, $skipms, $playbackid);
        }

        /**
         * Start a recording. Record audio from a channel.
         * Note that this will not capture audio sent to the channel.
         * The bridge itself has a record feature if that's what you want.
         *
         * @param null   $channel_id         - (required) ChannelID
         * @param null   $name               string       - (required) Recording's filename
         * @param null   $format             string     - (required) Format to encode audio in
         * @param int    $maxDurationSeconds - Maximum duration of the recording, in seconds. 0 for no limit
         * @param int    $maxSilenceSeconds  - Maximum duration of silence, in seconds. 0 for no limit
         * @param string $ifExists           - Action to take if a recording with the same name already exists.
         * @param bool   $beep               - Play beep when recording begins
         * @param string $terminateOn        - DTMF input to terminate recording
         *
         * @return bool
         */
        public function record($channel_id = NULL, $name = NULL, $format = NULL, $maxDurationSeconds = 0, $maxSilenceSeconds = 0, $ifExists = 'fail', $beep = TRUE, $terminateOn = "none")
        {
            try {

                if (is_null($channel_id))
                    throw new \Exception("Channel ID not provided or is null", 503);
                if (is_null($name))
                    throw new \Exception("Recording's filename is not provided or is null", 503);
                if (is_null($format))
                    throw new \Exception("Format to encode audio is not provided or is null", 503);

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

            } catch (\Pest_NotFound $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Pest_BadRequest $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Exception $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            }

        }

        public function channel_record($channel_id = NULL, $name = NULL, $format = NULL, $maxDurationSeconds = 0, $maxSilenceSeconds = 0, $ifExists = 'fail', $beep = TRUE, $terminateOn = "none")
        {
            return $this->record($channel_id, $name, $format, $maxDurationSeconds, $maxSilenceSeconds, $ifExists, $beep, $terminateOn);
        }

        /**
         * Get the value of a channel variable or function
         *
         * @param null $channel_id
         * @param null $variable
         *
         * @return bool
         */
        public function getVariable($channel_id = NULL, $variable = NULL)
        {
            try {

                if (is_null($channel_id))
                    throw new \Exception("Channel ID not provided or is null", 503);
                if (is_null($variable))
                    throw new \Exception("The variable  is not provided or is null", 503);


                $getObject = array('variable' => $variable);
                $result = $this->pestObject->get("/channels/" . $channel_id . "/variable", $getObject);

                return $result;

            } catch (\Pest_NotFound $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Pest_BadRequest $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Exception $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            }
        }

        public function channel_get_variable($channel_id = NULL, $variable = NULL)
        {
            return $this->getVariable($channel_id, $variable);
        }

        /**
         *
         * Set the value of a channel variable or function.
         *
         *
         * @param null $channel_id
         * @param null $variable
         * @param null $value
         *
         * @return bool
         */
        public function setVariable($channel_id = NULL, $variable = NULL, $value = NULL)
        {
            try {

                if (is_null($channel_id))
                    throw new \Exception("Channel ID not provided or is null", 503);
                if (is_null($variable))
                    throw new \Exception("The variable  is not provided or is null", 503);

                $postObject = array('variable' => $variable, 'value' => $value);
                $result = $this->pestObject->post("/channels/" . $channel_id . "/variable", $postObject);

                return $result;

            } catch (\Pest_NotFound $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Pest_BadRequest $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Exception $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            }
        }

        public function channel_set_variable($channel_id = NULL, $variable = NULL, $value = NULL)
        {
            return $this->setVariable($channel_id, $variable, $value);
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
         *
         * @return bool
         */
        public function snoop($channel_id = NULL, $spy = "none", $whisper = "none", $app = NULL, $appArgs = NULL, $snoopId = NULL, $action = "start")
        {
            try {

                if (is_null($channel_id))
                    throw new \Exception("Channel ID not provided or is null", 503);
                if (is_null($app))
                    throw new \Exception("The application the snooping channel is placed into is not provided or is null", 503);

                $postObject = array(
                    'spy'     => $spy,
                    'whisper' => $whisper,
                    'app'     => $app,
                    'appArgs' => $appArgs,
                    'snoopId' => $snoopId
                );

                $result = $this->pestObject->post("/channels/" . $channel_id . "/snoop", $postObject);

                return $result;

            } catch (\Pest_NotFound $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Pest_BadRequest $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            } catch (\Exception $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            }
        }

        public function channel_snoop_start($channel_id = NULL, $spy = "none", $whisper = "none", $app = NULL, $appArgs = NULL, $snoopId = NULL)
        {
            return $this->snoop($channel_id, $spy, $whisper, $app, $appArgs, $snoopId);
        }

        public function channel_snoop_start_id($channel_id = NULL, $spy = "none", $whisper = "none", $app = NULL, $appArgs = NULL, $snoopId = NULL)
        {
            return $this->snoop($channel_id, $spy, $whisper, $app, $appArgs, $snoopId);
        }
    }