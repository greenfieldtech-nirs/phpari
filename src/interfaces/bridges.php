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

class bridges // extends phpari
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
	 * GET /bridges
	 * List all active bridges in Asterisk.
	 *
	 * @return bool
	 */
	public function show()
	{
		try {

			if (is_null($this->pestObject))
				throw new Exception("PEST Object not provided or is null", 503);

			$uri = "/bridges";
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
	public function bridges_list()
	{
		return $this->show();
	}

	/**
	 * POST /bridges
	 * Create or Update a new or existing ARI bridge.
	 * This bridge persists until it has been shut down, or Asterisk has been shut down.
	 *
	 * Please note, ARI bridges have nothing to do with ConfBridge or native Asterisk bridging
	 *
	 * @param null $type - Comma separated list of bridge type attributes (mixing, holding, dtmf_events, proxy_media).
	 * @param null $bridgeId - Unique ID to give to the bridge being created.
	 * @param null $name - Name to give to the bridge being created.
	 *
	 * @return bool
	 */
	public function create($type = NULL, $bridgeId = NULL, $name = NULL)
	{
		try {

			$postOBJ = array();

			if (!is_null($type))
				$postOBJ['type'] = $type;

			if (!is_null($name))
				$postOBJ['name'] = $name;

			$uri = (is_null($bridgeId)) ? "/bridges" : "/bridges/" . $bridgeId;

			$result = $this->pestObject->post($uri, $postOBJ);

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
	 * This function is an alias to 'create' - will be deprecated in phpari 2.0
	 *
	 * @return mixed
	 */
	public function bridge_create($type = NULL, $bridgeId = NULL, $name = NULL)
	{
		return $this->create($type, $bridgeId, $name);
	}


	/**
	 * This function is an alias to 'create' - will be deprecated in phpari 2.0
	 *
	 * @return mixed
	 */
	public function bridge_create_update($type = NULL, $bridgeId = NULL, $name = NULL)
	{
		return $this->create($type, $bridgeId, $name);
	}

	/**
	 * GET /bridges/{bridgeId}
	 * Get bridge details.
	 *
	 * @param null $bridgeId
	 *
	 * @return bool
	 */
	public function details($bridgeId = NULL)
	{

		try {

			if (is_null($bridgeId))
				throw new Exception("BridgeID not provided or is null", 503);

			$uri = "/bridges/" . $bridgeId;
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
	 * This function is an alias to 'details' - will be deprecated in phpari 2.0
	 *
	 * @return mixed
	 */
	public function bridge_details($bridgeId = NULL)
	{
		$this->bridge_details($bridgeId);
	}

	/**
	 * DELETE /bridges/{bridgeId}
	 * Shut down a bridge.
	 * If any channels are in this bridge,
	 * they will be removed and resume whatever they were doing beforehand.
	 *
	 * @param null $bridgeId
	 *
	 * @return bool
	 */
	public function terminate($bridgeId = NULL)
	{
		try {

			if (is_null($bridgeId))
				throw new Exception("BridgeID not provided or is null", 503);

			$uri = "/bridges/" . $bridgeId;
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
	 * This function is an alias to 'terminate' - will be deprecated in phpari 2.0
	 *
	 * @return mixed
	 */
	public function bridge_delete($bridgeId = NULL)
	{
		return $this->terminate($bridgeId);
	}

	/**
	 *
	 * POST /bridges/{bridgeId}/addChannel
	 * Add a channel to a bridge.
	 *
	 * @param null $bridgeId - Bridge's id
	 * @param null $channel - (required) Ids of channels to add to bridge
	 * @param null $role - Channel's role in the bridge
	 *
	 * @return bool
	 *
	 * NOTE: Change in API occured here, old addchannel function is now deprecated
	 */
	public function addChannel($bridgeId = NULL, $channel = NULL, $role = NULL)
	{
		try {

			if (is_null($bridgeId))
				throw new Exception("BridgeID is not provided or is null", 503);

			if (is_null($channel))
				throw new Exception("Channel is not provided or is null", 503);

			$postObj = array();
			$postObj['channel'] = $channel;

			if (!is_null($role))
				$postObj['role'] = $role;

			$uri = "/bridges/" . $bridgeId . "/addChannel";
			$result = $this->pestObject->post($uri, $postObj);

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
	 * This function is an alias to 'addChannel' - will be deprecated in phpari 2.0
	 *
	 * @return mixed
	 */
	public function bridge_addchannel($bridgeId = NULL, $channel = NULL, $role = NULL)
	{
		return $this->addChannel($bridgeId, $channel, $role);
	}

	/**
	 *
	 * POST /bridges/{bridgeId}/removeChannel
	 * Remove a channel to a bridge.
	 *
	 * @param null $bridgeId - Bridge's id
	 * @param null $channel - (required) Ids of channels to add to bridge
	 *
	 * @return bool
	 */
	public function removeChannel($bridgeId = NULL, $channel = NULL)
	{
		try {

			if (is_null($bridgeId))
				throw new Exception("BridgeID is not provided or is null", 503);

			if (is_null($channel))
				throw new Exception("Channel is not provided or is null", 503);

			$delObj = array(
				'channel' => $channel,
			);

			$uri = "/bridges/" . $bridgeId . "/removeChannel";
			$result = $this->pestObject->post($uri, $delObj);

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
	 * This function is an alias to 'removechannel' - will be deprecated in phpari 2.0
	 *
	 * @return mixed
	 */
	public function bridge_remove_channel($bridgeId = NULL, $channel = NULL)
	{
		return $this->removeChannel($bridgeId, $channel);
	}

	/**
	 * This function is an alias to 'removeChannel' - it's here for backward compatibility only
	 *
	 * @return mixed
	 */
	public function delchannel($bridgeId = NULL, $channel = NULL)
	{
		return $this->removeChannel($bridgeId, $channel);
	}

	/**
	 *
	 * POST /bridges/{bridgeId}/moh
	 * Play music on hold to a bridge or change the MOH class that is playing.
	 *
	 * @param null $bridgeId
	 * @param null $mohClass
	 *
	 * @return bool
	 */
	public function mohStart($bridgeId = NULL, $mohClass = NULL)
	{
		try {

			if (is_null($bridgeId))
				throw new Exception("BridgeID is not provided or is null", 503);

			$postObj = array();
			if (!is_null($mohClass))
				$postObj['mohClass'] = $mohClass;

			$uri = '/bridges/' . $bridgeId . '/moh';
			$result = $this->pestObject->post($uri, $postObj);

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
	 * This function is an alias to 'moh_start' - will be deprecated in phpari 2.0
	 *
	 * @return mixed
	 */
	public function bridge_play_moh($bridgeId = NULL, $mohClass = NULL)
	{
		return $this->moh_start($bridgeId, $mohClass);
	}

	/**
	 *
	 * DELETE /bridges/{bridgeId}/moh
	 * Stop playing music on hold to a bridge.
	 * This will only stop music on hold being played via POST bridges/{bridgeId}/moh.
	 *
	 * @param null $bridgeId - Bridge's id
	 *
	 * @return bool
	 */
	public function mohStop($bridgeId = NULL)
	{
		try {

			if (is_null($bridgeId))
				throw new Exception("BridgeID is not provided or is null", 503);

			$uri = '/bridges/' . $bridgeId . '/moh';
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
	 * This function is an alias to 'moh_stop' - will be deprecated in phpari 2.0
	 *
	 * @return mixed
	 */
	public function bridge_stop_moh($bridgeId = NULL)
	{
		return $this->moh_stop($bridgeId);
	}

	/**
	 * POST /bridges/{bridgeId}/play
	 * Start playback of media on a bridge.
	 * The media URI may be any of a number of URI's. Currently sound:,
	 * recording:, number:, digits:, characters:, and tone: URI's are supported.
	 * This operation creates a playback resource that can be used to control the playback of media (pause, rewind, fast forward, etc.)
	 *
	 * @param null $bridgeId - Bridge's id
	 * @param string $media - (required) Media's URI to play.
	 * @param string $lang - For sounds, selects language for sound.
	 * @param int $offsetms - Number of media to skip before playing.
	 * @param int $skipms - Number of milliseconds to skip for forward/reverse operations.
	 * @param null $playbackId - Playback Id.
	 *
	 * @return bool
	 */
	public function playbackStart($bridgeId = NULL, $media = NULL, $lang = NULL, $offsetms = NULL, $skipms = NULL, $playbackId = NULL)
	{
		try {

			if (is_null($bridgeId))
				throw new Exception("BridgeID is not provided or is null", 503);
			if (is_null($media))
				throw new Exception("Media representation is not provided or is null", 503);

			$postObj = array();
			$postObj['media'] = $media;

			if (!is_null($lang))
				$postObj['lang'] = $lang;

			if (!is_null($offsetms))
				$postObj['offsetms'] = $offsetms;

			if (!is_null($skipms))
				$postObj['skipms'] = $skipms;

			if (!is_null($playbackId))
				$postObj['playbkackId'] = $playbackId;

			$uri = (is_null($playbackId)) ? '/bridges/' . $bridgeId . '/play' : '/bridges/' . $bridgeId . '/play/' . $playbackId;
			$result = $this->pestObject->post($uri, $postObj);

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
	 * This function is an alias to 'playback_start' - will be deprecated in phpari 2.0
	 *
	 * @return mixed
	 */
	public function bridge_start_playback($bridgeId = NULL, $media = NULL, $lang = NULL, $offsetms = NULL, $skipms = NULL, $playbackId = NULL)
	{
		return $this->playback_start($bridgeId, $media, $lang, $offsetms, $skipms, $playbackId);
	}


	/**
	 * This function is an alias to 'playback_start' - will be deprecated in phpari 2.0
	 *
	 * @return mixed
	 */
	public function bridge_start_playback_id($bridgeId = NULL, $media = NULL, $lang = NULL, $offsetms = NULL, $skipms = NULL, $playbackId = NULL)
	{
		return $this->playback_start($bridgeId, $media, $lang, $offsetms, $skipms, $playbackId);
	}


	/**
	 *  POST /bridges/{bridgeId}/record
	 *  Start a recording.
	 *  This records the mixed audio from all
	 *  channels participating in this bridge.
	 *
	 * @param null $bridgeId
	 * @param string $name - (required) Recording's filename
	 * @param string $format - (required) Format to encode audio in
	 * @param int $maxDurationSeconds - Maximum duration of the recording, in seconds. 0 for no limit.
	 * @param int $maxSilenceSeconds - Maximum duration of silence, in seconds. 0 for no limit.
	 * @param string $ifExists - Action to take if a recording with the same name already exists.
	 * @param bool $beep - Play beep when recording begins
	 * @param string $terminateOn - DTMF input to terminate recording.
	 *
	 * @return bool
	 */
	public function record(
		$bridgeId = NULL,
		$name = NULL,
		$format = NULL,
		$maxDurationSeconds = 0,
		$maxSilenceSeconds = 0,
		$ifExists = "fail",
		$beep = FALSE,
		$terminateOn = "none")
	{
		try {

			if (is_null($bridgeId))
				throw new Exception("BridgeID is not provided or is null", 503);
			if (is_null($name))
				throw new Exception("Recording filename is not provided or is null", 503);
			if (is_null($format))
				throw new Exception("Format to encode audio in is not provided or is null", 503);


			$postObj = array(
				'name' => $name,
				'format' => $format,
				'maxDurationSeconds' => $maxDurationSeconds,
				'maxSilenceSeconds' => $maxSilenceSeconds,
				'ifExists' => $ifExists,
				'beep' => $beep,
				'terminateOn' => $terminateOn,
			);

			$uri = '/bridges/' . $bridgeId . '/record';
			$result = $this->pestObject->post($uri, $postObj);

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
	 * This function is an alias to 'record' - will be deprecated in phpari 2.0
	 *
	 * @return mixed
	 */
	public function bridge_start_recording(
		$bridgeId = NULL,
		$name = NULL,
		$format = NULL,
		$maxDurationSeconds = 0,
		$maxSilenceSeconds = 0,
		$ifExists = "fail",
		$beep = FALSE,
		$terminateOn = "none"

	)
	{
		return $this->record($bridgeId, $name, $format, $maxDurationSeconds, $maxSilenceSeconds, $ifExists, $beep, $terminateOn);
	}

}



