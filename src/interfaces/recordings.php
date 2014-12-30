<?php

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
    class recordings //extends phpari
    {

        private $phpariObject;

        function __construct($connObject = NULL)
        {
            try {

                if (is_null($connObject) || is_null($connObject->ariEndpoint))
                    throw new Exception("Missing PestObject or empty string", 503);

                $this->phpariObject = $connObject;
                $this->pestObject   = $connObject->ariEndpoint;

            } catch (Exception $e) {
                die("Exception raised: " . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine());
            }
        }

        /**
         * Perform actions on stored recordings. List (show), Details(show), Delete(delete), Copy(copy)
         *
         * @param null $action
         * @param null $recordingName
         * @param null $destinationRecording
         *
         * @return mixed
         */
        public function stored($action = NULL, $recordingName = NULL, $destinationRecording = NULL)
        {
            try {

                $uri = "/recordings/stored";

                if (is_null($action))
                    throw new Exception("action not specified or is null", 503);

                switch ($action) {
                    case "show":
                    case "list":
                        $uri .= (!is_null($recordingName)) ? "/" . $recordingName : "";
                        $result = $this->pestObject->get($uri);
                        break;
                    default:
                        if (is_null($recordingName))
                            throw new Exception("recording name not specified or is null", 503);

                        $uri .= "/" . $recordingName;

                        switch ($action) {
                            case "delete":
                                $result = $this->pestObject->delete($uri);
                                break;
                            case "copy":
                                if (is_null($destinationRecording))
                                    throw new Exception("destination recording name not specified or is null", 503);
                                $uri .= "/copy";
                                $postOBJ = array(
                                    'destinationRecordingName' => $destinationRecording
                                );
                                $result = $this->pestObject->post($uri, $postOBJ);
                                break;
                            default:
                                throw new Exception("unknown action specified", 503);
                                break;
                        }
                        break;
                }

                return $result;

            } catch (Exception $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            }
        }

        /**
         * This function is an alias to 'stored' - action 'show' or 'list' - will be deprecated in phpari 2.0
         *
         * @return mixed
         */
        public function recording_list()
        {
            return $this->stored("show");
        }

        /**
         * This function is an alias to 'stored' - action 'show' or 'list' - will be deprecated in phpari 2.0
         *
         * @return mixed
         */
        public function recording_detail($recordingName = NULL)
        {
            return $this->stored("show", $recordingName);
        }

        /**
         * This function is an alias to 'stored' - action 'delete' - will be deprecated in phpari 2.0
         *
         * @return mixed
         */
        public function delete_recording($recordingName = NULL)
        {
            return $this->stored("delete", $recordingName);
        }

        /**
         * This function is an alias to 'stored' - action 'copy' - will be deprecated in phpari 2.0
         *
         * @return mixed
         */
        public function recording_stored_copy($recordingName = NULL, $destinationRecordingName = NULL)
        {
            return $this->stored("copy", $recordingName, $destinationRecordingName);
        }

        /**
         * Perform actions on live recordings. Start, Stop, Pause, Unpause, Mute, Unmute
         *
         * @param null $action
         * @param null $recordingName
         *
         * @return mixed
         */
        public function live($action = NULL, $recordingName = NULL)
        {
            try {

                $uri = "/recordings/live";

                if (is_null($action))
                    throw new Exception("action not specified or is null", 503);

                switch ($action) {
                    case "show":
                        $uri .= (!is_null($recordingName)) ? "/" . $recordingName : "";
                        $result = $this->pestObject->get($uri);
                        break;
                    default:
                        if (is_null($recordingName))
                            throw new Exception("recording name not specified or is null", 503);

                        $uri = "/recordings/live/" . $recordingName;

                        switch ($action) {
                            case "start":
                                throw new Exception("Starting a recording is done via channels interface - have you forgotten?", 503);
                                break;
                            case "stop":
                                $uri .= "/stop";
                                $result = $this->pestObject->post($uri);
                                break;
                            case "discard":
                                $result = $this->pestObject->delete($uri);
                                break;
                            case "pause":
                                $uri .= "/pause";
                                $result = $this->pestObject->post($uri);
                                break;
                            case "unpause":
                                $uri .= "/pause";
                                $result = $this->pestObject->delete($uri);
                                break;
                            case "mute":
                                $uri .= "/mute";
                                $result = $this->pestObject->post($uri);
                                break;
                            case "unmute":
                                $uri .= "/mute";
                                $result = $this->pestObject->post($uri);
                                break;
                            default:
                                throw new Exception("unknown action specified", 503);
                                break;
                        }
                        break;
                }

                return $result;

            } catch (Exception $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            }
        }

        /**
         * This function is an alias to 'live' - action 'show' - will be deprecated in phpari 2.0
         *
         * @return mixed
         */
        public function recordings_live_list($recordingName = NULL)
        {
            return $this->live("show", $recordingName);
        }

        /**
         * This function is an alias to 'live' - action 'discard' - will be deprecated in phpari 2.0
         *
         * @return mixed
         */
        public function recordings_live_stop_n_discard($recordingName = NULL)
        {
            return $this->live("discard", $recordingName);
        }

        /**
         * This function is an alias to 'live' - action 'stop' - will be deprecated in phpari 2.0
         *
         * @return mixed
         */
        public function recordings_live_stop_n_store($recordingName = NULL)
        {
            return $this->live("stop", $recordingName);
        }

        /**
         * This function is an alias to 'live' - action 'pause' - will be deprecated in phpari 2.0
         *
         * @return mixed
         */
        public function recordings_live_pause($recordingName = NULL)
        {
            return $this->live("pause", $recordingName);
        }

        /**
         * This function is an alias to 'live' - action 'unpause' - will be deprecated in phpari 2.0
         *
         * @return mixed
         */
        public function recordings_live_unpause($recordingName = NULL)
        {
            return $this->live("unpause", $recordingName);
        }

        /**
         * This function is an alias to 'live' - action 'mute' - will be deprecated in phpari 2.0
         *
         * @return mixed
         */
        public function  recordings_live_mute($recordingName = NULL)
        {
            return $this->live("mute", $recordingName);
        }

        /**
         * This function is an alias to 'live' - action 'unmute' - will be deprecated in phpari 2.0
         *
         * @return mixed
         */
        public function  recordings_live_unmute($recordingName = NULL)
        {
            return $this->live("unmute", $recordingName);
        }
    }
