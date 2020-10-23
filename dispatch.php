<?php
//This file should probably not be altered

require_once 'core/includes.php';

if(!isset($_SESSION['unique_click_done'])){
	die(json_encode(['forceReload' => true]));
}

$request = $_REQUEST;
$return = "";

if($request['patch']){
	$patch = $request['patch'];
	$return = [
		'html' => '',
		'js' => ''
	];
	$patchPath = _ROOT_PATH.'patch/'.$patch.'Patch.php';
	if(is_file($patchPath)){
		include $patchPath;
	}
}else if ($request['a'] and $request['path']){
	$return = standardMessage();

	$a = $request['a'];
	$path = $request['path'];
	$file = [];
	if(! empty($_FILES)){
		$file = $_FILES['file'];
	}

	$actPath = _ROOT_PATH.'act/'.$request['path'].'Act.php';
	if(is_file($actPath)){
		include $actPath;
	}
}else{
	die('Invalid dispatch call. Must contain either \'patch\', or \'a\' and \'path\'');
}

die(json_encode($return));
