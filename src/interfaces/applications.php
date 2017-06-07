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

use GuzzleHttp\Client;

class applications // extends phpari
{

	private $phpariObject;
	public $ariEndpointClient;
	public $ariEndpointOptions;

	function __construct($connObject = NULL)
	{
		try {

			if (is_null($connObject) || is_null($connObject->ariEndpointURL))
				throw new Exception("Missing endpoint or empty string", 503);

			$this->phpariObject = $connObject;

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

	private function validate_event_sources($eventSources = null)
	{

		$eventsList = explode(",", $eventSources);

		foreach ($eventsList as $eventURI) {
			$eventSourceType = strtok($eventURI, ":");

			switch ($eventSourceType) {
				case "channel":
				case "bridge":
				case "endpoint":
				case "deviceState":
					break;
				default:
					throw new Exception("Unknown event type for URI " . $eventURI, 503);
					break;
			}
		}

		return 0;

	}

	/**
	 * GET List of all applications or information regarding a specific application name
	 *
	 * @param null $applicationName
	 *
	 * @return mixed
	 */
	public function show($applicationName = NULL)
	{
		try {

			$uri = "/applications";
			$uri .= (!is_null($applicationName)) ? "/" . $applicationName : "";

			$response = $this->ariEndpointClient->get($this->ariEndpointURL . $uri, $this->ariEndpointOptions)->getBody()->getContents();

			return json_decode($response);

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
	 * @return mixed
	 *
	 * This is just an alias - if you are using this, please stop - it will be deprecated!
	 */
	public function applications_list()
	{
		return $this->show();
	}

	/**
	 * @return mixed
	 *
	 * This is just an alias - if you are using this, please stop - it will be deprecated!
	 */
	public function application_details($applicationName = NULL)
	{
		return $this->show($applicationName);
	}

	/**
	 * Subscribe an application to a event source. Returns the state of the application after the subscriptions have changed
	 *
	 * @param string $applicationName
	 * @param string $eventSources
	 *
	 * @return mixed
	 */
	public function subscribe($applicationName = NULL, $eventSources = NULL)
	{
		try {

			if (is_null($applicationName))
				throw new Exception("Application name not provided or is null", 503);
			if (is_null($eventSources))
				throw new Exception("eventSources not provided or is null", 503);

			$this->validate_event_sources($eventSources);

			$postObjParams = array(
				'eventSource' => $eventSources
			);

			$uri = "/applications/" . $applicationName . "/subscription";
			$this->ariEndpointOptions['json'] = $postObjParams;

			$result = json_decode($this->ariEndpointClient->post($this->ariEndpointURL . $uri, $this->ariEndpointOptions)->getBody()->getContents());

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
	 * @param null $applicationName
	 * @param null $eventSourceURI
	 * @return mixed
	 *
	 * This is just an alias - if you are using this, please stop - it will be deprecated!
	 */
	public function application_subscribe($applicationName = NULL, $eventSourceURI = NULL)
	{
		return $this->subscribe($applicationName, $eventSourceURI);
	}

	/**
	 * DELETE Unsubscribe an application from an event source. Returns the state of the application after the subscriptions have changed
	 *
	 * @param string $applicationName
	 * @param string $eventSources
	 *
	 * @return mixed
	 */
	public function unsubscribe($applicationName = NULL, $eventSources = NULL)
	{
		try {

			if (is_null($applicationName))
				throw new Exception("Application name not provided or is null", 503);
			if (is_null($eventSources))
				throw new Exception("eventSources not provided or is null", 503);

			$this->validate_event_sources($eventSources);

			$postObjParams = array(
				'eventSource' => $eventSources
			);

			$uri = "/applications/" . $applicationName . "/subscription";

			$this->ariEndpointOptions['json'] = $postObjParams;

			$result = json_decode($this->ariEndpointClient->delete($this->ariEndpointURL . $uri, $this->ariEndpointOptions)->getBody()->getContents());


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
	 * @param null $applicationName
	 * @param null $eventSources
	 * @return mixed
	 *
	 * This is just an alias - if you are using this, please stop - it will be deprecated!
	 */
	public function application_unsubscribe($applicationName = NULL, $eventSources = NULL)
	{
		return $this->unsubscribe($applicationName, $eventSources);
	}

}





