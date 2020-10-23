<?php

require _ROOT_PATH.'admin/inc/redirectIfNotAdmin.php';

$usrObj = getUserObject();
if($usrObj == null) die('No User');

$userImg = executeQuery(
	_BASE_DB_HOOK,
	'SELECT * FROM user_file WHERE id_user = ?;',
	[['i' => $usrObj['id']]]
);

$locales = explode(',', getConfig("SUPPORTED_LOCALES"));
$localesOptions = '';
foreach ($locales as $locale){
	$selected = [];
	if($usrObj['locale'] == $locale) $selected['selected'] = '';
	$localesOptions .= option(ucfirst($locale), $locale, $selected);
}

$imgUrl = getConfig("DEFAULT_USER_ICON");
$reader = null;
if($userImg instanceof mysqli_result){
	$reader = $userImg->fetch_assoc();
	if($reader != null){
		$imgUrl = $reader['path'];
	}
}

$imgUrl = _URL.$imgUrl;

$return['html'] =
	h1('My Account')
	.br(2)
	.div(
		imgTag(
			$imgUrl,
			'User Icon',
			mergeArrays(['width' => '150px', 'height' => '150px', 'class' => 'profile-img'], pluploadData('updateImg', 'user'))
		)
	, attributes('', 'left'))
	.div(
		form(
			label(translate("Username"), 'username')
			.br()
			.input('text', 'username', $usrObj['username'])
			.br()
			.label(translate("Full name"), 'fullname')
			.br()
			.input('text', 'fullname', $usrObj['fullname'])
			.br()
			.label(translate("Language"), 'locale')
			.br()
			.select($localesOptions, 'locale')
			.br()
			.label(translate("Password"), 'password')
			.br()
			.input('password', 'password', '', ['autocomplete' => 'off'])

			.br(2)
			.button(translate('Update'))
		, 'post', '', mergeArrays(actData('updateUser', 'user'), jsTrigger('submit'), ['autocomplete' => 'off']))
	, attributes('','right'))
;


$return['selector'] = '#main';
$return['operation'] = 'html';
$return['js'] =
	"
		$('#menu ul li.current_page_item').removeClass('current_page_item');
		$('#logo img').css('border-style','solid');
		$('#logo img').css('border-width','5px');
		$('#logo img').css('border-color','".getConfig("SITE_HIGHLIGHT_COLOR")."');
	"
;