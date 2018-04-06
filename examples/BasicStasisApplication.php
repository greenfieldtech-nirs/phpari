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

    /* DO NOT MODIFY THIS PART, YOU WILL BREAK THIS! */
    $pathinfo = pathinfo($_SERVER['PHP_SELF']);
    $dir = $pathinfo['dirname'] . "/";
    require_once $dir . "../../vendor/autoload.php";


    /* START YOUR MODIFICATIONS HERE */
    class BasicStasisApplication
    {

        private $ariEndpoint;
        private $stasisClient;
        private $stasisLoop;
        private $phpariObject;
        private $stasisChannelID;
        private $dtmfSequence = "";

        public $stasisLogger;

        public function __construct($appname = NULL)
        {
            try {
                if (is_null($appname))
                    throw new Exception("[" . __FILE__ . ":" . __LINE__ . "] Stasis application name must be defined!", 500);

                $this->phpariObject = new \phpari\phpari($appname);

                $this->ariEndpoint  = $this->phpariObject->ariEndpoint;
                $this->stasisClient = $this->phpariObject->stasisClient;
                $this->stasisLoop   = $this->phpariObject->stasisLoop;
                $this->stasisLogger = $this->phpariObject->stasisLogger;
                $this->stasisEvents = $this->phpariObject->stasisEvents;
            } catch (Exception $e) {
                echo $e->getMessage();
                exit(99);
            }
        }

        public function setDtmf($digit = NULL)
        {
            try {

                $this->dtmfSequence .= $digit;

                return TRUE;

            } catch (Exception $e) {
                return FALSE;
            }
        }

        // process stasis events
        public function StasisAppEventHandler()
        {
            $this->stasisEvents->on('StasisStart', function ($event) {
                $this->stasisLogger->notice("Event received: StasisStart");
                $this->stasisChannelID = $event->channel->id;
                $this->phpariObject->channels()->channel_answer($this->stasisChannelID);
                $this->phpariObject->channels()->channel_playback($this->stasisChannelID, 'sound:demo-thanks', NULL, NULL, NULL, 'play1');
            });

            $this->stasisEvents->on('StasisEnd', function ($event) {
                /*
                 * The following section will produce an error, as the channel no longer exists in this state - this is intentional
                 */
                $this->stasisLogger->notice("Event received: StasisEnd");
                if (!$this->phpariObject->channels()->channel_delete($this->stasisChannelID))
                    $this->stasisLogger->notice("Error occurred: " . $this->phpariObject->lasterror);
            });


            $this->stasisEvents->on('PlaybackStarted', function ($event) {
                $this->stasisLogger->notice("+++ PlaybackStarted +++ " . json_encode($event->playback) . "\n");
            });

            $this->stasisEvents->on('PlaybackFinished', function ($event) {
                switch ($event->playback->id) {
                    case "play1":
                        $this->phpariObject->channels()->channel_playback($this->stasisChannelID, 'sound:demo-congrats', NULL, NULL, NULL, 'play2');
                        break;
                    case "play2":
                        $this->phpariObject->channels()->channel_playback($this->stasisChannelID, 'sound:demo-echotest', NULL, NULL, NULL, 'end');
                        break;
                    case "end":
                        $this->phpariObject->channels()->channel_continue($this->stasisChannelID);
                        break;
                }
            });

            $this->stasisEvents->on('ChannelDtmfReceived', function ($event) {
                $this->setDtmf($event->digit);
                $this->stasisLogger->notice("+++ DTMF Received +++ [" . $event->digit . "] [" . $this->dtmfSequence . "]\n");
                switch ($event->digit) {
                    case "*":
                        $this->dtmfSequence = "";
                        $this->stasisLogger->notice("+++ Resetting DTMF buffer\n");
                        break;
                    case "#":
                        $this->stasisLogger->notice("+++ Playback ID: " . $this->phpariObject->playbacks()->get_playback());
                        $this->phpariObject->channels()->channel_continue($this->stasisChannelID, "demo", "s", 1);
                        break;
                    default:
                        break;
                }
            });
        }

        public function StasisAppConnectionHandlers()
        {
            try {
                $this->stasisClient->on("request", function ($headers) {
                    $this->stasisLogger->notice("Request received!");
                });

                $this->stasisClient->on("handshake", function () {
                    $this->stasisLogger->notice("Handshake received!");
                });

                $this->stasisClient->on("message", function ($message) {
                    $event = json_decode($message->getData());
                    $this->stasisLogger->notice('Received event: ' . $event->type);
                    $this->stasisEvents->emit($event->type, array($event));
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

    $basicAriClient = new BasicStasisApplication("hello-world");

    $basicAriClient->stasisLogger->info("Starting Stasis Program... Waiting for handshake...");
    $basicAriClient->StasisAppEventHandler();

    $basicAriClient->stasisLogger->info("Initializing Handlers... Waiting for handshake...");
    $basicAriClient->StasisAppConnectionHandlers();

    $basicAriClient->stasisLogger->info("Connecting... Waiting for handshake...");
    $basicAriClient->execute();

    exit(0);