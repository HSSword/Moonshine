<?php
//This file should probably not be altered

/**
 * Returns a standard message for the $return variable
 * @param string $message The message to be sent
 * @param bool $error Was there an error?
 * @return array The message array
 */
function standardMessage (string $message = "An error occured", bool $error = true): array {
	return ['message' => $message, 'error' => $error];
}

/**
 * Merges any number of arrays
 * @return array The merged array
 */
function mergeArrays (/*$array1, $array2, ...*/): array {
	$gluedArray = [];
	$arrays = func_get_args();
	foreach ($arrays as $array) {
		if (is_array($array)) $gluedArray = array_merge($gluedArray, $array);
	}
	return $gluedArray;
}

/**
 * Returns a formatted string of arguments in a ?key=value&key2=value2 format
 * @param array $args The arguments as an array of string
 * @return string The formatted string
 */
function parseUrlGet (array $args): string {
	$return = '';
	if (count($args) > 0) {
		$return .= '?';
		foreach ($args as $key => $value) {
			$return .= $key.'='.$value.'&';
		}
		$return = str_removeFromEnd($return, '&');
	}
	return $return;
}

/**
 * Returns a formatted string of argument in a key="value" key2="value2" format
 * @param array $options The arguments as an array of string
 * @return string The formatted string
 */
function parseOptions (array $options): string {
	$opts = "";
	foreach ($options as $option => $value) {
		if(strlen($value) == 0){
			$opts .= $option.' ';
			continue;
		}
		$opts .= $option.'="'.$value.'" ';
	}
	return str_removeFromEnd($opts, ' ');
}

/**
 * Wrapper for var_dump and die
 * @param mixed $value The value to dump
 */
function dumpAndDie ($value): void {
	var_dump($value);
	die();
}

/**
 * Generates an attribute array for auto-act assignation
 * @param string $a The Action to perform
 * @param string $path The path to take (see Readme.md)
 * @param array $others Any other arguments to send dispatch.php
 * @return array The attribute array
 */
function actData (string $a, string $path, array $others = []): array {
	$return = str_removeFromStart(
		parseUrlGet(
			mergeArrays(
				['a' => $a, 'path' => $path],
				$others
			)
		), '?'
	);

	return ['data-act' => $return];
}

/**
 * Generates an attribute array for auto-plupload assignation
 * @param string $a The Action to perform
 * @param string $path The path to take (see Readme.md)
 * @param array $others Any other arguments to send dispatch.php
 * @return array The attribute array
 */
function pluploadData (string $a, string $path, array $others = []): array {
	$return = str_removeFromStart(
		parseUrlGet(
			mergeArrays(
				['a' => $a, 'path' => $path],
				$others,
				((isset($others['maxsize']) or getConfig("DEFAULT_MAX_UPLOAD_SIZE") != null) ? [] : ['maxsize' => getConfig("DEFAULT_MAX_UPLOAD_SIZE")])
			)
		), '?'
	);

	return ['data-plupload' => $return];
}

/**
 * Attemps to login a user. Automatically sets the userObject if successful
 * @param string $user The username
 * @param string $pwd The password, unhashed
 * @param bool $remember Wether or not to remember the connexion
 * @param string $userObjID The custom userObj ID to use. Can be ommited
 * @return bool Wether or not the user is now logged in
 */
function attemptLogin (string $user, string $pwd, bool $remember = false, string $userObjID = "default") : bool {
	if(!_USE_BASE_DB) return false;
	if(!databaseHookExists(_BASE_DB_HOOK)) return false;

	$result = executeQuery(
		_BASE_DB_HOOK,
		getSqlFile("getUser"),
		[
			['s' => $user],
			['s' => $pwd]
		]
	);

	if(!($result instanceof mysqli_result)){
		return false;
	}
	if(($row = $result->fetch_assoc()) != null){
		$userObj =
			[
				'id' => $row['id_user'],
				'username' => $row['username'],
				'pwd_hash' => $row['password_hash'],
				'admin' => $row['admin'] == 1,
				'email' => $row['email'],
				'fullname' => $row['fullname'],
				'locale' => $row['locale']
			]
		;
		setUserObject($userObj, $userObjID);

		if($remember){
			saveToken('', $userObjID);
		}

		return true;
	}

	return false;
}

