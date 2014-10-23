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
class sounds //extends phpari
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
     * GET /sounds
     * List all sounds.
     *
     * @param string $lang - Lookup sound for a specific language.
     * @param string $format - Lookup sound in a specific format.
     * @return bool
     */
    public function   sounds_list($lang = null, $format = null)
    {
        try {

            if (is_null($lang))
                throw new Exception("Language  is  not provided or is null", 503);
            if (is_null($format))
                throw new Exception("Language  is  not provided or is null", 503);

            $uri = "/sounds";
            $getOBJ = array(
                'lang' => $lang,
                'format' => $format
            );

            $result = $this->pestObject->get($uri, $getOBJ);
            return $result;

        } catch (Exception $e) {
            $this->phpariObject->lasterror = $e->getMessage();
            $this->phpariObject->lasttrace = $e->getTraceAsString();
            return false;
        }
    }

    /**
     *
     * GET /sounds/{soundId}
     * Get a sound's details.
     *
     * @param null $soundID
     * @return bool
     */
    public function   sound_detail($soundID = null)
    {
        try {
            if (is_null($soundID))
                throw new Exception("Sound id is  not provided or is null", 503);

            $uri = "/sounds/" . $soundID;
            $result = $this->pestObject->get($uri);
            return $result;

        } catch (Exception $e) {
            $this->phpariObject->lasterror = $e->getMessage();
            $this->phpariObject->lasttrace = $e->getTraceAsString();
            return false;
        }
    }


    /**
     * PUT /mailboxes/{mailboxName}
     * Change the state of a mailbox. (Note - implicitly creates the mailbox).
     *
     * @param  int - (required) Count of old messages in the mailbox
     * @param  int - (required) Count of new messages in the mailbox
     * @param  null $newMessages
     * @return bool
     */
    public function   mailbox_change_state($mailBoxName = null, $oldMessages = null, $newMessages = null)
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
            return false;
        }
    }

    /**
     * DELETE /mailboxes/{mailboxName}
     * Destroy a mailbox.
     */
    public function   mailbox_destroy($mailBoxName = null)
    {
        try {

            if (is_null($mailBoxName))
                throw new Exception("Mail box name is  not provided or is null", 503);

            $uri = "/mailboxes/" . $mailBoxName;
            $result = $this->pestObject->delete($uri);
            return $result;


        } catch (Exception $e) {
            $this->phpariObject->lasterror = $e->getMessage();
            $this->phpariObject->lasttrace = $e->getTraceAsString();
            return false;
        }
    }


}