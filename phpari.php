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
    class phpari
    {

        /**
         * @param null   $ariUsername
         * @param null   $ariPassword
         * @param null   $stasisApplication
         * @param string $ariServer
         * @param int    $ariPort
         * @param string $ariEndpoint
         *
         * Returns an array containing 4 objects: WebSocket, Pest, EventLoopFactory, Logger
         */
        public function __construct($ariUsername = NULL, $ariPassword = NULL, $stasisApplication = NULL, $ariServer = "127.0.0.1", $ariPort = 8080, $ariEndpoint = "/ari")
        {
            try {
                if ((is_null($ariUsername)) || (!strlen($ariEndpoint)))
                    throw new Exception("Missing ARI username or empty string", 503);

                if ((is_null($ariPassword)) || (!strlen($ariPassword)))
                    throw new Exception("Missing ARI password or empty string", 503);

                if ((is_null($stasisApplication)) || (!strlen($stasisApplication)))
                    throw new Exception("Missing ARI stasis application name or empty string", 503);


                $result = $this->connect($ariUsername, $ariPassword, $stasisApplication, $ariServer, $ariPort, $ariEndpoint);

                // $this->init();

                return $result;


            } catch (Exception $e) {
                die("Exception raised: " . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine());
            }

        }

        private function connect($ariUsername, $ariPassword, $stasisApplication, $ariServer, $ariPort, $ariEndpoint)
        {

            try {

                $this->ariEndpoint = new PestJSON("http://" . $ariServer . ":" . $ariPort . $ariEndpoint);
                $this->ariEndpoint->setupAuth($ariUsername, $ariPassword, "basic");

                $this->stasisLoop = \React\EventLoop\Factory::create();

                $this->stasisLogger = new \Zend\Log\Logger();
                $this->logWriter    = new Zend\Log\Writer\Stream("php://output");
                $this->stasisLogger->addWriter($this->logWriter);


                $this->stasisClient = new \Devristo\Phpws\Client\WebSocket("ws://" . $ariServer . ":" . $ariPort . "/ari/events?api_key=" . $ariUsername . ":" . $ariPassword . "&app=" . $stasisApplication, $this->stasisLoop, $this->stasisLogger);

                return array("stasisClient" => $this->stasisClient, "stasisLoop" => $this->stasisLoop, "stasisLogger" => $this->stasisLogger, "ariEndpoint" => $this->ariEndpoint);

            } catch (Exception $e) {
                die("Exception raised: " . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine());
            }

        }


    }

