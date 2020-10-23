<?php
require _ROOT_PATH.'admin/inc/redirectIfNotAdmin.php';

//Pops a modal allowing you to add or edit

if($request['tableName']){
	$return['selector'] = 'body';
	$return['operation'] = 'after';

	$hook = _BASE_DB_HOOK;
	if($request['hookOverride']) $hook = $request['hookOverride'];

	$tableName = $request['tableName'];
	$isNew = !isset($request['i']);
	$i = $request['i'];

	$modalContent = '';
	$values = [];
	if(!$isNew){
		$valuesData = executeQuery(
			$hook,
			'SELECT * FROM '.$tableName.' WHERE id_'.$tableName.' = ?;',
			[['i' => $i]]
		);
		if($valuesData instanceof mysqli_result){
			$requestValues = $valuesData->fetch_row();
			if(is_array($requestValues)) $values = $requestValues;
		}
	}

	$form = getFormView($tableName, $values, $hook);
	$js = '';

	if($isNew){
		//modal to create
		$modalContent .=
			div(
				h3('Add an entry for '.$tableName)
				.br(2)
				.form(
					$form
					.br(2)
					.div(
						button("Cancel", ['onclick' => 'closeAllModal()'])
						.button("Confirm")
					, attributesClass('factory-form-buttons')),
					'post',
					'',
					mergeArrays(
						actData('InsertTableInstance', 'generalBase', ['tableName' => $tableName, 'hookOverride' => $hook]),
						jsTrigger('submit')
					)
				)
				, attributesClass('modal-insert-edit-form'))
		;
	}else{
		//modale to update
		$modalContent .=
			div(
				h3('Change an entry for '.$tableName)
				.br(2)
				.form(
					$form
					.br(2)
					.div(
						button("Cancel", ['onclick' => 'closeAllModal()'])
						.button("Confirm")
					, attributesClass('factory-form-buttons')),
					'post',
					'',
					mergeArrays(
						actData('UpdateTableInstance', 'generalBase', ['tableName' => $tableName, 'i' => $request['i'], 'hookOverride' => $hook]),
						jsTrigger('submit')
					)
				)
			, attributesClass('modal-insert-edit-form'))
		;
	}
	$return['html'] = getModalPopup(div($modalContent,
										['style' => 'text-align: center;padding-top: 2vh']
									) , 90, 90, 'mod-table-modal', false);
}