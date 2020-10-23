<?php
//This file should probably not be altered

/**
 * This file should contain all standard includes. (includes useful to ALL calls, pages, acts or otherwise)
 */
session_start();

//How deep can the entry point be before config is not found anymore
define("_CONFIG_INCLUDE_FOLDER_ROBUSTNESS", 20);

$isAdmin = ($_REQUEST['admin'] === 'yes');

//include all /configs
$allConfigFiles = [];
for($i = 0; $i < _CONFIG_INCLUDE_FOLDER_ROBUSTNESS; $i++){
	if(!empty($allConfigFiles)) break; //Already found our config file. Stop looking

	$configGlobPath = '';
	for($j = 0; $j < $i; $j++){
		$configGlobPath .= '../';
	}
	$configGlobPath .= 'configs/*.php';

	try{
		@$allConfigFiles = array_merge($allConfigFiles, glob($configGlobPath));
	}catch (Exception $ignored){}
}
foreach ($allConfigFiles as $file){
	include $file;
}


//Database functions, must be loaded before std_func
/**
 * Tests if a database hook exists
 * @param string $identifier The identifier of the hook
 * @return bool Wether or not the hook exists
 */
function databaseHookExists (string $identifier): bool {
	return is_array($_SESSION['db_hook_'.$identifier]);
}

/**
 * Creates a database hook in the session
 * @param string $identifier The identifier of the hook. Save it
 * @param string $username Username to be used for the connection
 * @param string $password Password to be used for the connection
 * @param string $database Database name to be used for the connection
 * @param string $address Address to be used for the connection
 * @param int $port Port to be used for the connection
 */
function setupDatabaseHook (string $identifier, string $username, string $password, string $database, string $address = "localhost", int $port = 3306): void {
	if (databaseHookExists($identifier)) return;
	$_SESSION['db_hook_'.$identifier] =
		[
			'username' => $username,
			'password' => $password,
			'schema' => $database,
			'host' => $address,
			'port' => $port
		];
}

/**
 * Deletes a database hook
 * @param string $identifier The identifier of the hook
 */
function deleteDatabaseHook (string $identifier): void {
	unset ($_SESSION['db_hook_'.$identifier]);
}


/**
 * Retrieves a database hook and returns it
 * @param string $identifier The identifier of the hook
 * @return mysqli|null The mysqli object, or null if an error occured
 */
function getDatabase (string $identifier): ?mysqli {
	if (databaseHookExists($identifier)) {
		$hook = $_SESSION['db_hook_'.$identifier];
		return new mysqli($hook['host'], $hook['username'], $hook['password'], $hook['schema'], $hook['port']);
	}
	return null;
}

/**
 * Executes a query on a database hook
 * @param string $identifier The identifier of the hook
 * @param string $baseRequest The base request, in SQL
 * @param array $args The array of arguments in this format: [['i' => 1],['s' => 'string']]
 * @return false|int|mysqli_result|null The result of the operation. Null on error, affected rows (int) on non-select ops, and mysqli_result on select ops
 */
function executeQuery (string $identifier, string $baseRequest, array $args = []) {
	$connection = getDatabase($identifier);
	if ($connection) {
		$prepare = $connection->prepare($baseRequest);
		$argsToPass = [];
		$types = "";
		for ($i = 0; $i < count($args); $i++) {
			$arg = $args[$i];
			$types .= key($arg);
			array_push($argsToPass, $arg[key($arg)]);
		}
		if (count($argsToPass) > 0) array_unshift($argsToPass, $types);
		@call_user_func_array(array($prepare, "bind_param"), $argsToPass);

		if (!$prepare) return null;
		$prepare->execute();
		$results = $prepare->get_result();
		return $results === false ? $prepare->affected_rows : $results;
	}
	return null;
}


/**
 * Retrieves a configuration from the base database
 * @param string $configId The config ID
 * @return string | null The config, or null if not found
 */
