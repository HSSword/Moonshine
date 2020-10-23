<?php
//This file should probably not be altered

/**
 * This file should initialize a page call
 */

/**
 * This array controls the output of the page
 * body - the content of the body. Required
 * bodyAttributes - attributes of the body tag
 * css - inline css to be added
 * js - inline js to be added
 * readyjs - inline js to be added in the page ready function
 * header - will be at the top of the body
 * footer - will be at the bottom of the body
 * head - the content of the head. Required
 * url - overrides the url on the browser
 * title - overrides the tab title
 * favicon - overrides the tab favicon
 * import - external imports of css & js. Auto-generated, do not tamper
 */
$output = array(
	'body' => "",
	'bodyAttributes' => [],
	'css' => "",
	'js' => "",
	'readyJs' => "",
	'header' => "",
	'footer' => "",
	'head' => "",
	'url' => "",
	'title' => "",
	'favicon' => "",
	'keywords' => "",
	'description' => "",
	//values below should not be altered in most cases
	'import' => ""
);

$request = $_REQUEST;
$page = $request['p'];

if(! $page) die();

$isAdmin = ($request['admin'] === 'yes');

require_once 'core/includes.php';

if(getConfig("VERBOSE") == "true") {
	logInfo('Starting session for page '.$page);
}

//import external css dependencies ================================================================
$cssIncludeFilePath = _ROOT_PATH.'css/external.list';
if(is_file($cssIncludeFilePath)) {
	$csIncludeFile = fopen($cssIncludeFilePath, 'r');
	while (!feof($csIncludeFile)) {
		$line = fgets($csIncludeFile);
		if(str_startsWith($line, "#")) continue;
		$output['import'] .= '<link rel="stylesheet" href="' . $line . '"></link>';
	}
	@fclose($csIncludeFile);
}

//include all /css ================================================================
$alreadyIncludedCss = [];
$priorityFile = _ROOT_PATH.'css/priority.list';
if(is_file($priorityFile)){
	$lines = catLines($priorityFile);
	foreach ($lines as $line){
		if(str_startsWith($line, "#")) continue;
		$singleFilePath = _ROOT_PATH.'css/'.$line;
		$output['import'] .= getCssLoader($singleFilePath);
		$alreadyIncludedCss[] = $singleFilePath;
	}
}
$allCssFiles = glob(_ROOT_PATH.'css/*.css');
foreach ($allCssFiles as $file){
	if(in_array($file, $alreadyIncludedCss)) continue; //Already loaded
	$output['import'] .= getCssLoader($file);
}

if($isAdmin) {
//include all /css/adminOnly ================================================================
	$alreadyIncludedCss = [];
	$priorityFile = _ROOT_PATH.'css/adminOnly/priority.list';
	if (is_file($priorityFile)) {
		$lines = catLines($priorityFile);
		foreach ($lines as $line) {
			if (str_startsWith($line, "#")) continue;
			$singleFilePath = _ROOT_PATH.'css/adminOnly/'.$line;
			$output['import'] .= getCssLoader($singleFilePath);
			$alreadyIncludedCss[] = $singleFilePath;
		}
	}
	$allCssFiles = glob(_ROOT_PATH.'css/adminOnly/*.css');
	foreach ($allCssFiles as $file) {
		if (in_array($file, $alreadyIncludedCss)) continue; //Already loaded
		$output['import'] .= getCssLoader($file);
	}
}else {
//include all /css/publicOnly ================================================================
	$alreadyIncludedCss = [];
	$priorityFile = _ROOT_PATH.'css/publicOnly/priority.list';
	if (is_file($priorityFile)) {
		$lines = catLines($priorityFile);
		foreach ($lines as $line) {
			if (str_startsWith($line, "#")) continue;
			$singleFilePath = _ROOT_PATH.'css/publicOnly/'.$line;
			$output['import'] .= getCssLoader($singleFilePath);
			$alreadyIncludedCss[] = $singleFilePath;
		}
	}
	$allCssFiles = glob(_ROOT_PATH.'css/publicOnly/*.css');
	foreach ($allCssFiles as $file) {
		if (in_array($file, $alreadyIncludedCss)) continue; //Already loaded
		$output['import'] .= getCssLoader($file);
	}
}

