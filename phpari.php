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
        private $applications;
        private $asterisk;
        private $bridges;
        private $channels;
        private $devicestates;
        private $endpoints;
        private $events;
        private $mailboxes;
        private $recordings;
        private $sounds;
        private $playbacks;

        public $stasisEvents;
        public $stasisLogger;
        private $configuration;
        public $debug;
        public $logfile;

        /**
         * @param null   $stasisApplication
         * @param string $configFile
         *
         * Returns an array containing 5 objects: WebSocket, Pest, EventLoopFactory, Logger, StasisEventHandler
         *
         */
        //public function __construct($ariUsername = NULL, $ariPassword = NULL, $stasisApplication = NULL, $ariServer = "127.0.0.1", $ariPort = 8080, $ariEndpoint = "/ari")
        public function __construct($stasisApplication = NULL, $configFile = "phpari.ini")
        {
            try {

                /* Get our configuration */
                $this->configuration = (object)parse_ini_file($configFile, TRUE);

                /* Some general information */
                $this->debug = $this->configuration->general['debug'];
                $this->logfile = $this->configuration->general['logfile'];

                /* Connect to ARI server */
                $result = $this->connect($this->configuration->asterisk_ari['username'],
                                        $this->configuration->asterisk_ari['password'],
                                        $stasisApplication,
                                        $this->configuration->asterisk_ari['host'],
                                        $this->configuration->asterisk_ari['port'],
                                        $this->configuration->asterisk_ari['endpoint'],
                                        $this->configuration->asterisk_ari['transport']);

                return $result;


            } catch (Exception $e) {
                die("Exception raised: " . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine());
            }

        }

        /**
         * This function is connecting and returning a phpari client object which
         * transferred to any of the interfaces will assist with the connection process
         * to the Asterisk Stasis or to the Asterisk 12 web server (Channels list , End Points list)
         * etc.
         *
         * @param $ariUsername
         * @param $ariPassword
         * @param $stasisApplication
         * @param $ariServer
         * @param $ariPort
         * @param $ariEndpoint
         *
         * @return array
         */
        private function connect($ariUsername, $ariPassword, $stasisApplication, $ariServer, $ariPort, $ariEndpoint, $ariTransport)
        {

            try {

                $this->ariEndpoint = new PestJSON("http://" . $ariServer . ":" . $ariPort . $ariEndpoint);
                $this->ariEndpoint->setupAuth($ariUsername, $ariPassword, "basic");

                $this->stasisLoop = \React\EventLoop\Factory::create();

                $this->stasisLogger = new \Zend\Log\Logger();

                if ($this->configuration->general['logfile'] == "console")
                    $this->logWriter    = new Zend\Log\Writer\Stream("php://output");
                else
                    $this->logWriter    = new Zend\Log\Writer\Stream($this->configuration->general['logfile']);

                $this->stasisLogger->addWriter($this->logWriter);


                if ($this->debug) $this->stasisLogger->debug("Initializing WebSocket Information");

                $this->stasisClient = new \Devristo\Phpws\Client\WebSocket($ariTransport . "://" . $ariServer . ":" . $ariPort . "/ari/events?api_key=" . $ariUsername . ":" . $ariPassword . "&app=" . $stasisApplication, $this->stasisLoop, $this->stasisLogger);

                if ($this->debug) $this->stasisLogger->debug("Initializing Stasis Event Emitter");

                $this->stasisEvents = new Evenement\EventEmitter();


                return true;
                //return array("stasisClient" => $this->stasisClient, "stasisLoop" => $this->stasisLoop, "stasisLogger" => $this->stasisLogger, "ariEndpoint" => $this->ariEndpoint);

            } catch (Exception $e) {
                die("Exception raised: " . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine());
            }
        }

        public function applications()
        {
            try {
                $this->applications = new applications($this);
                if ($this->debug) $this->stasisLogger->debug("applications class had been initiated");
                return $this->applications;
            } catch (Exception $e) {

                if ($this->debug) $this->stasisLogger->debug("applications has failed initialization");
                return TRUE;
            }
        }

        public function asterisk()
        {
            try {
                $this->asterisk = new asterisk($this);
                if ($this->debug) $this->stasisLogger->debug("asterisk class had been initiated");
                return $this->asterisk;
            } catch (Exception $e) {

                if ($this->debug) $this->stasisLogger->debug("asterisk has failed initialization");
                return TRUE;
            }
        }

        public function bridges()
        {
            try {
                $this->bridges = new bridges($this);
                if ($this->debug) $this->stasisLogger->debug("bridges class had been initiated");
                return $this->bridges;
            } catch (Exception $e) {

                if ($this->debug) $this->stasisLogger->debug("bridges has failed initialization");
                return TRUE;
            }
        }

        public function channels()
        {
            try {
                $this->channels = new channels($this);
                if ($this->debug) $this->stasisLogger->debug("channels class had been initiated");
                return $this->channels;
            } catch (Exception $e) {

                if ($this->debug) $this->stasisLogger->debug("channels has failed initialization");
                return TRUE;
            }
        }

        public function devicestates()
        {
            try {
                $this->devicestates = new devicestates($this);
                if ($this->debug) $this->stasisLogger->debug("devicestates class had been initiated");
                return $this->devicestates;
            } catch (Exception $e) {

                if ($this->debug) $this->stasisLogger->debug("devicestates has failed initialization");
                return TRUE;
            }
        }

        public function endpoints()
        {
            try {
                $this->endpoints = new endpoints($this);
                if ($this->debug) $this->stasisLogger->debug("endpoints class had been initiated");
                return $this->endpoints;
            } catch (Exception $e) {

                if ($this->debug) $this->stasisLogger->debug("endpoints has failed initialization");
                return TRUE;
            }
        }

        public function events()
        {
            try {
                $this->events = new events($this);
                if ($this->debug) $this->stasisLogger->debug("events class had been initiated");
                return $this->events;
            } catch (Exception $e) {

                if ($this->debug) $this->stasisLogger->debug("events has failed initialization");
                return TRUE;
            }
        }

        public function mailboxes()
        {
            try {
                $this->mailboxes = new mailboxes($this);
                if ($this->debug) $this->stasisLogger->debug("mailboxes class had been initiated");
                return $this->mailboxes;
            } catch (Exception $e) {

                if ($this->debug) $this->stasisLogger->debug("mailboxes has failed initialization");
                return TRUE;
            }
        }

        public function recordings()
        {
            try {
                $this->recordings = new recordings($this);
                if ($this->debug) $this->stasisLogger->debug("recordings class had been initiated");
                return $this->recordings;
            } catch (Exception $e) {

                if ($this->debug) $this->stasisLogger->debug("recordings has failed initialization");
                return TRUE;
            }
        }

        public function sounds()
        {
            try {
                $this->sounds = new sounds($this);
                if ($this->debug) $this->stasisLogger->debug("sounds class had been initiated");
                return $this->sounds;
            } catch (Exception $e) {

                if ($this->debug) $this->stasisLogger->debug("sounds has failed initialization");
                return TRUE;
            }
        }

        public function playbacks()
        {
            try {
                $this->playbacks = new playbacks($this);
                if ($this->debug) $this->stasisLogger->debug("playbacks class had been initiated");
                return $this->playbacks;
            } catch (Exception $e) {

                if ($this->debug) $this->stasisLogger->debug("playbacks has failed initialization");
                return TRUE;
            }
        }

    }
