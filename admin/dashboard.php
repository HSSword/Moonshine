<?php
//Generated with the Template Importation Function

require _ROOT_PATH.'admin/inc/redirectIfNotAdmin.php';

$output['head'] .=
	linkTag(
		"https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900"
		,"stylesheet"
	)
	.linkTag(
		_URL."resources/5f3dbbc75687d/default.css",
		'stylesheet'
	)
	.linkTag(
		_URL."resources/5f3dbbc75687d/fonts.css"
		,"stylesheet"
	);

$userObj = getUserObject();
$profilePic = getResourceURL('placeholder.png');
$profilePicQuery = executeQuery(_BASE_DB_HOOK, getSqlFile('getUserIcon'), [['i' => $userObj['id']]]);
if($profilePicQuery instanceof mysqli_result){
	$reader = $profilePicQuery->fetch_row();
	if($reader != null){
		$profilePic = _URL.$reader[0];
	}
}



$devTools = '';
if(is_file(_ROOT_PATH.'patch/devUtilitiesPatch.php')){
	$devTools =
		li(
			a(
				translate("Dev Tools")
				,'javascript:return false'
			)
			,mergeArrays(['id' => 'dev-menu-button'],patchData('devUtilities'), jsTrigger('click'))
		);
}

$headerMenu =
	div(
		div(
			imgTag(
				$profilePic
				,"Profile"
			, mergeArrays(patchData('adminAccountSettings'), jsTrigger('click')))
			.h1(
				a(
					translate('Welcome').', '.ucfirst($userObj['username'])
					,"javascript: return false;"
				)
			)
			,['id' => 'logo']
		)
		.div(
			ul(
				li(
					a(
						translate("Overview")
						,'javascript:return false'
					)
					,mergeArrays(['id' => 'dashboard-menu-button', 'class' => 'current_page_item'], patchData('adminDashboard'), jsTrigger('click'))
				)
				.li(
					a(
						translate("Users")
						,'javascript:return false'
					)
					,mergeArrays(['id' => 'users-menu-button'],patchData('adminUsers'), jsTrigger('click'))
				)
				.li(
					a(
						translate("Locales")
						,'javascript:return false'
					)
					,mergeArrays(['id' => 'locales-menu-button'],patchData('adminLocales'), jsTrigger('click'))
				)
				.li(
					a(
						translate("Content")
						,'javascript:return false'
					)
					,mergeArrays(['id' => 'content-menu-button'],patchData('adminContent'), jsTrigger('click'))
				)
				.li(
					a(
						translate("Configs")
						,'javascript:return false'
					)
					,mergeArrays(['id' => 'config-menu-button'],patchData('adminConfig'), jsTrigger('click'))
				)
				.li(
					a(
						translate("Functions")
						,'javascript:return false'
					)
					,mergeArrays(['id' => 'functions-menu-button'],patchData('adminFunctions'), jsTrigger('click'))
				)
				.$devTools
				.li(
					a(
						"PHPMyAdmin"
						,_URL.'phpmyadmin'
						,'_blank'
					)
					,['class' => '']
				)
				.li(
					a(
						translate("Home")
						,_URL
						,'_blank'
					)
					,['class' => '']
				)
				.li(
					a(
						translate("Disconnect")
						,_URL.'disconnect'
					)
					,['class' => '']
				)
			)
			,['id' => 'menu'])
		,['id' => 'header']
	)
;

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

$output['title'] = translate("Dashboard");

$output['body'] .=
	div(
		$headerMenu
		.div(
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
			,['id' => 'main']
		)
		,['id' => 'page', 'class' => 'container']
	)
;