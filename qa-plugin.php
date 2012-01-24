<?php
/*
	Question2Answer (c) Gideon Greenspan

	http://www.question2answer.org/

	
	File: qa-plugin/antibot-captcha/qa-plugin.php
	Version: See define()s at top of qa-include/qa-base.php
	Description: Initiates AntiBot Captcha plugin


	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	More about this license: http://www.question2answer.org/license.php
*/

/*
	Plugin Name: AntiBot Captcha
	Plugin URI: https://github.com/KrzysztofKielce/q2a-captcha-antibot
	Plugin Description: Provides support for AntiBot Captcha
	Plugin Version: 1.1
	Plugin Date: 2012-01-24
	Plugin Author: Krzysztof Kielce
	Plugin Author URI: http://www.question2answer.org/qa/user/Krzysztof+Kielce
	Plugin License: GPLv3
	Plugin Minimum Question2Answer Version: 1.5
	Plugin Update Check URI: https://raw.github.com/KrzysztofKielce/q2a-captcha-antibot/master/qa-plugin.php
*/

	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../../');
		exit;
	}

	qa_register_plugin_module('captcha', 'qa-antibot-captcha.php', 'qa_antibot_captcha', 'AntiBot Captcha');

/*
	Omit PHP closing tag to help avoid accidental output
*/