function getConfig (string $configId) : ?string {
	$config = executeQuery(
		_BASE_DB_HOOK,
		"SELECT value FROM config WHERE software = ?;",
		[['s' => $configId]]
	);
	if($config instanceof mysqli_result){
		if(($reader = $config->fetch_row()) != null){
			return $reader[0];
		}
	}
	return null;
}

//composer
include_once _ROOT_PATH.'vendor/autoload.php';

//Std functions
require_once _ROOT_PATH.'core/std_func.php';

//include traits, interfaces and classes (in that order) if asked to
if(getConfig("_AUTO_IMPORT_TRAITS") == 'true'){
	foreach (glob(_ROOT_PATH.'classes/traits/*.php') as $traitFilePath){
		include $traitFilePath;
	}
}
if(getConfig("_AUTO_IMPORT_INTERFACES") == 'true'){
	foreach (glob(_ROOT_PATH.'classes/interfaces/*.php') as $interfaceFilePath){
		include $interfaceFilePath;
	}
}
if(getConfig("AUTO_IMPORT_CLASSES") == 'true'){
	foreach (glob(_ROOT_PATH.'classes/*.php') as $classFilePath){
		include $classFilePath;
	}
}


//include priority php first, and add them to the blacklist so they arent loaded again
$alreadyIncludedPhp = [];
$priorityFile = _ROOT_PATH.'autoInc/priority.list';
if(is_file($priorityFile)){
	$lines = catLines($priorityFile);
	foreach ($lines as $line){
		if(str_startsWith($line, "#")) continue;
		$singleFilePath = _ROOT_PATH.'autoInc/'.$line;
		include $singleFilePath;
		$alreadyIncludedPhp[] = $singleFilePath;
	}
}

//include all /autoInc
$allPhpFiles = glob(_ROOT_PATH.'autoInc/*.php');
foreach ($allPhpFiles as $file){
	if(in_array($file, $alreadyIncludedPhp)) continue; //Already loaded
	include $file;
}

if($isAdmin) {
	//Include all admin
	$alreadyIncludedPhp = [];
	$priorityFile = _ROOT_PATH.'autoInc/adminOnly/priority.list';
	if (is_file($priorityFile)) {
		$lines = catLines($priorityFile);
		foreach ($lines as $line) {
			if (str_startsWith($line, "#")) continue;
			$singleFilePath = _ROOT_PATH.'autoInc/adminOnly/'.$line;
			include $singleFilePath;
			$alreadyIncludedPhp[] = $singleFilePath;
		}
	}

	$allPhpFiles = glob(_ROOT_PATH.'autoInc/adminOnly/*.php');
	foreach ($allPhpFiles as $file) {
		if (in_array($file, $alreadyIncludedPhp)) continue; //Already loaded
		include $file;
	}
}else {
	//Include all public
	$alreadyIncludedPhp = [];
	$priorityFile = _ROOT_PATH.'autoInc/publicOnly/priority.list';
	if (is_file($priorityFile)) {
		$lines = catLines($priorityFile);
		foreach ($lines as $line) {
			if (str_startsWith($line, "#")) continue;
			$singleFilePath = _ROOT_PATH.'autoInc/publicOnly/'.$line;
			include $singleFilePath;
			$alreadyIncludedPhp[] = $singleFilePath;
		}
	}

	$allPhpFiles = glob(_ROOT_PATH.'autoInc/publicOnly/*.php');
	foreach ($allPhpFiles as $file) {
		if (in_array($file, $alreadyIncludedPhp)) continue; //Already loaded
		include $file;
	}
}

//include based on act or patch if no page was set
if(isset($page) and !$page){
	if($_REQUEST['a']) $page = $_REQUEST['a'];
	else if($_REQUEST['patch']) $page = $_REQUEST['patch'];
}