//include all /css/$page ================================================================
if (is_dir(_ROOT_PATH.'css/'.$page) or is_dir(_ROOT_PATH.'css/adminOnly/'.$page) or is_dir(_ROOT_PATH.'css/publicOnly/'.$page)){
	$alreadyIncludedCss = [];
	$priorityFile = _ROOT_PATH.'css/'.$page.'/priority.list';
	if(is_file($priorityFile)){
		$lines = catLines($priorityFile);
		foreach ($lines as $line){
			if(str_startsWith($line, "#")) continue;
			$singleFilePath = _ROOT_PATH.'css/'.$page.'/'.$line;
			$output['import'] .= getCssLoader($singleFilePath);
			$alreadyIncludedCss[] = $singleFilePath;
		}
	}
	$allCssFiles = glob(_ROOT_PATH.'css/'.$page.'/*.css');
	foreach ($allCssFiles as $file){
		if(in_array($file, $alreadyIncludedCss)) continue; //Already loaded
		$output['import'] .= getCssLoader($file);
	}

	if($isAdmin){
		//include all /css/adminOnly/$page ================================================================
		$alreadyIncludedCss = [];
		$priorityFile = _ROOT_PATH.'css/adminOnly/'.$page.'/priority.list';
		if(is_file($priorityFile)){
			$lines = catLines($priorityFile);
			foreach ($lines as $line){
				if(str_startsWith($line, "#")) continue;
				$singleFilePath = _ROOT_PATH.'css/adminOnly/'.$page.'/'.$line;
				$output['import'] .= getCssLoader($singleFilePath);
				$alreadyIncludedCss[] = $singleFilePath;
			}
		}
		$allCssFiles = glob(_ROOT_PATH.'css/adminOnly/'.$page.'/*.css');
		foreach ($allCssFiles as $file){
			if(in_array($file, $alreadyIncludedCss)) continue; //Already loaded
			$output['import'] .= getCssLoader($file);
		}
	}else{
		//include all /css/publicOnly/$page ================================================================
		$alreadyIncludedCss = [];
		$priorityFile = _ROOT_PATH.'css/publicOnly/'.$page.'/priority.list';
		if(is_file($priorityFile)){
			$lines = catLines($priorityFile);
			foreach ($lines as $line){
				if(str_startsWith($line, "#")) continue;
				$singleFilePath = _ROOT_PATH.'css/publicOnly/'.$page.'/'.$line;
				$output['import'] .= getCssLoader($singleFilePath);
				$alreadyIncludedCss[] = $singleFilePath;
			}
		}
		$allCssFiles = glob(_ROOT_PATH.'css/publicOnly/'.$page.'/*.css');
		foreach ($allCssFiles as $file){
			if(in_array($file, $alreadyIncludedCss)) continue; //Already loaded
			$output['import'] .= getCssLoader($file);
		}
	}
}




//import external js dependencies ================================================================
$jsIncludeFilePath = _ROOT_PATH.'js/external.list';
if(is_file($jsIncludeFilePath)) {
	$jsIncludeFile = fopen($jsIncludeFilePath, 'r');
	while (!feof($jsIncludeFile)) {
		$line = fgets($jsIncludeFile);
		if(str_startsWith($line, "#")) continue;
		$output['import'] .= '<script src="' . $line . '"></script>';
	}
	@fclose($jsIncludeFile);
}

//include all /js ================================================================
$alreadyIncludedJs = [];
$priorityFile = _ROOT_PATH.'js/priority.list';
if(is_file($priorityFile)){
	$lines = catLines($priorityFile);
	foreach ($lines as $line){
		if(str_startsWith($line, "#")) continue;
		$singleFilePath = _ROOT_PATH.'js/'.$line;
		$output['import'] .= getJsLoader($singleFilePath);
		$alreadyIncludedJs[] = $singleFilePath;
	}
}
$allJsFiles = glob(_ROOT_PATH.'js/*.js');
foreach ($allJsFiles as $file){
	if(in_array($file, $alreadyIncludedJs)) continue; //Already loaded
	$output['import'] .= getJsLoader($file);
}