/**
 * Generates an attribute array for auto-patch assignation
 * @param string $patch The patch to retrive
 * @param array $others Any other arguments to send dispatch.php
 * @return array The attribute array
 */
function patchData (string $patch, array $others = []): array {
	$return = str_removeFromStart(
		parseUrlGet(
			mergeArrays(
				['patch' => $patch],
				$others
			)
		), '?'
	);

	return ['data-patch' => $return];
}

/**
 * Generate a trigger attributes for acts and patches
 * @param string $trigger The trigger name. See Readme.md
 * @return array|string[] The attribute array
 */
function jsTrigger (string $trigger): array {
	return ['data-js-trigger' => $trigger];
}

/**
 * Generates the full path of a file in the /resources or /resource/subfolder folder
 * @param string $file The file name with the extention
 * @param string $subfolder The subfolder name, if applicable
 * @return string The full path to include
 */
function getResourcePath (string $file, string $subfolder = ''): string {
	if ($subfolder === ''){
		$path = _ROOT_PATH.'resources/'.$file;
	}else {
		$path = _ROOT_PATH.'resources/'.$subfolder.'/'.$file;
	}

	$originalInfo = pathinfo($path);
	if(getConfig("IMAGES_USE_WEBP") === "true" and in_array($originalInfo['extension'], ['jpg', 'jpeg', 'png'])){
		$webpFile = md5($path).'.webp';
		$webpPath = _ROOT_PATH.'resources/webp-convert/'.$webpFile;
		if(!is_file($webpPath)){
			$methodName = "imagecreatefrom".($originalInfo['extension'] === 'jpg' ? 'jpeg' : $originalInfo['extension']);
			if(function_exists($methodName)){
				$img = $methodName($path);
				imagewebp($img, $webpPath, 100);
			}
		}
		return $webpPath;
	}
	return $path;
}

/**
 * Generates the full URL of a file in the /resources or /resource/subfolder folder
 * @param string $file The file name with the extention
 * @param string $subfolder The subfolder name, if applicable
 * @return string The full URL to include
 */
function getResourceURL (string $file, string $subfolder = ''): string {
	if ($subfolder === ''){
		$path = _ROOT_PATH.'resources/'.$file;
		$url = _URL.'resources/'.$file;
	}else {
		$path = _ROOT_PATH.'resources/'.$subfolder.'/'.$file;
		$url = _URL.'resources/'.$subfolder.'/'.$file;
	}

	$originalInfo = pathinfo($path);
	if(getConfig("IMAGES_USE_WEBP") === "true" and in_array($originalInfo['extension'], ['jpg', 'jpeg', 'png'])){
		$webpFile = md5($path).'.webp';
		$webpPath = _ROOT_PATH.'resources/webp-convert/'.$webpFile;
		$webpUrl = _URL.'resources/webp-convert/'.$webpFile;
		if(!is_file($webpPath)){
			$methodName = "imagecreatefrom".($originalInfo['extension'] === 'jpg' ? 'jpeg' : $originalInfo['extension']);
			if(function_exists($methodName)){
				$img = $methodName($path);
				imagewebp($img, $webpPath, 100);
			}
		}
		return $webpUrl;
	}

	return $url;
}

/**
 * Generates the full path of a file in the /inc or /page/inc folder
 * @param string $file The file name without the extention
 * @param string $page The page name, if applicable
 * @return string The full path to include
 */
function getIncludePath (string $file, string $page = ''): string {
	if ($page === '') return _ROOT_PATH.'inc/'.$file.'.php';
	return _ROOT_PATH.'page/inc/'.$page.'/'.$file.'.php';
}

/**
 * Generates the full path to a class file
 * @param string $file The class name
 * @return string The full path to include
 */
function getClassPath (string $file): string {
	return _ROOT_PATH.'classes/'.$file.'.php';
}

/**
 * Generates the full path to a inerface file
 * @param string $file The interface name
 * @return string The full path to include
 */
function getInterfacePath (string $file): string {
	return _ROOT_PATH.'classes/interfaces/'.$file.'.php';
}

/**
 * Generates the full path to a trait file
 * @param string $file The trait name
 * @return string The full path to include
 */
function getTraitPath (string $file): string {
	return _ROOT_PATH.'classes/traits/'.$file.'.php';
}

