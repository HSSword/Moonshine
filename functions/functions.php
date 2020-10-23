<?php
//This file should probably not be altered

include '../core/includes.php';

require _ROOT_PATH.'/admin/inc/redirectIfNotAdmin.php';

if($_REQUEST['f']){
	$functionName = $_REQUEST['f'];

	$filePath = _ROOT_PATH.'functions/functionFiles/'.$functionName.'.php';
	if (is_file($filePath)) {
		require $filePath;
	} else {
		die("Function was not found");
	}
}