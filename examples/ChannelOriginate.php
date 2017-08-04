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

    require_once "../vendor/autoload.php";

    $conn     = new phpari("hello-world"); //create new object
    $channels = new channels($conn);
    $response = $channels->channel_originate(
        'SIP/7001',
        NULL,
        array(
            "extension"      => "7001",
            "context"        => 'from-phone',
            "priority"       => 1,
            "app"            => "",
            "appArgs"        => "",
            "callerId"       => "111",
            "timeout"        => -1,
            "channelId"      => '324234',
            "otherChannelId" => ""
        ),
        array("var1" => "cool")
    );

    echo json_encode($response);
    exit(0);

?>