//include based on page
//include all /autoInc/$page if exists
if(isset($page) and $page) {
	$alreadyIncludedPhp = [];
	$priorityFile = _ROOT_PATH.'autoInc/'.$page.'/priority.list';
	if(is_file($priorityFile)){
		$lines = catLines($priorityFile);
		foreach ($lines as $line){
			if(str_startsWith($line, "#")) continue;
			$singleFilePath = _ROOT_PATH.'autoInc/'.$page.'/'.$line;
			include $singleFilePath;
			$alreadyIncludedPhp[] = $singleFilePath;
		}
	}
	if (is_file(_ROOT_PATH . 'autoInc/'. $page)) {
		$allPhpFiles = glob(_ROOT_PATH . 'autoInc/'.$page.'/*.php');
		foreach ($allPhpFiles as $file) {
			if(in_array($file, $alreadyIncludedPhp)) continue; //Already loaded
			include $file;
		}
	}

	if($isAdmin){
		//Include all adminonly/page
		$alreadyIncludedPhp = [];
		$priorityFile = _ROOT_PATH.'autoInc/adminOnly/'.$page.'/priority.list';
		if(is_file($priorityFile)){
			$lines = catLines($priorityFile);
			foreach ($lines as $line){
				if(str_startsWith($line, "#")) continue;
				$singleFilePath = _ROOT_PATH.'autoInc/adminOnly/'.$page.'/'.$line;
				include $singleFilePath;
				$alreadyIncludedPhp[] = $singleFilePath;
			}
		}
		if (is_file(_ROOT_PATH . 'autoInc/adminOnly/'. $page)) {
			$allPhpFiles = glob(_ROOT_PATH . 'autoInc/adminOnly/'.$page.'/*.php');
			foreach ($allPhpFiles as $file) {
				if(in_array($file, $alreadyIncludedPhp)) continue; //Already loaded
				include $file;
			}
		}
	}else{
		//Include all publiconly/page
		$alreadyIncludedPhp = [];
		$priorityFile = _ROOT_PATH.'autoInc/publicOnly/'.$page.'/priority.list';
		if(is_file($priorityFile)){
			$lines = catLines($priorityFile);
			foreach ($lines as $line){
				if(str_startsWith($line, "#")) continue;
				$singleFilePath = _ROOT_PATH.'autoInc/publicOnly/'.$page.'/'.$line;
				include $singleFilePath;
				$alreadyIncludedPhp[] = $singleFilePath;
			}
		}
		if (is_file(_ROOT_PATH . 'autoInc/publicOnly/'. $page)) {
			$allPhpFiles = glob(_ROOT_PATH . 'autoInc/publicOnly/'.$page.'/*.php');
			foreach ($allPhpFiles as $file) {
				if(in_array($file, $alreadyIncludedPhp)) continue; //Already loaded
				include $file;
			}
		}
	}
}

if (_USE_BASE_DB) require _ROOT_PATH.'core/base_db.php';

if(isset($_REQUEST['lang'])){
	if($_REQUEST['lang'] == '') {
		unset($_SESSION['tmp_lang_get']);
	}else{
		$_SESSION['tmp_lang_get'] = $_REQUEST['lang'];
	}
}

$locale = '';
//manual lang parameter
if(isset($_SESSION['tmp_lang_get'])){
	$lg = $_SESSION['tmp_lang_get'];
	$locale = getConfig("DEFAULT_LOCALE");
	foreach (explode(',', getConfig("SUPPORTED_LOCALES")) as $SUPPORTED_LOCALE) {
		if(strpos($SUPPORTED_LOCALE, $lg) !== false){
			$locale = $SUPPORTED_LOCALE;
			break;
		}
	}
//Connected user with language setting
}else if($uo = getUserObject() and $uo['locale']){
	$locale = $uo['locale'];
//Browser user options
}else if($_SERVER['HTTP_ACCEPT_LANGUAGE']){
	$loc = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
	$locale = getConfig("DEFAULT_LOCALE");

	foreach(explode(',', $loc) as $userLocale){
		$break = false;
		foreach (explode(',', getConfig("SUPPORTED_LOCALES")) as $SUPPORTED_LOCALE) {
			if(strpos($SUPPORTED_LOCALE, $userLocale) !== false){
				$locale = $SUPPORTED_LOCALE;
				$break = true;
				break;
			}
		}
		if($break) break;
	}
//Default
}else{
	$locale = getConfig("DEFAULT_LOCALE");
}

$_SESSION['locale'] = $locale;
