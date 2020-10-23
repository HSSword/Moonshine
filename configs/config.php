<?php
//These settings should be left alone

function __str_end (string $str, string $needle): bool {
	$strLen = strlen($str);
	$needleLen = strlen($needle);
	return (substr($str, $strLen - $needleLen, $needleLen) === $needle);
}

function __adj_path (string $str, string $needle) : string {
	$strLen = strlen($str);
	$needleLen = strlen($needle);

	if($strLen >= $needleLen and __str_end($str, $needle)){
		return substr($str, 0, $strLen - $needleLen);
	}
	return $str;
}

/** The root path of the project */
define("_ROOT_PATH", __adj_path(__DIR__, '/configs').'/');
/** The root url of the site */
define("_URL", $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].(($_SERVER['SERVER_PORT'] != 80 and  $_SERVER['SERVER_PORT'] != 443)? ':'.$_SERVER['SERVER_PORT'] : '').'/');

/** The Paypal websrc link */
define("_PAYPAL_WEBSRC_LINK", 'https://www.sandbox.paypal.com/cgi-bin/websrc'); //Remove the sandbox for live

/** Wether or not to use the base DB. Required for admin stuff */
define("_USE_BASE_DB", true);


ini_set('display_errors', true);
error_reporting(E_ERROR | E_COMPILE_ERROR);
