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

        private $ariEndpoint;
        private $stasisClient;
        private $stasisLoop;
        private $stasisLogger;
        private $phpariObject;
        private $stasisChannelID;

        private $dtmfSequence = "";

        public function __construct()
        {
            $this->phpariObject = new phpari(ARI_USERNAME, ARI_PASSWORD, "hello-world", ARI_SERVER, ARI_PORT, ARI_ENDPOINT);

            $this->ariEndpoint  = $this->phpariObject->ariEndpoint;
            $this->stasisClient = $this->phpariObject->stasisClient;
            $this->stasisLoop   = $this->phpariObject->stasisLoop;
            $this->stasisLogger = $this->phpariObject->stasisLogger;
        }

        public function setDtmf($digit = null) {
            try {

                $this->dtmfSequence .= $digit;

                return true;

            } catch (Exception $e) {
                return false;
            }
        }

        public function handlers()
        {
            try {
                $stasisClientLocal = $this->stasisClient;
                $stasisLoggerLocal = $this->stasisLogger;

                $this->stasisClient->on("request", function ($headers) {
                    $this->stasisLogger->notice("Request received!");
                });

                $this->stasisClient->on("handshake", function () {
                    $this->stasisLogger->notice("Handshake received!");
                });

                $this->stasisClient->on("message", function ($message) {
                    $messageObject = json_decode($message->getData());
                    echo $messageObject->type . " Received \n";
                    switch ($messageObject->type) {
                        case "StasisStart";
                            echo "StasisStart";
                            $this->stasisChannelID = $messageObject->channel->id;
                            $this->phpariObject->channels()->channel_answer($this->stasisChannelID);
                            $this->phpariObject->channels()->channel_playback($this->stasisChannelID, 'sound:demo-thanks',null,null,null,'play1');
                            break;
                        case "StasisEnd":
                            echo "StasisEnd";
                            $this->phpariObject->channels()->channel_delete($this->stasisChannelID);
                            break;
                        case "PlaybackStarted":
                            echo "+++ PlaybackStarted +++ " . json_encode($messageObject->playback) . "\n";
                            break;
                        case "PlaybackFinished":
                            switch ($messageObject->playback->id) {
                                case "play1":
                                    $this->phpariObject->channels()->channel_playback($this->stasisChannelID, 'sound:demo-congrats',null,null,null,'play2');
                                    break;
                                case "play2":
                                    $this->phpariObject->channels()->channel_playback($this->stasisChannelID, 'sound:demo-echotest',null,null,null,'end');
                                    break;
                                case "end":
                                    $this->phpariObject->channels()->channel_continue($this->stasisChannelID);
                                    break;
                            }
                            break;
                        case "ChannelDtmfReceived":
                            $this->setDtmf($messageObject->digit);
                            echo "+++ DTMF Received +++ [" . $messageObject->digit . "] [" . $this->dtmfSequence. "]\n";
                            switch ($messageObject->digit) {
                                case "*":
                                    $this->dtmfSequence = "";
                                    echo "+++ Resetting DTMF buffer\n";
                                    break;
                                case "#":
                                    echo "+++ Playback ID: " . $this->phpariObject->playbacks()->get_playback();
                                    break;
                                default:
                                    break;
                            }
                            break;
                        default:
                            print_r($messageObject);
                            break;

                    }
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
    $basicAriClient->handlers();
    $basicAriClient->execute();

    exit(0);