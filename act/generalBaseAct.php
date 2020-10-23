<?php
require _ROOT_PATH.'admin/inc/redirectIfNotAdmin.php';

$hook = _BASE_DB_HOOK;
if($request['hookOverride']) $hook = $request['hookOverride'];

switch ($a){
	case 'DeleteTableInstance':
		if($request['tableName'] and $request['i']){
			$tbl = $request['tableName'];
			$response = executeQuery(
				$hook,
				'DELETE FROM '.$tbl.' WHERE id_'.$tbl.' = ?',
				[['i' => $request['i']]]
			);
			if($response == 1){
				$return = standardMessage("Entry deleted", false);
				$return['js'] = '
					closeAllModal();
					executePatch(\'getTable\', {tableName: \''.$tbl.'\', replace: \'#'.$tbl.'-factory-table\'});
				';
			}
		}
		break;
	case 'UpdateTableInstance':
		if($request['tableName'] and $request['i']){
			//HERE
			$tbl = $request['tableName'];
			$fields = [];
			$blacklist = ['a', 'path', 'patch', 'i', 'tableName', 'id_'.$tbl, 'hookOverride'];
			foreach ($request as $key => $value){
				if(in_array($key, $blacklist)) continue;
				$fields[$key] = $value;
			}
			$sql = 'UPDATE '.$tbl.' SET ';
			foreach ($fields as $field => $value){
				$value = addslashes($value);
				if($value != 'null'){
					$value = '\''.$value.'\'';
				}
				$sql .= $field.' = '.$value.', ';
			}
			$sql = str_removeFromEnd($sql, ', ');
			$sql .= ' WHERE id_'.$tbl.' = ?;';

			$result = executeQuery($hook, $sql, [['i' => $request['i']]]);
			if($result === 1){
				$return = standardMessage('Entry updated', false);
				$return['js'] = '
					closeAllModal();
					executePatch(\'getTable\', {tableName: \''.$tbl.'\', replace: \'#'.$tbl.'-factory-table\'});
				';
			}
		}
		break;
	case 'InsertTableInstance':
		if($request['tableName']){
			//HERE
			$tbl = $request['tableName'];
			$fields = [];
			$blacklist = ['a', 'path', 'patch', 'i', 'tableName', 'id_'.$tbl, 'hookOverride'];
			foreach ($request as $key => $value){
				if(in_array($key, $blacklist)) continue;
				$fields[$key] = $value;
			}
			$sql = 'INSERT INTO '.$tbl.' (';
			foreach ($fields as $field => $ignored){
				$sql .= $field.', ';
			}
			$sql = str_removeFromEnd($sql, ', ');
			$sql .= ') VALUES (';
			foreach ($fields as $value){
				$value = addslashes($value);
				if($value != 'null'){
					$value = '\''.$value.'\'';
				}
				$sql .= $value.', ';
			}
			$sql = str_removeFromEnd($sql, ', ');
			$sql .= ');';

			$result = executeQuery($hook, $sql, [['i' => $request['i']]]);
			if($result === 1){
				$return = standardMessage('Entry added', false);
				$return['js'] = '
					closeAllModal();
					executePatch(\'getTable\', {tableName: \''.$tbl.'\', replace: \'#'.$tbl.'-factory-table\'});
				';
			}
		}
		break;
	case 'CloneTableInstance':
		if($request['tableName'] and $request['i']){
			$tbl = $request['tableName'];
			$sql = 'CALL duplicateRows(DATABASE(), \''.$tbl.'\', \'WHERE id_'.$tbl.' = '.$request['i'].'\', \'id_'.$tbl.'\');';
			$result = executeQuery(
				$hook,
				$sql
			);
			if($result === 1) {
				$return = standardMessage("Entry cloned", false);
				$return['js'] = '
					closeAllModal();
					executePatch(\'getTable\', {tableName: \''.$tbl.'\', replace: \'#'.$tbl.'-factory-table\'});
				';
			}
		}
		break;
}