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

        private $phpariObject;

        function __construct($connObject = NULL)
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
         * This function is an alias to 'info' - will be deprecated in phpari 2.0
         *
         * @param null $filter
         *
         * @return mixed
         */
        public function get_asterisk_info($filter = NULL)
        {
            return info($filter);
        }

        /**
         * @param null $filter
         *
         * @return mixed
         */
        public function info($filter = NULL)
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

                $result = $this->pestObject->get($uri);

                return $result;

            } catch (Exception $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            }

        }

        /**
         * @param null $variable
         *
         * @return bool
         */
        public function getGlobalVariable($variable = NULL)
        {
            try {

                $result = FALSE;

                if (is_null($variable))
                    throw new Exception("Global variable name not provided or is null", 503);

                $uri = "/asterisk/variable?variable=" . $variable;

                $jsonResult = $this->pestObject->get($uri);

                $result = $jsonResult->value;

                return $result;

            } catch (Exception $e) {
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            }
        }

        /*
         * This is an alias to setGlobalVariable
         */
        public function get_global_variable($variable = NULL)
        {
            $this->setGlobalVariable($variable);
        }

        /**
         * @param null $variable
         * @param null $value
         *
         * @return mixed
         */
        public function setGlobalVariable($variable = NULL, $value = NULL)
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
                $this->phpariObject->lasterror = $e->getMessage();
                $this->phpariObject->lasttrace = $e->getTraceAsString();

                return FALSE;
            }
        }

        /*
         * This is an alias to setGlobalVariable
         */
        public function set_global_variable($variable = NULL, $value = NULL)
        {
            $this->setGlobalVariable($variable, $value);
        }

		/**
		 * @param null $configClass
		 * @param null $objectType
		 * @param null $id
		 * @return bool
		 */
        public function getDynamicConfigurationObject($configClass = NULL, $objectType = NULL, $id = NULL)
		{
			try {

				$result = FALSE;

				if (is_null($configClass))
					throw new Exception("configClass variable name not provided or is null", 503);

				if (is_null($objectType))
					throw new Exception("objectType variable value not provided or is null", 503);

				if (is_null($id))
					throw new Exception("id variable value not provided or is null", 503);

				$uri      = "/asterisk/config/dynamic/" . $configClass . "/" . $objectType . "/" . $id;

				$result = $this->pestObject->get($uri);

				return $result;

			} catch (Exception $e) {
				$this->phpariObject->lasterror = $e->getMessage();
				$this->phpariObject->lasttrace = $e->getTraceAsString();

				return FALSE;
			}
		}

		/**
		 * @param null $configClass
		 * @param null $objectType
		 * @param null $id
		 * @return bool
		 */
		public function get_dynamic_configuration_object($configClass = NULL, $objectType = NULL, $id = NULL)
		{
			return $this->getDynamicConfigurationObject($configClass, $objectType, $id);
		}

		/**
		 * @param null $configClass
		 * @param null $objectType
		 * @param null $id
		 * @param null $fields
		 * @return bool
		 */
		public function putDynamicConfigurationObject($configClass = NULL, $objectType = NULL, $id = NULL, $fields = NULL)
		{
			try {

				$result = FALSE;

				if (is_null($configClass))
					throw new Exception("configClass variable name not provided or is null", 503);

				if (is_null($objectType))
					throw new Exception("objectType variable value not provided or is null", 503);

				if (is_null($id))
					throw new Exception("id variable value not provided or is null", 503);

				if (is_null($fields))
					throw new Exception("id variable value not provided or is null", 503);

				$uri      = "/asterisk/config/dynamic/" . $configClass . "/" . $objectType . "/" . $id;

				$result = $this->pestObject->put($uri, $fields);

				return $result;

			} catch (Exception $e) {
				$this->phpariObject->lasterror = $e->getMessage();
				$this->phpariObject->lasttrace = $e->getTraceAsString();

				return FALSE;
			}
		}

		/**
		 * @param null $configClass
		 * @param null $objectType
		 * @param null $id
		 * @param null $fields
		 * @return bool
		 */
		public function put_dynamic_configuration_object($configClass = NULL, $objectType = NULL, $id = NULL, $fields = NULL)
		{
			return $this->putDynamicConfigurationObject($configClass, $objectType, $id);
		}

		/**
		 * @param null $configClass
		 * @param null $objectType
		 * @param null $id
		 * @return bool
		 */
		public function deleteDynamicConfigurationObject($configClass = NULL, $objectType = NULL, $id = NULL)
		{
			try {

				$result = FALSE;

				if (is_null($configClass))
					throw new Exception("configClass variable name not provided or is null", 503);

				if (is_null($objectType))
					throw new Exception("objectType variable value not provided or is null", 503);

				if (is_null($id))
					throw new Exception("id variable value not provided or is null", 503);


				$uri      = "/asterisk/config/dynamic/" . $configClass . "/" . $objectType . "/" . $id;

				$result = $this->pestObject->delete($uri);

				return $result;

			} catch (Exception $e) {
				$this->phpariObject->lasterror = $e->getMessage();
				$this->phpariObject->lasttrace = $e->getTraceAsString();

				return FALSE;
			}
		}

		/**
		 * @param null $configClass
		 * @param null $objectType
		 * @param null $id
		 * @return bool
		 */
		public function delete_dynamic_configuration_object($configClass = NULL, $objectType = NULL, $id = NULL)
		{
			return $this->deleteDynamicConfigurationObject($configClass, $objectType, $id);
		}

		/**
		 * @param string $action
		 * @param null $module_name
		 * @return bool
		 */
		private function ari_module_handler($action = "get", $module_name = NULL)
		{
			try {

				$result = FALSE;

				$uri      = "/asterisk/modules" . $module_name;

				switch ($action) {
					case "get":
						$result = $this->pestObject->get($uri);
						break;
					case "load":

						if (is_null($module_name))
							throw new Exception("module_name variable name not provided or is null", 503);

						$result = $this->pestObject->post($uri);
						break;
					case "unload":

						if (is_null($module_name))
							throw new Exception("module_name variable name not provided or is null", 503);

						$result = $this->pestObject->delete($uri);
						break;
					case "reload":

						if (is_null($module_name))
							throw new Exception("module_name variable name not provided or is null", 503);

						$result = $this->pestObject->put($uri);
						break;
				}

				return $result;

			} catch (Exception $e) {
				$this->phpariObject->lasterror = $e->getMessage();
				$this->phpariObject->lasttrace = $e->getTraceAsString();

				return FALSE;
			}
		}

		/**
		 * @param null $module_name
		 * @return bool
		 */
		public function getModule($module_name = NULL)
		{
			return $this->ari_module_handler("get", $module_name);
		}

		/**
		 * @param null $module_name
		 * @return bool
		 */
		public function get_module($module_name = NULL)
		{
			return $this->getModule($module_name);
		}

		/**
		 * @param null $module_name
		 * @return bool
		 */
		public function loadModule($module_name = NULL)
		{
			return $this->ari_module_handler("load", $module_name);
		}

		/**
		 * @param null $module_name
		 * @return bool
		 */
		public function load_module($module_name = NULL)
		{
			return $this->loadModule($module_name);
		}

		/**
		 * @param null $module_name
		 * @return bool
		 */
		public function unloadModule($module_name = NULL)
		{
			return $this->ari_module_handler("unload", $module_name);
		}

		/**
		 * @param null $module_name
		 * @return bool
		 */
		public function unload_module($module_name = NULL)
		{
			return $this->unloadModule($module_name);
		}

		/**
		 * @param null $module_name
		 * @return bool
		 */
		public function reloadModule($module_name = NULL)
		{
			return $this->ari_module_handler("reload", $module_name);
		}

		/**
		 * @param null $module_name
		 * @return bool
		 */
		public function reload_module($module_name = NULL)
		{
			return $this->reloadModule($module_name);
		}

		public function getLogChannels()
		{
			try {

				$result = FALSE;

				$uri      = "/asterisk/logging";

				$result = $this->pestObject->get($uri);

				return $result;

			} catch (Exception $e) {
				$this->phpariObject->lasterror = $e->getMessage();
				$this->phpariObject->lasttrace = $e->getTraceAsString();

				return FALSE;
			}
		}

		public function get_log_channels()
		{
			return $this->getLogChannels();
		}


		public function addLogChannel($log_channel_name = NULL, $configuration = NULL)
		{
			try {

				$result = FALSE;

				if (is_null($log_channel_name))
					throw new Exception("log_channel_name variable name not provided or is null", 503);

				if (is_null($configuration))
					throw new Exception("configuration variable name not provided or is null", 503);

				$uri      = "/asterisk/logging/" . $log_channel_name;

				$result = $this->pestObject->post($uri, $configuration);

				return $result;

			} catch (Exception $e) {
				$this->phpariObject->lasterror = $e->getMessage();
				$this->phpariObject->lasttrace = $e->getTraceAsString();

				return FALSE;
			}
		}

		public function add_log_channel($log_channel_name = NULL, $configuration = NULL)
		{
			return $this->addLogChannel($log_channel_name, $configuration);
		}

		public function rotateLogChannel($log_channel_name = NULL)
		{
			try {

				$result = FALSE;

				if (is_null($log_channel_name))
					throw new Exception("log_channel_name variable name not provided or is null", 503);


				$uri      = "/asterisk/logging/" . $log_channel_name . "/rotate";

				$result = $this->pestObject->put($uri);

				return $result;

			} catch (Exception $e) {
				$this->phpariObject->lasterror = $e->getMessage();
				$this->phpariObject->lasttrace = $e->getTraceAsString();

				return FALSE;
			}
		}

		public function rotate_log_channel($log_channel_name = NULL)
		{
			return $this->rotateLogChannel($log_channel_name);

		}

		public function deleteLogChannel($log_channel_name = NULL)
		{
			try {

				$result = FALSE;

				if (is_null($log_channel_name))
					throw new Exception("log_channel_name variable name not provided or is null", 503);

				$uri      = "/asterisk/logging/" . $log_channel_name;

				$result = $this->pestObject->delete($uri);

				return $result;

			} catch (Exception $e) {
				$this->phpariObject->lasterror = $e->getMessage();
				$this->phpariObject->lasttrace = $e->getTraceAsString();

				return FALSE;
			}
		}

		public function delete_log_channel($log_channel_name = NULL)
		{
			return $this->deleteLogChannel($log_channel_name);
		}

    }