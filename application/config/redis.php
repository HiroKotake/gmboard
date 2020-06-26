<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Redis settings
| -------------------------------------------------------------------------
| Your Redis servers can be specified below.
|
*/


$config = array(
	'default' => array(
		'hostname' => '127.0.0.1',
        'password' => null,
		'port'     => 6379,
		'timeout'   => '0',
	),
);
