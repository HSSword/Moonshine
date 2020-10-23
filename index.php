<?php
//This file should probably not be altered

require "core/init.php";
require _ROOT_PATH."core/router.php";

if(getConfig("VERBOSE") == "true") {
	logInfo('Ending session for page '.$page);
}

if(strlen($output['favicon']) == 0){
	$output['favicon'] = _URL.(getConfig("SITE_FAVICON") ? getConfig("SITE_FAVICON") : '');
}
if(strlen($output['title']) == 0){
	$output['title'] = (getConfig("SITE_TITLE") ? getConfig("SITE_TITLE") : '');
}
if(strlen($output['description']) == 0){
	$output['description'] = (getConfig("SITE_DESCRIPTION") ? getConfig("SITE_DESCRIPTION") : '');
}
if(strlen($output['keywords']) == 0){
	$output['keywords'] = (getConfig("SITE_KEYWORDS") ? getConfig("SITE_KEYWORDS") : '');
}


$favicon = favicon($output['favicon']);
$title = title($output['title']);
$descriptions = meta('', ['name' => 'description', 'content' => $output['description']]);
$keywords = meta('', ['name' => 'keywords', 'content' => $output['keywords']]);

$jsVars =
	"
		var _LANG = '".substr($_SESSION['locale'], 0, 2)."';
		var _LOCALE = '".$_SESSION['locale']."';
	"
;

die (
	'<!DOCTYPE html>'
	.'<html>'
	.'<head>'
	.$title
	.$favicon
	.$descriptions
	.$keywords
	.$output['head']
	.$output['import']
	.'<style>
		'.$output['css'].'
	</style>
	<script>
		'.$output['js'].'
		document.addEventListener("DOMContentLoaded", function (event) {
		   '.$output['readyJs'].' 
		});
		'.$jsVars.'
	</script>'

	.'</head>'
	.'<body '.parseOptions($output['bodyAttributes']).'>'
	.$output['header']
	.$output['body']
	.$output['footer']
	.'</body>'
	.'</html>'
);