/**
 * Gets the file content of a .sql file
 * @param string $file the name of the sql file
 * @return string The full content of the sql file
 */
function getSqlFile (string $file): string {
	$filePath = _ROOT_PATH.'sql/'.$file.'.sql';
	if (is_file($filePath)) {
		return file_get_contents($filePath);
	}
	return '';
}


/**
 * Retrives the content of a file
 * @param string $filePath The file path
 * @return string The content of the file
 */
function cat (string $filePath): string {
	if (is_file($filePath)) {
		return file_get_contents($filePath);
	}
	return '';
}


/**
 * Retrives the content of a file as array of strings
 * @param string $filePath The file path
 * @param bool $treatPoundsAsComments Should lines starting with a '#' be ommited
 * @return array The content of the file, separated by newlines
 */
function catLines (string $filePath, bool $treatPoundsAsComments = true): array {
	$lines = [];
	if (is_file($filePath)) {
		$fh = fopen($filePath, 'r');
		try {
			$index = 0;
			while (($line = fgets($fh)) !== false) {
				if (str_startsWith($line, '#') and $treatPoundsAsComments) continue;
				$lines[$index++] = rtrim($line);
			}
		} finally {
			@fclose($fh);
		}
	}
	return $lines;
}

/**
 * Recursively sets the permissions of a file or folder per the
 * _UNIX_FILE_OWNER and _UNIX_FILE_PERMISSIONS configs.
 * Use this whenever you create a file or folder if you are using another group / permission octal
 * than the one used by your web server.
 * Does nothing on Windows
 * @param string $path The file path
 */
function setFilePermission (string $path) : void {

	if (PHP_OS_FAMILY === "Windows") return;
	$iterator = null;
	if (!is_dir($path)) {
		$iterator = [$path];
	} else {
		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
	}

	foreach ($iterator as $item) {
		@chmod($item, octdec(getConfig("UNIX_FILE_PERMISSIONS")));
		@chgrp($item, getConfig("UNIX_FILE_GROUP"));
	}
}


/**
 * Performs a REST GET operation
 * @param string $url The url for the
 * @param array $args Arguments to send to the server
 * @return string The server of the response
 */
function get (string $url, array $args = []): string {
	$append = '';
	if (count($args) > 0) {
		$append .= '/'.parseUrlGet($args);
	}

	$curlCall = curl_init($url.$append);

	curl_setopt($curlCall, CURLOPT_RETURNTRANSFER, 1);

	$return = curl_exec($curlCall);
	curl_close($curlCall);

	return $return;
}

/**
 * Performs a REST POST operation
 * @param string $url The url for the
 * @param array $args Arguments to send to the server
 * @return string The server of the response
 */
function post (string $url, array $args = []): string {
	$curlCall = curl_init($url);

	curl_setopt($curlCall, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curlCall, CURLOPT_POST, true);
	curl_setopt($curlCall, CURLOPT_POSTFIELDS, $args);

	$return = curl_exec($curlCall);
	curl_close($curlCall);

	return $return;
}


/**
 * Sends an email from a domain in which you have authority on
 * @param string $from From which address it is coming from
 * @param string $to The target email address
 * @param string $subject Subject of the email
 * @param string $body Body of the email as text or HTML
 * @param array $attachments Attachments as paths to a file
 * @param bool $bodyIsHtml Is the text valid HTML
 * @return bool Wether or not the mail was sent
 */
