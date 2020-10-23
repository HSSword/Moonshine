<?php

require _ROOT_PATH.'admin/inc/redirectIfNotAdmin.php';

$return['html'] =
	h1('Functions')
	.br(2)
	.a(button('Import Template'), _URL.'functions/import_template')
;
$return['selector'] = '#main';
$return['operation'] = 'html';
$return['js'] =
	"
		$('#menu ul li.current_page_item').removeClass('current_page_item');
		$('#menu ul #functions-menu-button').addClass('current_page_item');
		$('#logo img').css('border-style','none');
	"
;