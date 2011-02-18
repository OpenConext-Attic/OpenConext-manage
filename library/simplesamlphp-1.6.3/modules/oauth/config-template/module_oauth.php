<?php
/* 
 * Configuration for the OAuth module.
 * 
 * $Id: module_oauth.php 1792 2009-09-28 09:50:24Z andreassolberg $
 */

$config = array (

	/* Enable the getUserInfo endpoint. Do not enable unless you know what you do.
	 * It may give external parties access to userInfo unless properly secured.
	 */
	'getUserInfo.enable' => TRUE,
	
	'requestTokenDuration' => 60*30, // 30 minutes
	'accessTokenDuration'  => 60*60*24, // 24 hours
	'nonceCache'           => 60*60*24*14, // 14 days


	// Tag to run storage cleanup script using the cron module...
	'cron_tag' => 'hourly',

);

