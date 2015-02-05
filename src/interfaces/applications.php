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
         * GET List of all applications or information regarding a specific application name
         *
         * @param null $applicationName
         *
         * @return mixed
         */
        public function show($applicationName = NULL)
        {
            try {

                if (is_null($this->pestObject))
                    throw new Exception("PEST Object not provided or is null", 503);

                $uri = "/applications";
                $uri .= (!is_null($applicationName)) ? "/" . $applicationName : "";
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
        public function applications_list()
        {
            return $this->show();
        }

        /**
         * This function is an alias to 'show' - will be deprecated in phpari 2.0
         *
         * @return mixed
         */
        public function  application_details($applicationName = NULL)
        {
            return $this->show($applicationName);
        }

        /**
         * Subscribe an application to a event source. Returns the state of the application after the subscriptions have changed
         *
         * @param string $applicationName
         * @param string $eventSources
         *
         * @return mixed
         */
        public function subscribe($applicationName = NULL, $eventSources = NULL)
        {
            try {

                if (is_null($applicationName))
                    throw new Exception("Application name not provided or is null", 503);
                if (is_null($eventSources))
                    throw new Exception("eventSources not provided or is null", 503);

                $eventsList = explode(",", $eventSources);

                foreach ($eventsList as $eventURI) {
                    $eventSourceType = strtok($eventURI, ":");

                    switch ($eventSourceType) {
                        case "channel":
                        case "bridge":
                        case "endpoint":
                        case "deviceState":
                            break;
                        default:
                            throw new Exception("Unknown event type for URI " . $eventURI, 503);
                            break;
                    }
                }

                $postObjParams = array(
                    'eventSource' => $eventSources
                );

                $uri    = "/applications/" . $applicationName . "/subscription";
                $result = $this->pestObject->post($uri, $postObjParams);

                return $result;

            } catch (Exception $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            }

        }

        public function application_subscribe($applicationName = NULL, $eventSourceURI = NULL)
        {
            return $this->subscribe($applicationName, $eventSourceURI);
        }

        /**
         * DELETE Unsubscribe an application from an event source. Returns the state of the application after the subscriptions have changed
         *
         * @param string $applicationName
         * @param string $eventSources
         *
         * @return mixed
         */
        public function unsubscribe($applicationName = NULL, $eventSources = NULL)
        {
            try {

                if (is_null($applicationName))
                    throw new Exception("Application name not provided or is null", 503);
                if (is_null($eventSources))
                    throw new Exception("eventSources not provided or is null", 503);

                $eventsList = explode(",", $eventSources);

                foreach ($eventsList as $eventURI) {
                    $eventSourceType = strtok($eventURI, ":");

                    switch ($eventSourceType) {
                        case "channel":
                        case "bridge":
                        case "endpoint":
                        case "deviceState":
                            break;
                        default:
                            throw new Exception("Unknown event type for URI " . $eventURI, 503);
                            break;
                    }
                }

                $postObjParams = array(
                    'eventSource' => $eventSources
                );

                $uri    = "/applications/" . $applicationName . "/subscription";
                $result = $this->pestObject->delete($uri, $postObjParams);

                return $result;

            } catch (Exception $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            }

        }

        public function application_unsubscribe($applicationName = NULL, $eventSources = NULL)
        {
            return $this->unsubscribe($applicationName, $eventSources);
        }

    }





