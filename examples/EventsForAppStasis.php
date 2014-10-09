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

    class EventsForAppStasis
    {

        private $conn;
        private $events;

        public function __construct()
        {
            $this->conn   = new phpari("hello-world"); //create new object
            $this->events = new events($this->conn);


        }

        public function handlers()
        {
            try {
                $this->conn->stasisClient->on("request", function ($headers) {
                    $this->conn->stasisLogger->notice("Request received!");
                });

                $this->conn->stasisClient->on("handshake", function () {
                    $this->conn->stasisLogger->notice("Handshake received!");
                });

                $this->conn->stasisClient->on("message", function ($message) {


                    $this->stasisLogger->notice(json_encode($message));
                    $this->stasisLogger->notice($this->events->events('hello-world'));
                });

            } catch (Exception $e) {
                echo $e->getMessage();
                exit(99);
            }
        }

        public function execute()
        {
            try {
                $this->conn->stasisClient->open();
                $this->conn->stasisLoop->run();

            } catch (Exception $e) {
                echo $e->getMessage();
                exit(99);
            }
        }

    }

    $eventForApp = new EventsForAppStasis();
    $eventForApp->handlers();
    $eventForApp->execute();

    exit(0);