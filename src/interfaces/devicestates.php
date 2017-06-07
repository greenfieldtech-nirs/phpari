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
 **/

use GuzzleHttp\Client;

class devicestates //extends phpari
{
	private $phpariObject;

	function __construct($connObject = NULL)
	{
		try {

			if (is_null($connObject) || is_null($connObject->ariEndpoint))
				throw new Exception("Missing PestObject or empty string", 503);

			$this->phpariObject = $connObject;
			$this->pestObject = $connObject->ariEndpoint;

			$this->ariEndpointURL = $connObject->ariEndpointURL;

			$this->ariEndpointClient = new Client([
				'base_uri' => $this->ariEndpointURL,
				'timeout' => 2.0
			]);

			$this->ariEndpointOptions = [
				'debug' => false,
				'auth' => [
					$connObject->ariUsername,
					$connObject->ariPassword
				]
			];

		} catch (Exception $e) {
			die("Exception raised: " . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine());
		}
	}


	/**
	 * GET /deviceStates
	 * Get a list of current device states, or the device state for a specific device name
	 *
	 * @return bool
	 */
	public function show($deviceName = NULL)
	{
		try {

			if (is_null($this->pestObject))
				throw new Exception("PEST Object not provided or is null", 503);

			$uri = (is_null($deviceName)) ? "/deviceStates" : "/deviceStates/" . $deviceName;
			$result = $this->pestObject->get($uri);
			return $result;

		} catch (\GuzzleHttp\Exception\ClientException $e) {
			$this->phpariObject->lasterror = $e->getMessage();
			$this->phpariObject->lasttrace = $e->getTraceAsString();
			return (int)$e->getCode();
		} catch (\GuzzleHttp\Exception\ServerException $e) {
			$this->phpariObject->lasterror = $e->getMessage();
			$this->phpariObject->lasttrace = $e->getTraceAsString();
			return (int)$e->getCode();
		} catch (\GuzzleHttp\Exception\RequestException $e) {
			$this->phpariObject->lasterror = $e->getMessage();
			$this->phpariObject->lasttrace = $e->getTraceAsString();
			return (int)$e->getCode();
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
	public function devicestates_list()
	{
		return $this->show();
	}

	/**
	 * This function is an alias to 'show' - will be deprecated in phpari 2.0
	 *
	 * @return mixed
	 */
	public function devicestate_currentstate($deviceName = NULL)
	{
		return $this->show($deviceName);
	}

	/**
	 *
	 *  PUT /deviceStates/{deviceName}
	 *  Change the state of a device controlled by ARI.
	 *  (Note - implicitly creates the device state).
	 *
	 *
	 * @param null $deviceName
	 * @param null $deviceState
	 * @return bool
	 */
	public function set($deviceName = NULL, $deviceState = NULL)
	{
		try {

			if (is_null($deviceName))
				throw new Exception("Device name is not provided or is null", 503);
			if (is_null($deviceState))
				throw new Exception("Device state name is  not provided or is null", 503);

			$putObj = array(
				'deviceState' => $deviceState
			);

			$uri = "/deviceStates/" . $deviceName;
			$result = $this->pestObject->put($uri, $putObj);

			return $result;

		} catch (\GuzzleHttp\Exception\ClientException $e) {
			$this->phpariObject->lasterror = $e->getMessage();
			$this->phpariObject->lasttrace = $e->getTraceAsString();
			return (int)$e->getCode();
		} catch (\GuzzleHttp\Exception\ServerException $e) {
			$this->phpariObject->lasterror = $e->getMessage();
			$this->phpariObject->lasttrace = $e->getTraceAsString();
			return (int)$e->getCode();
		} catch (\GuzzleHttp\Exception\RequestException $e) {
			$this->phpariObject->lasterror = $e->getMessage();
			$this->phpariObject->lasttrace = $e->getTraceAsString();
			return (int)$e->getCode();
		} catch (Exception $e) {
			$this->phpariObject->lasterror = $e->getMessage();
			$this->phpariObject->lasttrace = $e->getTraceAsString();
			return FALSE;
		}
	}

	/**
	 * This function is an alias to 'set' - will be deprecated in phpari 2.0
	 *
	 * @return mixed
	 */
	public function devicestate_changestate($deviceName = NULL, $deviceState = NULL)
	{
		return $this->set($deviceName, $deviceState);
	}


	/**
	 *  DELETE /deviceStates/{deviceName}
	 *  Destroy a device-state controlled by ARI.
	 *
	 * @param null $deviceName
	 * @return bool
	 */
	public function remove($deviceName = NULL)
	{
		try {

			if (is_null($deviceName))
				throw new Exception("Device name is not provided or is null", 503);

			$uri = "/deviceStates/" . $deviceName;
			$result = $this->pestObject->delete($uri);

			return $result;

		} catch (\GuzzleHttp\Exception\ClientException $e) {
			$this->phpariObject->lasterror = $e->getMessage();
			$this->phpariObject->lasttrace = $e->getTraceAsString();
			return (int)$e->getCode();
		} catch (\GuzzleHttp\Exception\ServerException $e) {
			$this->phpariObject->lasterror = $e->getMessage();
			$this->phpariObject->lasttrace = $e->getTraceAsString();
			return (int)$e->getCode();
		} catch (\GuzzleHttp\Exception\RequestException $e) {
			$this->phpariObject->lasterror = $e->getMessage();
			$this->phpariObject->lasttrace = $e->getTraceAsString();
			return (int)$e->getCode();
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
	public function devicestate_deletestate($deviceName = NULL)
	{
		return $this->remove($deviceName);
	}
}


