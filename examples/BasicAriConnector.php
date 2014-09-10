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

    require_once "../vendor/autoload.php";
    require_once "examples-config.php";

    class BasicAriConnector
    {
        public function __construct()
        {
            $phpariObject = new phpari(ARI_USERNAME, ARI_PASSWORD, "hello-world", ARI_SERVER, ARI_PORT, ARI_ENDPOINT);

            $this->ariEndpoint  = $phpariObject->ariEndpoint;
            $this->stasisClient = $phpariObject->stasisClient;
            $this->stasisLoop   = $phpariObject->stasisLoop;
            $this->stasisLogger = $phpariObject->stasisLogger;


        }

        public function handlers()
        {
            try {
                $this->stasisClient->on("request", function ($headers) {
                    $this->stasisLogger->notice("Request received!");
                });

                $this->stasisClient->on("handshake", function () {
                    $this->stasisLogger->notice("Handshake received!");
                });

                $this->stasisClient->on("message", function ($message) {

                    print_r($message->getData());

                    $this->stasisLogger->notice($message->getData());
                });

            } catch (Exception $e) {
                echo $e->getMessage();
                exit(99);
            }
        }

        public function execute()
        {
            try {
                $this->stasisClient->open();
                $this->stasisLoop->run();

            } catch (Exception $e) {
                echo $e->getMessage();
                exit(99);
            }
        }

    }

    $basicAriClient = new BasicAriConnector();

    /**
     * Get some basic information from ARI
     */
    $ariAsterisk            = new asterisk($basicAriClient->ariEndpoint);
    $ariAsteriskInformation = $ariAsterisk->get_asterisk_info();
    $ariChannels            = new channels($basicAriClient);
    $ariAsteriskChannels    = $ariChannels->channel_list();

    print_r($ariAsteriskInformation);
    print_r($ariAsteriskChannels);

    $basicAriClient->handlers();
    $basicAriClient->execute();

    exit(0);