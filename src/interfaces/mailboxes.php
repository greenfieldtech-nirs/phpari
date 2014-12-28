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
    class mailboxes //extends phpari
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
         * GET /mailboxes
         * List all mailboxes.
         */
        public function show($mailbox = NULL)
        {
            try {

                $uri    = (is_null($mailbox)) ? "/mailboxes" : "/mailboxes/" . $mailBoxName;
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
        public function mailboxes_list()
        {
            return $this->show();
        }


        /**
         * This function is an alias to 'show' - will be deprecated in phpari 2.0
         *
         * @return mixed
         */
        public function mailbox_state($mailbox = NULL)
        {
            return $this->show($mailbox);
        }


        /**
         * PUT /mailboxes/{mailboxName}
         * Change the state of a mailbox. (Note - implicitly creates the mailbox).
         *
         * @param       int - (required) Count of old messages in the mailbox
         * @param       int - (required) Count of new messages in the mailbox
         * @param  null $newMessages
         *
         * @return bool
         */
        public function state($mailBoxName = NULL, $oldMessages = NULL, $newMessages = NULL)
        {
            try {

                if (is_null($mailBoxName))
                    throw new Exception("Mail box name is  not provided or is null", 503);

                if (is_null($oldMessages))
                    throw new Exception("Old messages is  not provided or is null", 503);

                if (is_null($newMessages))
                    throw new Exception("New messages is  not provided or is null", 503);

                $uri = "/mailboxes/" . $mailBoxName;

                $putObj = array(
                    'oldMessages' => $oldMessages,
                    'newMessages' => $newMessages

                );
                $result = $this->pestObject->put($uri, $putObj);

                return $result;


            } catch (Exception $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            }
        }

        /**
         * This function is an alias to 'state' - will be deprecated in phpari 2.0
         *
         * @return mixed
         */
        public function mailbox_change_state($mailBoxName = NULL, $oldMessages = NULL, $newMessages = NULL)
        {
            return $this->state($mailBoxName, $oldMessages, $newMessages);
        }

        /**
         * DELETE /mailboxes/{mailboxName}
         * Destroy a mailbox.
         */
        public function remove($mailbox = NULL)
        {
            try {

                if (is_null($mailbox))
                    throw new Exception("Mail box name is  not provided or is null", 503);

                $uri    = "/mailboxes/" . $mailbox;
                $result = $this->pestObject->delete($uri);

                return $result;

            } catch (Exception $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            }
        }

        /**
         * This function is an alias to 'remove' - will be deprecated in phpari 2.0
         *
         * @return mixed
         */
        public function mailbox_destroy($mailbox = NULL)
        {
            $this->remove($mailbox);
        }

    }