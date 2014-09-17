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


    function __construct($connObject = NULL)
    {
        try {

            if (is_null($connObject) || is_null($connObject->ariEndpoint))
                throw new Exception("Missing PestObject or empty string", 503);
            $this->pestObject = $connObject->ariEndpoint;

        } catch (Exception $e) {
            die("Exception raised: " . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine());
        }
    }

    /**
     * GET /recordings/stored
     * List recordings that are complete.
     *
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

            $uri = "/recordings/stored/" . $recordingName;
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

            $uri = "/recordings/stored/" . $recordingName;
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
    public function recording_stored_copy($recordingName = null, $destinationRecordingName = null)
    {
        try {


            if (is_null($recordingName))
                throw new Exception("Recording name is not provided or is null", 503);
            if (is_null($destinationRecordingName))
                throw new Exception("Destination recording name  not provided or is null", 503);

            $uri = "/recordings/stored/" . $recordingName . "/copy";
            $postOBJ = array(
                'destinationRecordingName' => $destinationRecordingName
            );


            $result = $this->pestObject->post($uri, $postOBJ);
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


            $uri = "/recordings/live/" . $recordingName;
            $result = $this->pestObject->get($uri);
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

            $uri = "/recordings/live/" . $recordingName;
            $result = $this->pestObject->delete($uri);
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
    public function  recordings_live_stop_n_store($recordingName = null)
    {

        try {
            if (is_null($recordingName))
                throw new Exception("Recording name is not provided or is null", 503);

            $uri = "/recordings/live/" . $recordingName . "/stop";
            $result = $this->pestObject->delete($uri);
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
    public function  recordings_live_pause($recordingName = null)
    {

        try {
            if (is_null($recordingName))
                throw new Exception("Recording name is not provided or is null", 503);

            $uri = "/recordings/live/" . $recordingName . "/pause";
            $result = $this->pestObject->post($uri);
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
    public function  recordings_live_unpause($recordingName = null)
    {

        try {
            if (is_null($recordingName))
                throw new Exception("Recording name is not provided or is null", 503);

            $uri = "/recordings/live/" . $recordingName . "/pause";
            $result = $this->pestObject->delete($uri);
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
    public function  recordings_live_mute($recordingName = null)
    {

        try {
            if (is_null($recordingName))
                throw new Exception("Recording name is not provided or is null", 503);

            $uri = "/recordings/live/" . $recordingName . "/mute";
            $result = $this->pestObject->post($uri);
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
    public function  recordings_live_unmute($recordingName = null)
    {

        try {
            if (is_null($recordingName))
                throw new Exception("Recording name is not provided or is null", 503);

            $uri = "/recordings/live/" . $recordingName . "/mute";
            $result = $this->pestObject->delete($uri);
            return $result;

        } catch (Exception $e) {
            return false;
        }
    }


}
