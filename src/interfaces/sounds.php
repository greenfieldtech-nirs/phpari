<?php

namespace phpari\interfaces;

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
    private $pestObject;

    function __construct($connObject = NULL)
    {
        try {

            if (is_null($connObject) || is_null($connObject->ariEndpoint))
                throw new \Exception("Missing PestObject or empty string", 503);

            $this->phpariObject = $connObject;
            $this->pestObject = $connObject->ariEndpoint;

        } catch (\Exception $e) {
            die("Exception raised: " . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine());
        }
    }
    
    /**
     * GET /sounds
     * List all sounds.
     *
     * @param string $lang - Lookup sound for a specific language.
     * @param string $format - Lookup sound in a specific format.
     * @param string $soundID - The specific sound ID you would like to get details for
     * @return bool
     */
    public function show($lang = NULL, $format = NULL, $soundID = NULL)
    {
        try {

            $uri = "/sounds";
            $uri .= (!is_null($soundID))?"/" . $soundID:"";

            $getOBJ = array();

            if (!is_null($lang))
                $getOBJ['lang'] = $lang;

            if (!is_null($format))
                $getOBJ['format'] = $format;

            $result = $this->pestObject->get($uri, $getOBJ);
            return $result;

        } catch (\Exception $e) {
            $this->phpariObject->lasterror = $e->getMessage();
            $this->phpariObject->lasttrace = $e->getTraceAsString();
            return false;
        }
    }

    /**
     * This function is an alias to 'show' - will be deprecated in phpari 2.0
     *
     * @return mixed
     */
    public function sounds_list($lang = NULL, $format = NULL)
    {
        return $this->show($lang, $format);
    }

    /**
     *
     * GET /sounds/{soundId}
     * Get a sound's details.
     *
     * @param null $soundID
     * @return bool
     */
    public function details($soundID = NULL)
    {
        try {

            if (is_null($soundID))
                throw new \Exception("Sound ID not provided or is null", 503);

            return $this->show(NULL, NULL, $soundID);

        } catch (\Exception $e) {
            $this->phpariObject->lasterror = $e->getMessage();
            $this->phpariObject->lasttrace = $e->getTraceAsString();
            return false;
        }
    }


    /**
     * This function is an alias to 'details' - will be deprecated in phpari 2.0
     *
     * @return mixed
     */
    public function sound_detail($soundID = NULL)
    {
        return $this->details($soundID);
    }
}