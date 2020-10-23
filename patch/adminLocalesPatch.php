<?php

require _ROOT_PATH.'admin/inc/redirectIfNotAdmin.php';

$locales = executeQuery(_BASE_DB_HOOK, getSqlFile('adminViews/getAllLocales'));

$return['html'] =
	h1('Locales')
	.br(2)
	.button('Rescan', attributes('js-rescan-button'))
	.br(2)
	.getTableView(
		'translation',
		$locales,
		['style' => 'margin: auto;width: 95%']
	)
;

$return['selector'] = '#main';
$return['operation'] = 'html';
$return['js'] =
	"
		$('#menu ul li.current_page_item').removeClass('current_page_item');
		$('#menu ul #locales-menu-button').addClass('current_page_item');
		$('#logo img').css('border-style','none');
		
		$('#js-rescan-button').click(function (event) {
			startLoading();
			
			executeAct('RefreshLocales', 'locale', {});
		});
	"
;