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
class playbacks //extends phpari
{
    private $phpariObject;

    function __construct($connObject = NULL)
    {
        try {

            if (is_null($connObject) || is_null($connObject->ariEndpoint))
                throw new Exception("Missing PestObject or empty string", 503);

            $this->phpariObject = $connObject;
            $this->pestObject = $connObject->ariEndpoint;

        } catch (Exception $e) {
            die("Exception raised: " . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine());
        }
    }

    /**
     * GET /playbacks/{playbackid}
     * 
     * GET details for a specific playback ID
     * 
     * @param null $playbackid
     * @return array|bool
     */
    public function show($playbackid = NULL)
    {
        try {
            $result = FALSE;

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);

            if (is_null($playbackid))
                throw new Exception("playbackid is required for this operation", 503);

            $uri = "/playbacks/" . $playbackid;
            $result = $this->pestObject->get($uri);

            return $result;
        } catch (Exception $e) {
            $this->phpariObject->lasterror = $e->getMessage();
            $this->phpariObject->lasttrace = $e->getTraceAsString();
            return FALSE;
        }
    }

    /**
     * This function is an alias to 'show' - will be deprecated in phpari 2.0
     *
     * @return mixed
     */
    public function get_playback($playbackid = NULL)
    {
        return $this->show($playbackid);
    }

    /**
     * @param null $playbackid
     * @return array|bool
     */
    public function remove($playbackid = NULL)
    {
        try {
            $result = FALSE;

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);

            if (is_null($playbackid))
                throw new Exception("playbackid is required for this operation", 503);

            $uri = "/playbacks/" . $playbackid;
            $result = $this->pestObject->delete($uri);

            return $result;
        } catch (Exception $e) {
            $this->phpariObject->lasterror = $e->getMessage();
            $this->phpariObject->lasttrace = $e->getTraceAsString();
            return FALSE;
        }
    }

    /**
     * This function is an alias to 'remove' - will be deprecated in phpari 2.0
     *
     * @return mixed
     */
    public function delete_playback($playbackid = NULL)
    {
        return $this->remove($playbackid);
    }

    /**
     * @param null $playbackid
     * @param null $control
     * @return array|bool
     */
    public function control($playbackid = NULL, $control = NULL)
    {
        try {
            $result = FALSE;

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);

            if (is_null($playbackid))
                throw new Exception("playbackid is required for this operation", 503);

            if (is_null($control))
                throw new Exception("control is required for this operation", 503);

            switch (strtolower($control)) {
                case "restart":
                case "pause":
                case "unpause":
                case "reverse":
                case "forward":
                    break;
                default:
                    throw new Exception("control property is unknown", 503);
                    break;
            }

            $uri = "/playbacks/" . $playbackid . "/control";
            $result = $this->pestObject->post($uri, array('operation' => $control));

            return $result;
        } catch (Exception $e) {
            $this->phpariObject->lasterror = $e->getMessage();
            $this->phpariObject->lasttrace = $e->getTraceAsString();
            return FALSE;
        }
    }

    /**
     * This function is an alias to 'control' - will be deprecated in phpari 2.0
     *
     * @return mixed
     */
    public function control_playback($playbackid = NULL, $control = NULL)
    {
        return $this->control($playbackid, $control);
    }
}
