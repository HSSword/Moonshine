<?php

$contents = executeQuery(_BASE_DB_HOOK, 'SELECT * FROM content;');

$return['html'] =
	h1('Content')
	.br(2)
	.getTableView(
		'content',
		$contents,
		['style' => 'margin: auto;width: 95%']
	)
;
$return['selector'] = '#main';
$return['operation'] = 'html';
$return['js'] =
	"
		$('#menu ul li.current_page_item').removeClass('current_page_item');
		$('#menu ul #content-menu-button').addClass('current_page_item');
		$('#logo img').css('border-style','none');
	"
;