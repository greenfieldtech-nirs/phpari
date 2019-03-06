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
class phpari_config {

	private $configuration = array(
		'general' => array(
			'logfile' => 'console',
			'debug'   => 0,
		),
		'asterisk_ari' => array(
			'username' => 'asterisk',
			'password' => 'asterisk',
			'host'     => '127.0.0.1',
			'port'     => '8088',
			'endpoint' => '/ari',
			'transport' => 'ws',
		),
		'asterisk_manager' => array(
			'username' => 'amiuser',
			'password' => 'amipassword',
			'host'     => '127.0.0.1',
			'port'     => '5038'
		),
	);

	public function __construct($config = 'phpari.ini') {
		if (is_array($config)) {
			$this->config_merge($config);
			return;
		}
		
		// in case we read a fle for initialization, its easy
		if (($ini = parse_ini_file($config, TRUE)) === false)
			throw new Exception("Invald INI file provided: '$config'");
		$this->config_merge($ini);
	}
	
	private function config_merge($config = []) {
		foreach ($config as $section => $settings) {
			if (array_key_exists($section, $this->configuration))
				$this->configuration[$section] = array_merge($this->configuration[$section], $config[$section]);
			else
				$this->configuration[$section] = $config[$section];
		}
	}
	
	public function __get($section) {
		return @$this->configuration[$section];
	}

}
