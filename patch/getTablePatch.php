<?php


if($request['tableName'] and $request['replace']){

	$tableName = $request['tableName'];
	$descVarName = $tableName.'TableDesc';
	$descVar = $$descVarName;

	$permissionsOverride = [];
	if(in_array('public', $descVar['permissions']) and (!getUserObject() or getUserObject()['admin'] != true)) {
		$permissionsOverride[] = 'x'; //Prevents an empty array to be passed to getTableView
		//Which would be interpreted as 'no override'
		foreach ($descVar['permissions'] as $perm){
			if(in_array($perm, $publicTablePermissionsBlacklist)) continue;
			$permissionsOverride[] = $perm;
		}
	} else {
		require _ROOT_PATH.'admin/inc/redirectIfNotAdmin.php';
	}

	$hook = _BASE_DB_HOOK;
	if($request['hookOverride']) $hook = $request['hookOverride'];

	$sql = 'SELECT * FROM '.$tableName;

	if($request['search'] == 'yes') {
		unset($_SESSION['filter'][$tableName]);

		$blacklist = ['tableName', 'search', 'replace', 'patch', 'orderby'];
		foreach ($request as $key => $searchItem) {
			if (strlen($searchItem) == 0) continue;
			if (in_array($key, $blacklist)) continue;

			$_SESSION['filter'][$tableName][$key] = $searchItem;
		}
	} else if($request['search'] == 'clear'){
		unset($_SESSION['filter'][$tableName]);
	}

	if($orderBy = $request['orderby']){
		if($orderBy == "clear"){
			unset($_SESSION['orderby'][$tableName]);
		}else if ($orderBy){
			$orderArray = explode(',', $request['orderby']);
			if(count($orderArray) >= 2){
				unset($_SESSION['orderby'][$tableName]);
				$_SESSION['orderby'][$tableName][$orderArray[0]] = $orderArray[1];
			}
		}
	}

	//Add filter to query
	if($_SESSION['filter'][$tableName]){
		$sql .= ' WHERE 1=1 AND ';
		foreach($_SESSION['filter'][$tableName] as $key => $searchItem){
			$columnDesc = executeQuery($hook, 'SHOW COLUMNS FROM '.$tableName.' LIKE \''.$key.'\';');
			$reader = $columnDesc->fetch_row();
			if ($reader == null) continue;
			if (str_contains($reader[1], 'int')) {
				if (is_numeric($searchItem)) {
					$sql .= $key.' = '.$searchItem.' AND ';
				} else {
					$sql .= '0=1 AND ';
				}
			} else {
				$sql .= $key.' LIKE \'%'.$searchItem.'%\' AND ';
			}
		}
		$sql = str_removeFromEnd($sql, ' AND ');
	}
	//Add orderby to query
	if($_SESSION['orderby'][$tableName]){
		$orderColumnName = array_keys($_SESSION['orderby'][$tableName])[0];
		$orderColumnOrientation = array_values($_SESSION['orderby'][$tableName])[0];

		$sql .= ' ORDER BY '.$orderColumnName.' '.strtoupper($orderColumnOrientation);
	}


	$data = executeQuery(
		$hook,
		$sql.';'
	);

	if($data instanceof mysqli_result){
		$return['html'] = getTableView(
			$tableName,
			$data,
			['style' => 'margin: auto;width: 95%'],
			$permissionsOverride,
			$hook
		);
	}


	$return['selector'] = $request['replace'];
	$return['operation'] = 'replaceWith';
}