function sendEmail (string $from, string $to, string $subject, string $body, bool $bodyIsHtml = true, array $attachments = []) : bool {
	ini_set('sendmail_from', $from);

	$boundary = md5(uniqid());
	//Header
	$headers =
		"MIME-Version: 1.0\r\n".
		'From: '.$from."\r\n".
		'Reply-To: '.$from."\r\n".
		'X-Mailer: PHP/'.phpversion()."\r\n".
		"Content-Type: multipart/mixed; boundary=\"".$boundary."\";"
	;

	//Message
	$message =
		"--".$boundary."\r\n".
		"Content-Type: text/".($bodyIsHtml ? 'html' : 'plain')."; charset=UTF-8;\r\n".
		"Content-Transfer-Encoding: base64\r\n\r\n"
	;
	$message .= base64_encode($body)."\r\n\r\n";

	if(!empty($attachments)){
		//Attachments
		foreach ($attachments as $attachment){
			$info = pathinfo($attachment);
			$handle = fopen($attachment, 'r');
			$content = fread($handle, filesize($attachment));
			fclose($handle);
			$encoded_content = chunk_split(base64_encode($content));

			$message .=
				"--".$boundary."\r\n".
				"Content-Type: application/octet-stream; name=\"".$info['basename']."\";\r\n".
				"Content-Description: ".$info['basename']."\r\n".
				"Content-Disposition: attachment; filename=\"".$info['basename']."\"; size=".filesize($attachment).";\r\n".
				"Content-Transfer-Encoding: base64\r\n".
				"X-Attachment-Id: ".rand(1000,99999)."\r\n\r\n".
				$encoded_content.
				"\r\n\r\n"
			;
		}

	}
	$message .= "--".$boundary."--";

	return mail($to, $subject, $message, $headers);
}

/**
 * Sends an email from a domain in which you have authority on. Accepts std_func's getEmailArray
 * @param array $mailObject The mail array built using getEmailArray. If an array of array is sent, all objects will be processed
 * @return bool Wether or not the mail was sent
 */
function sendEmailArray (array $mailObject) : bool {
	$mailArray = [];
	if(array_key_exists('from', $mailObject)){
		return sendEmail(
			$mailArray['from'],
			$mailArray['to'],
			$mailArray['subject'],
			$mailArray['body'],
			$mailArray['isHtml'],
			$mailArray['attachments']
		);
	}else if(array_key_exists(0, $mailObject)){
		$errorOccured = false;
		foreach ($mailObject as $mail){
			if(!sendEmail(
				$mail['from'],
				$mail['to'],
				$mail['subject'],
				$mail['body'],
				$mail['isHtml'],
				$mail['attachments']
			)){
				$errorOccured = true;
				break;
			}
		}
		return !$errorOccured;
	}

	return false;
}

/**
 * Builds an email array from parameters. Also automatically replaces placeholders in templates
 * @param string $from From where the email comes from
 * @param string $to The destination of the email
 * @param bool $isHtml Wether or not the message is valid html
 * @param string $subject The subject of the email
 * @param string $message The message of the email, either plaintext or html
 * @param array $replacements Associative array that maps placeholders with values. (you can ommit the %% delimiters)
 * @param array $attachments The attachments as an array of paths
 * @return array The email array. Use it with sendEmailArray
 */
function getEmailArray (string $from, string $to, bool $isHtml, string $subject, string $message, array $replacements = [], array $attachments = []): array {
	foreach ($replacements as $placeholder => $replacement){
		$message = str_replace('%%'.str_replace('%', '', $placeholder).'%%', $replacement, $message);
		$subject = str_replace('%%'.str_replace('%', '', $placeholder).'%%', $replacement, $subject);
	}
	return [
		'from' => $from,
		'to' => $to,
		'isHtml' => $isHtml,
		'body' => $message,
		'subject' => $subject,
		'attachments' => $attachments
	];
}

/**
 * Logs a raw string to the log file
 * @param string $msg The raw message
 */
function logString (string $msg): void {
	$fh = fopen(getConfig("LOG_FILE"), 'a');
	try {
		fwrite($fh, $msg.PHP_EOL);
	} finally {
		@fclose($fh);
	}
}

/**
 * Logs an info message to the log file
 * @param string $msg The message
 */
function logInfo (string $msg): void {
	logString('('.$_SERVER['REMOTE_ADDR'].') ('.date('Y-m-d H:i:s').') [Info] '.$msg);
}

/**
 * Logs a warning message to the log file
 * @param string $msg The warning
 */
function logWarning (string $msg): void {
	logString('('.$_SERVER['REMOTE_ADDR'].') ('.date('Y-m-d H:i:s').') [Warning] '.$msg);
}

/**
 * Logs an error message to the log file
 * @param string $msg The error
 */
function logError (string $msg, bool $fatal = false): void {
	logString('('.$_SERVER['REMOTE_ADDR'].') ('.date('Y-m-d H:i:s').') ['.($fatal ? 'Fatal ' : '').'Error] '.$msg);
	if ($fatal) die('Fatal Error');
}

