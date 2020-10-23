<?php

$clicks = 0;
$uniqueClicks = 0;

$dbMetrics = executeQuery(_BASE_DB_HOOK, getSqlFile('getMetrics'));
if($dbMetrics instanceof mysqli_result){
	$reader = $dbMetrics->fetch_row();
	if($reader != null) {
		$clicks = $reader[0];
		$uniqueClicks = $reader[1];
	}
}

$users = 0;
$dbMetrics = executeQuery(_BASE_DB_HOOK, getSqlFile('getUserCount'));
if($dbMetrics instanceof mysqli_result){
	$reader = $dbMetrics->fetch_row();
	if($reader != null) {
		$users = $reader[0];
	}
}



$return['html'] =
	div(
		div(
			br(2)
			.h2(translate("Stats of ").getConfig("SITE_TITLE"))
			.br(2)
			.h3(translate("Clicks")).p($clicks)
			.h3(translate("Sessions")).p($uniqueClicks)
			.h3(translate("Users")).p($users)
			,['class' => 'title']
		)
		,['id' => 'welcome']
	)
;

$return['selector'] = '#main';
$return['operation'] = 'html';
$return['js'] =
	"
		$('#menu ul li.current_page_item').removeClass('current_page_item');
		$('#menu ul #dashboard-menu-button').addClass('current_page_item');
		$('#logo img').css('border-style','none');
	"
;