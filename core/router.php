<?php

/**
 * This file should contain the includes for various $page values
 */



switch ($page){
	//Exceptions here
	default:
		if(isset($_REQUEST['admin']) and $_REQUEST['admin'] == 'yes'){
			$filePath = _ROOT_PATH.'admin/'.$page.'.php';
		}else{
			$filePath = _ROOT_PATH.'page/'.$page.'.php';
		}
		if(is_file($filePath)){
			require $filePath;
		}else{
			require _ROOT_PATH . '404.php';
		}
		break;
}