if($isAdmin){
	//include all /js/adminOnly
	$alreadyIncludedJs = [];
	$priorityFile = _ROOT_PATH.'js/adminOnly/priority.list';
	if(is_file($priorityFile)){
		$lines = catLines($priorityFile);
		foreach ($lines as $line){
			if(str_startsWith($line, "#")) continue;
			$singleFilePath = _ROOT_PATH.'js/adminOnly/'.$line;
			$output['import'] .= getJsLoader($singleFilePath);
			$alreadyIncludedJs[] = $singleFilePath;
		}
	}
	$allJsFiles = glob(_ROOT_PATH.'js/adminOnly/*.js');
	foreach ($allJsFiles as $file){
		if(in_array($file, $alreadyIncludedJs)) continue; //Already loaded
		$output['import'] .= getJsLoader($file);
	}
}else{
	//include all /js/publicOnly
	$alreadyIncludedJs = [];
	$priorityFile = _ROOT_PATH.'js/publicOnly/priority.list';
	if(is_file($priorityFile)){
		$lines = catLines($priorityFile);
		foreach ($lines as $line){
			if(str_startsWith($line, "#")) continue;
			$singleFilePath = _ROOT_PATH.'js/publicOnly/'.$line;
			$output['import'] .= getJsLoader($singleFilePath);
			$alreadyIncludedJs[] = $singleFilePath;
		}
	}
	$allJsFiles = glob(_ROOT_PATH.'js/publicOnly/*.js');
	foreach ($allJsFiles as $file){
		if(in_array($file, $alreadyIncludedJs)) continue; //Already loaded
		$output['import'] .= getJsLoader($file);
	}
}


//include all /js/$page ================================================================
if (is_dir(_ROOT_PATH.'js/'.$page) or is_dir(_ROOT_PATH.'js/adminOnly/'.$page) or is_dir(_ROOT_PATH.'js/publicOnly/'.$page)){
	$alreadyIncludedJs = [];
	$priorityFile = _ROOT_PATH.'js/'.$page.'/priority.list';
	if(is_file($priorityFile)){
		$lines = catLines($priorityFile);
		foreach ($lines as $line){
			if(str_startsWith($line, "#")) continue;
			$singleFilePath = _ROOT_PATH.'js/'.$page.'/'.$line;
			$output['import'] .= getJsLoader($singleFilePath);
			$alreadyIncludedJs[] = $singleFilePath;
		}
	}
	$allCssFiles = glob(_ROOT_PATH.'js/'.$page.'/*.js');
	foreach ($allCssFiles as $file){
		if(in_array($file, $alreadyIncludedJs)) continue; //Already loaded
		$output['import'] .= getJsLoader($file);
	}

	if($isAdmin){
		//include all /js/adminOnly/$page ================================================================
		$alreadyIncludedJs = [];
		$priorityFile = _ROOT_PATH.'js/adminOnly/'.$page.'/priority.list';
		if(is_file($priorityFile)){
			$lines = catLines($priorityFile);
			foreach ($lines as $line){
				if(str_startsWith($line, "#")) continue;
				$singleFilePath = _ROOT_PATH.'js/adminOnly/'.$page.'/'.$line;
				$output['import'] .= getJsLoader($singleFilePath);
				$alreadyIncludedJs[] = $singleFilePath;
			}
		}
		$allCssFiles = glob(_ROOT_PATH.'js/adminOnly/'.$page.'/*.js');
		foreach ($allCssFiles as $file){
			if(in_array($file, $alreadyIncludedJs)) continue; //Already loaded
			$output['import'] .= getJsLoader($file);
		}
	}else{
		//include all /js/publicOnly/$page ================================================================
		$alreadyIncludedJs = [];
		$priorityFile = _ROOT_PATH.'js/publicOnly/'.$page.'/priority.list';
		if(is_file($priorityFile)){
			$lines = catLines($priorityFile);
			foreach ($lines as $line){
				if(str_startsWith($line, "#")) continue;
				$singleFilePath = _ROOT_PATH.'js/publicOnly/'.$page.'/'.$line;
				$output['import'] .= getJsLoader($singleFilePath);
				$alreadyIncludedJs[] = $singleFilePath;
			}
		}
		$allCssFiles = glob(_ROOT_PATH.'js/publicOnly/'.$page.'/*.js');
		foreach ($allCssFiles as $file){
			if(in_array($file, $alreadyIncludedJs)) continue; //Already loaded
			$output['import'] .= getJsLoader($file);
		}
	}
}


if(is_file(_ROOT_PATH."inc/head.php")) include _ROOT_PATH."inc/head.php";
if(is_file(_ROOT_PATH."inc/header.php")) include _ROOT_PATH."inc/header.php";
if(is_file(_ROOT_PATH."inc/footer.php")) include _ROOT_PATH."inc/footer.php";

if(_USE_BASE_DB){
	executeQuery(_BASE_DB_HOOK, getSqlFile('incrementClick'));
	if(!isset($_SESSION['unique_click_done'])){
		$_SESSION['unique_click_done'] = true;
		executeQuery(_BASE_DB_HOOK, getSqlFile('incrementUniqueClick'));
	}
}

if(getUserObject() == null){
	if(attemptTokenLogin()) die();
}

$output['css'] .= '
	:root{
		--accent-color: '.getConfig("SITE_HIGHLIGHT_COLOR").';
	}
';