/**
 * Tries to find a logged user object by ID
 * @param string $id The ID
 * @return array|null The object. Null if none if found
 */
function getUserObject (string $id = 'default'): ?array {
	if (isset($_SESSION['user_sessions'][$id])) {
		return $_SESSION['user_sessions'][$id];
	}
	return null;
}

/**
 * Evaluates if a user is logged in
 * @param string $id The ID
 * @return bool Wether or not the user is logged in
 */
function isLoggedIn (string $id = 'default') : bool {
	return (getUserObject($id) != null);
}

/**
 * Stores a logged user object
 * @param array|null $userObject The user object. Use null to delete it
 * @param string $id The id
 */
function setUserObject (?array $userObject, string $id = 'default'): void {
	if ($userObject == null) {
		unset($_SESSION['user_sessions'][$id]);
		return;
	}
	$_SESSION['user_sessions'][$id] = $userObject;
}

/**
 * Returns a content from the base database, if it exists
 * @param string $contentId The content id
 * @param string $locale The locale. Defaults to the default locale
 * @return string|null The content as HTML, if it exists
 */
function getHTMLContent (string $contentId, string $locale = ""): ?string {
	if(strlen($locale) == 0){
		$locale = $_SESSION['locale'];
	}

	$content =
		executeQuery(
			_BASE_DB_HOOK,
			'SELECT html FROM content WHERE name = ? AND locale = ?;',
			[['s' => $contentId], ['s' => $locale]]
		);
	if($content instanceof mysqli_result){
		$content = $content->fetch_row();
		if($content) return str_replace('&nbsp;', ' ', $content[0]);
	}
	return p('Content '.$contentId.' ('.$locale.') could not be found');
}

/**
 * Translates a string, if possible. Otherwise, passes the original through
 * @param string $original The original string
 * @param string $localeOverride The locale to use. Can be ommited
 * @return string The translated string
 */
function translate (string $original, string $localeOverride = '') : string {
	$locale = ($localeOverride === '' ? $_SESSION['locale'] : $localeOverride);
	$translation = executeQuery(
		_BASE_DB_HOOK,
		'SELECT translated FROM translation WHERE original = ? AND locale = ?;',
		[['s' => $original], ['s' => $locale]]
	);

	if($translation instanceof mysqli_result){
		$reader = $translation->fetch_row();
		if($reader[0]){
			return $reader[0];
		}
	}
	return $original;
}

/**
 * Slugifies a string
 * @param string $string The input string
 * @return string The slugified string
 */
function slugify (string $string): string {
	$string = trim($string, ' ');
	$string = str_replace(' ', '_', $string);
	$string = strtolower($string);

	return $string;
}

/**
 * Converts bytes to human readable size string
 * @param int $size The size in bytes (8 bits)
 * @param int $decimals The precision of the output string
 * @return string The human readable size
 */
function bytesToHumanReadable (int $size, int $decimals = 2): string {
	if ($size >= 1 << 40)
		return number_format($size / (1 << 40), $decimals)."TB";
	if ($size >= 1 << 30)
		return number_format($size / (1 << 30), $decimals)."GB";
	if ($size >= 1 << 20)
		return number_format($size / (1 << 20), $decimals)."MB";
	if ($size >= 1 << 10)
		return number_format($size / (1 << 10), $decimals)."KB";
	return number_format($size, $decimals)."B";
}

/**
 * Converts human readable size to bytes
 * @param string $size The human readable size
 * @return int The size in bytes
 */
function humanReadableToBytes (string $size): int {
	$unitIndex = 0;

	$chars = str_split($size);
	$charsLen = count($chars);

	$multipliers =
		[
			'B' => 1,
			'KB' => 1 << 10,
			'MB' => 1 << 20,
			'GB' => 1 << 30,
			'TB' => 1 << 40
		];

	for ($i = 0; $i < $charsLen; $i++) {
		if (!is_numeric($size[$i]) and $size[$i] != '.') {
			$unitIndex = $i;
			break;
		}
	}

	$sizeN = floatval(substr($size, 0, $unitIndex));
	$sizeU = substr($size, $unitIndex, $charsLen - $unitIndex);

	return round($sizeN * $multipliers[$sizeU]);
}

