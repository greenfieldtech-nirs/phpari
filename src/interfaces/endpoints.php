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
class endpoints //extends phpari
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
     * List all endpoints
     *
     * @return bool
     */
    public function   endpoints_list()
    {
        try {

            if (is_null($this->pestObject))
                throw new Exception("PEST Object not provided or is null", 503);

            $uri = "/endpoints";
            $result = $this->pestObject->get($uri);

            return $result;


        } catch (Exception $e) {
            return FALSE;
        }
    }

    /**
     *
     * Send a message to some technology URI or endpoint.
     * TODO somehow just can't create PUT method to the Asterisk server response : "message": "Invalid method"
     *
     *
     * @param null $to
     * @param null $from
     * @param null $body
     *
     * @return bool
     */
    public function  endpoint_sendmessage($to = NULL, $from = NULL, $body = NULL)
    {
        try {

            if (is_null($to))
                throw new Exception("endpoint name not provided or is null", 503);
            if (is_null($from))
                throw new Exception("endpoint name not provided or is null", 503);
            if (is_null($body))
                throw new Exception("endpoint name not provided or is null", 503);

            $uri = "/endpoints/sendMessage";


            $message = array(
                'to' => $to,
                'from' => $from,
                'body' => $body
            );


            $result = $this->pestObject->put($uri, $message);

            return $result;

        } catch (Exception $e) {
            return FALSE;
        }
    }

    /**
     * List available endPoints for a given endpoint technology.
     *
     * @param null $tech
     *
     * @return bool
     */
    public function   endpoints_tech($tech = NULL)
    {
        try {

            if (is_null($tech))
                throw new Exception("Technology is not provided or is null", 503);

            $uri = "/endpoints/" . $tech;
            $result = $this->pestObject->get($uri);

            return $result;


        } catch (Exception $e) {
            return FALSE;
        }
    }


    /**
     *
     * Details for an endpoint.
     * GET /endpoints/{tech}/{resource}
     *
     * @param null $tech
     * @param null $resource
     *
     * @return bool
     */
    public function   endpoints_tech_details($tech = NULL, $resource = NULL)
    {
        try {

            if (is_null($tech))
                throw new Exception("Technology is not provided or is null", 503);

            if (is_null($resource))
                throw new Exception("Technology is not provided or is null", 503);


            $uri = "/endpoints/" . $tech . "/" . $resource;
            $result = $this->pestObject->get($uri);

            return $result;


        } catch (Exception $e) {
            return FALSE;
        }
    }


    /**
     * Send a message to some endpoint in a technology.
     *
     * PUT /endpoints/{tech}/{resource}/sendMessage
     *
     * @param string $tech - Technology of the endpoint
     * @param string $resource - ID of the endpoint
     * @param string $body - The body of the message
     * @param null $from - (required)
     *
     * @return bool
     */
    public function   endpoint_sendmessage_intech($tech = NULL, $resource = NULL, $body = NULL, $from = NULL)
    {
        try {

            if (is_null($tech))
                throw new Exception("Technology is not provided or is null", 503);

            if (is_null($resource))
                throw new Exception("Technology is not provided or is null", 503);

            if (is_null($from))
                throw new Exception("Sender tech/endpoint uri  is not provided or is null", 503);

            if (is_null($body))
                throw new Exception("Body of the uri  is not provided or is null", 503);


            $putObj = array(
                'body' => $body,
                'from' => $from
            );


            $uri = "/endpoints/" . $tech . "/" . $resource . "/sendMessage";
            $result = $this->pestObject->put($uri, $putObj);

            return $result;


        } catch (Exception $e) {
            return FALSE;
        }
    }

}






