<?php

require _ROOT_PATH.'admin/inc/redirectIfNotAdmin.php';

$users = executeQuery(_BASE_DB_HOOK, getSqlFile('adminViews/getAllUsers'));

$return['html'] =
	h1('Users')
	.br(2)
	.getTableView(
		'user',
		$users,
		['style' => 'margin: auto;width: 95%']
	)
;
$return['selector'] = '#main';
$return['operation'] = 'html';
$return['js'] =
	"
		$('#menu ul li.current_page_item').removeClass('current_page_item');
		$('#menu ul #users-menu-button').addClass('current_page_item');
		$('#logo img').css('border-style','none');
	"
;