/**
 * Saves a file from the $_FILE array
 * @param array $file The file in question
 * @param string $subfolder The folder in which to place said file
 * @param string $customName The file name, with the extention
 * @param bool $private Is this file meant to be private?
 * @param string $size The max file size acceptable
 * @param bool $override Should this file overwrite any existing file with the same name
 * @return string The status of the operation to display
 */
function saveFile (array $file, string $subfolder, string $customName = '', bool $private = false, string $size = '', bool $override = false): string {
	if (getConfig("VERBOSE") == "true") logInfo('File upload received');
	if($size === '') $size = getConfig("DEFAULT_MAX_UPLOAD_SIZE");

	if ($file['error'] !== UPLOAD_ERR_OK) return 'Error '.$file['error'].' occured during upload';
	if ($file['size'] > humanReadableToBytes($size)) return 'File is too large (max: '.$size.')';

	$name = $file['name'];
	if ($customName !== '') $name = $customName.'.'.(pathinfo($name)['extension']);

	$pathAdd = '';
	if ($private === true) $pathAdd = 'private/';

	$fileDestinationPath = _ROOT_PATH.'files/'.$pathAdd.$subfolder.'/'.slugify($name);
	if (!$override and is_file($fileDestinationPath)) {
		return 'File already exists';
	}

	return (move_uploaded_file($file['tmp_name'], $fileDestinationPath) === true ? 'Success' : 'File copy failed');
}

/**
 * Checks if a string starts with another string
 * @param string $str The haystack
 * @param string $needle The needle
 * @return bool Wether the haystacks starts with the needle
 */
function str_startsWith (string $str, string $needle): bool {
	$strLen = strlen($str);
	$needleLen = strlen($needle);
	return (substr($str, 0, $needleLen) === $needle);
}

/**
 * Checks if a string ends with another string
 * @param string $str The haystack
 * @param string $needle The needle
 * @return bool Wether the haystacks ends with the needle
 */
function str_endsWith (string $str, string $needle): bool {
	$strLen = strlen($str);
	$needleLen = strlen($needle);
	return (substr($str, $strLen - $needleLen, $needleLen) === $needle);
}

/**
 * Checks if a string contains another string
 * @param string $str The haystack
 * @param string $needle The needle
 * @return bool Wether the haystacks contains the needle
 */
function str_contains (string $str, string $needle): bool {
	return (strpos($str, $needle) !== false);
}

/**
 * Removes a string from the end of another
 * @param string $str The containing string
 * @param string $needle The string to remove
 * @return string The altered string, or the original if the needle was not found
 */
function str_removeFromEnd (string $str, string $needle) : string {
	$strLen = strlen($str);
	$needleLen = strlen($needle);

	if($strLen >= $needleLen and str_endsWith($str, $needle)){
		return substr($str, 0, $strLen - $needleLen);
	}
	return $str;
}

/**
 * Removes a string from the start of another
 * @param string $str The containing string
 * @param string $needle The string to remove
 * @return string The altered string, or the original if the needle was not found
 */
function str_removeFromStart (string $str, string $needle) : string {
	$strLen = strlen($str);
	$needleLen = strlen($needle);

	if($strLen >= $needleLen and str_startsWith($str, $needle)){
		return substr($str, $needleLen);
	}
	return $str;
}

/**
 * Removes a string from another
 * @param string $str The containing string
 * @param string $needle The string to remove
 * @param int $instanceCount The max number of instances to remove. 0 is unlimited
 * @return string The altered string, or the original if the needle was not found
 */
function str_removeFrom (string $str, string $needle, int $instanceCount = 0) : string {
	if($instanceCount <= 0){
		return str_replace($needle, '', $str);
	}else{
		$moddedStr = $str;
		for($i = 0; $i < $instanceCount; $i++){
			$indexOfFirst = strpos($moddedStr, $needle);
			if($indexOfFirst === false) break;

			$partOne = substr($moddedStr, 0, $indexOfFirst);
			$partTwo = substr($moddedStr, $indexOfFirst + strlen($needle));

			$moddedStr = $partOne.$partTwo;
		}
		return $moddedStr;
	}
}

/**
 * Private internal function to get a minified js / css file. Not meant to be used externally
 * @param string $path The original file path
 * @param string $type The file type. Either 'js' or 'css'
 * @return string The endpoint file URL
 */
