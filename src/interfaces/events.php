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
class events // extends phpari
{
    private $phpariObject;

    function __construct($connObject = null)
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
     * GET /events
     * WebSocket connection for events.
     */
    public function  events($app)
    {
        try {

            if (is_null($app))
                throw new Exception("App name is  not provided or is null", 503);

            $uri = "/events";
            $getObj = array('app' => $app);

            $result = $this->pestObject->get($uri, $getObj);
            return $result;


        } catch (Exception $e) {
            $this->phpariObject->lasterror = $e->getMessage();
            $this->phpariObject->lasttrace = $e->getTraceAsString();
            return false;
        }
    }

    /**
     *
     * POST /events/user/{eventName}
     *
     *   Generate a user event.
     *
     * @param string $eventName - Event name
     * @param string $application - (required) The name of the application that will receive this event
     * @param string $channelID - Name of the Channel     - source part
     * @param string $bridge - Name of the  bridge     - source part
     * @param string $endpoint - Name of the endpoints   - source part
     * @param string $deviceName - The name of the device  - source part
     * @param array $variables - Ex. array("key1" => "value1" ,  "key2" => "value2")
     * @return bool
     */
    public function event_generate($eventName = null,
                                   $application = null,
                                   $channelID = null,
                                   $bridge = null,
                                   $endpoint = null,
                                   $deviceName = null,
                                   $variables = array())
    {

        try {

            if (is_null($application))
                throw new Exception("Application name is  not provided or is null", 503);
            if (is_null($eventName))
                throw new Exception("Event name is  not provided or is null", 503);


            $uri = "/events/user/" . $eventName;
            $postObj = array(
                'application' => $application,
                'source' => array(
                    'channel' => $channelID,
                    'bridge' => $bridge,
                    'endpoint' => $endpoint,
                    'deviceState' => $deviceName
                ),
                'variables' => $variables
            );

            $result = $this->pestObject->post($uri, $postObj);
            return $result;

        } catch (Exception $e) {
            $this->phpariObject->lasterror = $e->getMessage();
            $this->phpariObject->lasttrace = $e->getTraceAsString();
            return false;
        }
    }
}