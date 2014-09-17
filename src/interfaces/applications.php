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
class applications // extends phpari
{

    function __construct($connObject = null)
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
     * @return bool
     */
    public function   applications_list()
    {
        try {

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);

            $uri = "/applications";
            $result = $this->pestObject->get($uri);

            return $result;


        } catch (Exception $e) {
            return FALSE;
        }
    }

    /**
     * @param null $applicationName
     *
     * @return mixed
     */
    public function  application_details($applicationName = NULL)
    {
        try {

            if (is_null($applicationName))
                throw new Exception("Application name not provided or is null", 503);

            $uri = "/applications";
            $result = $this->pestObject->get($uri);

            return $result;

        } catch (Exception $e) {
            return FALSE;
        }
    }

    /**
     * @param null $applicationName
     * @param null $bridge
     * @param null $channelId
     * @param null $endpoint
     * @param null $deviceState
     *
     * @return bool
     */
    public function application_subscribe($applicationName = NULL, $bridge = NULL, $channelId = NULL, $endpoint = NULL, $deviceState = NULL)
    {
        try {

            if (is_null($applicationName))
                throw new Exception("Application name not provided or is null", 503);
            if (is_null($bridge))
                throw new Exception("Bridge id not provided or is null", 503);
            if (is_null($channelId))
                throw new Exception("Channel id not provided or is null", 503);

            if (is_null($endpoint))
                throw new Exception("End point  not provided or is null", 503);

            if (is_null($deviceState))
                throw new Exception("Device state name is not provided or is null", 503);


            $postObjParams = array(
                'channel' => $channelId,
                'bridge' => $bridge,
                'endpoint' => $endpoint,
                'deviceState' => $deviceState
            );


            $postObj['eventSource'] = $postObjParams;


            $uri = "/applications/" . $applicationName . "/subscription";
            $result = $this->pestObject->post($uri, $postObj);

            return $result;

        } catch (Exception $e) {
            return FALSE;
        }

    }


    /**
     * @param null $applicationName
     * @param null $bridge
     * @param null $channelId
     * @param null $endpoint
     * @param null $deviceState
     *
     * @return bool
     */
    public function application_unsubscribe($applicationName = NULL, $bridge = NULL, $channelId = NULL, $endpoint = NULL, $deviceState = NULL)
    {
        try {

            if (is_null($applicationName))
                throw new Exception("Application name not provided or is null", 503);
            if (is_null($bridge))
                throw new Exception("Bridge id not provided or is null", 503);
            if (is_null($channelId))
                throw new Exception("Channel id not provided or is null", 503);

            if (is_null($endpoint))
                throw new Exception("End point  not provided or is null", 503);

            if (is_null($deviceState))
                throw new Exception("Device state name is not provided or is null", 503);


            $postObjParams = array(
                'channel' => $channelId,
                'bridge' => $bridge,
                'endpoint' => $endpoint,
                'deviceState' => $deviceState
            );


            $postObj['eventSource'] = $postObjParams;


            $uri = "/applications/" . $applicationName . "/subscription";
            $result = $this->pestObject->delete($uri, $postObj);

            return $result;

        } catch (Exception $e) {
            return FALSE;
        }

    }
}