function _getLoaderUrl (string $path, string $type) {
	require_once _ROOT_PATH.'vendor/autoload.php';
	if (is_file($path)) {

		$minifyVarName = 'MINIFY_'.strtoupper($type);
		$minifyAutoVarName = 'AUTO_UPDATE_MINIFY_'.strtoupper($type);
		$minifyForceVarName = 'FORCE_UPDATE_MINIFY_'.strtoupper($type);

		if (getConfig($minifyVarName) !== 'true' or str_endsWith($path, 'min.'.$type)) { //Either no minify, or already a minified file
			if ($type == 'js') {
				return script('', str_replace(_ROOT_PATH, _URL, $path));
			} elseif ($type == 'css') {
				return linkTag(str_replace(_ROOT_PATH, _URL, $path), 'stylesheet');
			}
		}

		$fileName = explode('/', $path);
		$fileName = $fileName[count($fileName) - 1];
		$uniqId = md5($path);
		$fileName = str_removeFromEnd($fileName, '.'.$type);
		$endPath = $type.'/minified/'.$fileName.'-'.$uniqId.'.min.'.$type;

		$endLocalPath = _ROOT_PATH.$endPath;
		$endRemotePath = _URL.$endPath;

		if(
			(
				(
					!isMinifiedFileUpToDate($path, $endLocalPath)
					and getConfig($minifyAutoVarName) === 'true'
				)
				or
				(
					!is_file($endLocalPath)
				)
			)
			or
				getConfig($minifyForceVarName) === 'true'
		) {

			if ($type == 'js') {
				$minifier = new \MatthiasMullie\Minify\JS($path);
			} elseif ($type == 'css') {
				$minifier = new \MatthiasMullie\Minify\CSS($path);
			}
			$minifier->minify($endLocalPath);
			$info = pathinfo($endLocalPath);
			$metaFile = fopen($info['dirname'].'/checksums/'.$info['filename'].'.meta', 'w');
			try {
				fputs($metaFile, md5_file($path));
			} finally {
				@fclose($metaFile);
			}
			if (getConfig("VERBOSE") == "true") logInfo('Generated '.str_replace(_ROOT_PATH, '', $endLocalPath).' from '.str_replace(_ROOT_PATH, '', $path));
		}

		if ($type == 'js') {
			return script('', $endRemotePath);
		} elseif ($type == 'css') {
			return linkTag($endRemotePath, 'stylesheet');
		}
	}
	return "";
}

/**
 * Checks wether a minified js or css file is due for an update
 * @param string $originalPath The original file path
 * @param string $minifiedPath The minified file path
 * @return bool Should the minified file be updated
 */
function isMinifiedFileUpToDate (string $originalPath, string $minifiedPath): bool {
	$result = false;
	$newSum = md5_file($originalPath);

	$info = pathinfo($minifiedPath);
	$metaFileHandle = fopen($info['dirname'].'/checksums/'.$info['filename'].'.meta', 'r');
	try {
		$oldSum = fgets($metaFileHandle);
		$result = ($oldSum === $newSum);
	} finally {
		@fclose($metaFileHandle);
	}

	return $result;
}

/**
 * Recursively copies a directory
 * @param string $src The source path
 * @param string $dst The destination path
 */
function recurse_copy(string $src, string $dst) : void {
	$dir = opendir($src);
	@mkdir($dst);
	while(false !== ( $file = readdir($dir)) ) {
		if (( $file != '.' ) && ( $file != '..' )) {
			if ( is_dir($src . '/' . $file) ) {
				recurse_copy($src . '/' . $file,$dst . '/' . $file);
			}
			else {
				copy($src . '/' . $file,$dst . '/' . $file);
			}
		}
	}
	closedir($dir);
}

/**
 * Deletes the _ROOT_PATH/temp directory
 */
function deleteTemp () : void {
	foreach(glob(_ROOT_PATH.'temp/*') as $file){
		unlink($file);
	}
}

/**
 * Recursively deletes a directory
 * @param string $dirname The path of the folder
 * @return bool Wether or not the operation was successful
 */
