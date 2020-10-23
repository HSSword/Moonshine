<?php

require _ROOT_PATH.'admin/inc/redirectIfNotAdmin.php';

$configs = executeQuery(
	_BASE_DB_HOOK,
	"SELECT * FROM config;"
);

$return['html'] =
	h1('Configs')
	.br(2)
	.getTableView(
		'config',
		$configs,
		['style' => 'margin: auto;width: 95%']
	)
;
$return['selector'] = '#main';
$return['operation'] = 'html';
$return['js'] =
	"
		$('#menu ul li.current_page_item').removeClass('current_page_item');
		$('#menu ul #config-menu-button').addClass('current_page_item');
		$('#logo img').css('border-style','none');
	"
;