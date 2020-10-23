<?php
require _ROOT_PATH.'admin/inc/redirectIfNotAdmin.php';

//Pops a modal allowing you to add or edit

if($request['tableName'] and $request['i']){
	$hook = _BASE_DB_HOOK;
	if($request['hookOverride']) $hook = $request['hookOverride'];

	$return['selector'] = 'body';
	$return['operation'] = 'after';

	$modalContent =
		div(
			h3("Do you really want to delete this entry?")
			.button("No", attributes('no-btn','',['onclick' => 'closeAllModal()']))
			.button("Yes", attributes('yes-btn','',mergeArrays(
				actData('DeleteTableInstance', 'generalBase', ['i' => $request['i'], 'tableName' => $request['tableName'], 'hookOverride' => $hook])
				,jsTrigger('click')
			)))
		, ['style' => 'top: calc(50% - 16px); position: relative;'])
	;

	$return['html'] =
		getModalPopup($modalContent , 50, 40, 'mod-table-modal', false)
	;
}