function delete_directory(string $dirname) : bool {
	if (is_dir($dirname))
		$dir_handle = opendir($dirname);
	if (!$dir_handle)
		return false;
	while($file = readdir($dir_handle)) {
		if ($file != "." && $file != "..") {
			if (!is_dir($dirname."/".$file))
				unlink($dirname."/".$file);
			else
				delete_directory($dirname.'/'.$file);
		}
	}
	closedir($dir_handle);
	rmdir($dirname);
	return true;
}

/**
 * Saved a login token for a user
 * @param string $token The token to save, if you have one
 * @param string $id The user id. Can be ommited
 * @throws Exception
 */
function saveToken (string $token = '', string $id = 'default') : void {
	$user = getUserObject($id);
	if(!$user){
		return;
	}
	$tokenProvided = true;
	if($token === ''){
		$token = md5(uniqid());
		for($i = 0; $i < getConfig("LOGIN_TOKEN_32_CHAR_CHUNKS") - 1; $i++){
			$token .= md5($token.uniqid());
		}
		$token .= md5($token);
		$tokenProvided = false;
	}
	$expiration = new DateTime('now');
	$expiration = $expiration->add(new DateInterval("PT".getConfig("LOGIN_TOKEN_EXPIRATION_HOURS")."H"));
	if($tokenProvided){
		executeQuery(
			_BASE_DB_HOOK,
			'UPDATE user_token SET expiration = DATE_ADD(CURDATE(), INTERVAL '.getConfig("LOGIN_TOKEN_EXPIRATION_HOURS").' HOUR) WHERE token = ?;',
			[['s' => $token]]
		);
	}else{
		executeQuery(
			_BASE_DB_HOOK,
			'INSERT INTO user_token VALUES (NULL, ?, ?, ?)',
			[
				['i' => $user['id']],
				['s' => $token],
				['s' => $expiration->format('Y-m-d')]
			]
		);
	}

	setcookie(getConfig("SITE_TITLE").'-logintoken', $token, time() + (3600 * getConfig("LOGIN_TOKEN_EXPIRATION_HOURS")), '/');
}

/**
 * Retrieves the memcache object, or null on failure
 * @return Memcache|null The memcache
 */
function getMemcacheObject () : ?Memcache {
	$mem = new Memcache();
	if($mem->connect('localhost')) return $mem;

	return null;
}


/**
 * Reloads the user object from the database, if the id has a valid user object
 * @param string $id The user object. Can be ommited
 */
function refreshUserObj (string $id = 'default') : void {
	$user = getUserObject($id);
	if(!$user) return;

	$userId = $user['id'];
	$sql = 'SELECT * FROM user WHERE id_user = ?;';
	$res = executeQuery(
		_BASE_DB_HOOK,
		$sql,
		[['i' => $userId]]
	);

	if($res instanceof mysqli_result){
		$reader = $res->fetch_assoc();
		$userObj =
			[
				'id' => $reader['id_user'],
				'username' => $reader['username'],
				'pwd_hash' => $reader['pwd_hash'],
				'admin' => $reader['admin'] == 1,
				'email' => $reader['email'],
				'fullname' => $reader['fullname'],
				'locale' => $reader['locale']
			]
		;
		setUserObject($userObj, $id);
	}
}

/**
 * Attempts to login using token cookies
 * @param string $id The userObject id. Can be ommited
 * @return bool Wether or not the login was successful
 */
function attemptTokenLogin (string $id = 'default') : bool {

	if($userToken = $_COOKIE[getConfig("SITE_TITLE").'-logintoken']){
		$tokens = executeQuery(
			_BASE_DB_HOOK,
			'SELECT user.* FROM user_token INNER JOIN user ON user.id_user = user_token.id_user WHERE user_token.token = ?;',
			[['s' => $userToken]]
		);

		if($tokens instanceof mysqli_result){
			$reader = $tokens->fetch_assoc();
			if($reader){
				$userObj =
					[
						'id' => $reader['id_user'],
						'username' => $reader['username'],
						'pwd_hash' => $reader['pwd_hash'],
						'admin' => $reader['admin'] == 1,
						'email' => $reader['email'],
						'fullname' => $reader['fullname'],
						'locale' => $reader['locale']
					]
				;
				setUserObject($userObj, $id);
				saveToken($userToken, $id);
				header("Refresh:0");
				die();
			}
		}
	}
	return false;
}