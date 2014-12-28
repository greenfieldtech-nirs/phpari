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

class parsing_helper {

    /**
     *
     */
    function __construct()
    {
        try {
            return false;
        } catch (Exception $e) {
            die("Exception raised: " . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine());
        }
    }

    /**
     * Ascertain if the input provided by $rawInput is one of the following: JSON_STRING, JSON_OBJECT, ASSOC_ARRAY.
     * The return value shall always be an ASSOC_ARRAY, represting $rawInput in a unified manner
     *
     * @param null $rawInput
     */
    function parseRequestData($rawInput = NULL)
    {
        try {

            if ($rawInput == NULL)
                throw new Exception ("Input must be defined", 503);

            $result = array();

            if (is_string($rawInput))
                $result = json_decode($rawInput, TRUE);

            if (is_array($rawInput))
                $result = $rawInput;

            if (is_object($rawInput))
                $result = json_decode(json_encode($rawInput), TRUE);

            return $result;

        } catch (Exception $e) {
            die("Exception raised: " . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine());
        }
    }

} 