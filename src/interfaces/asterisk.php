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
class asterisk // extends phpari
    {

        function __construct($pestObject = NULL)
        {
            try {

                if (is_null($pestObject))
                    throw new Exception("Missing PestObject or empty string", 503);

                $this->pestObject = $pestObject;

            } catch (Exception $e) {
                die("Exception raised: " . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine());
            }
        }

        public function get_asterisk_info($filter = NULL)
        {

            try {

                $result = FALSE;

                switch ($filter) {
                    case "build":
                    case "system":
                    case "config":
                    case "status":
                        break;
                    default:
                        $filter = NULL;
                        break;
                }

                $uri = "/asterisk/info";
                $uri .= (!is_null($filter)) ? '?only=' . $filter : '';

                $result = json_decode($this->pestObject->get($uri));

                return $result;

            } catch (Exception $e) {
                return FALSE;
            }

        }

        public function get_global_variable($variable = NULL)
        {

            try {

                $result = FALSE;

                if (is_null($variable))
                    throw new Exception("Global variable name not provided or is null", 503);

                $uri = "/asterisk/variable?variable=" . $variable;

                $jsonResult = json_decode($this->pestObject->get($uri));

                $result = $jsonResult->value;

                return $result;

            } catch (Exception $e) {
                return FALSE;
            }

        }

        public function set_global_variable($variable = NULL, $value = NULL)
        {

            try {

                $result = FALSE;

                if (is_null($variable))
                    throw new Exception("Global variable name not provided or is null", 503);

                if (is_null($value))
                    throw new Exception("Global variable value not provided or is null", 503);

                $uri      = "/asterisk/variable";
                $postData = array("variable" => $variable, "value" => $value);

                $result = $this->pestObject->post($uri, $postData);

                return $result;

            } catch (Exception $e) {
                return FALSE;
            }

        }
    }