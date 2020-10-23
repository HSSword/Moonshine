<?php

switch ($a){
	case 'login':
		if($request['username'] and $request['password']){
			if(getUserObject() != null){
				$return = standardMessage("Already logged in!");
				break;
			}
			$remember = (isset($request['remember']) and $request['remember'] == 'yes');

			if(attemptLogin($request['username'], $request['password'], $remember)){
				$return = standardMessage("Logged in", false);
				if(getUserObject()['admin']) {
					$return['js'] = 'setTimeout(function() {location.assign(_URL + \'admin-panel/dashboard\');},1500);';
				}else{
					$return['js'] = 'setTimeout(function() {location.assign(_URL);},1500);';
				}
			}else{
				$return = standardMessage("Invalid username or password", true);
			}
		}
		break;
	case 'logout':
		$user = getUserObject();
		setUserObject(null);
		if($user){
			$return = standardMessage("Successfully logged out", false);
		}else{
			$return = standardMessage("You were not logged in to begin with");
		}
		$return['js'] = 'setTimeout(function() {location.reload();},1500);';
		break;

	case 'updateUser':
		if($request['username'] and $request['fullname'] and $request['locale']){
			$id = getUserObject()['id'];
			$reloadJs =
				"
					setTimeout(function () {
						location.reload();
					}, 1200);
				";

			$sql = 'UPDATE user SET username = ?, locale = ?, fullname = ? WHERE id_user = ?;';

			$result = executeQuery(
				_BASE_DB_HOOK,
				$sql,
				[
					['s' => $request['username']],
					['s' => $request['locale']],
					['s' => $request['fullname']],
					['i' => $id]
				]
			);

			if($request['password'] and md5($request['password']) != $userObj['password_hash']) {
				$resultPwd = executeQuery(
					_BASE_DB_HOOK,
					'UPDATE user SET password_tmp = ? WHERE id_user = ?;',
					[['s' => $request['password']], ['i' => $id]]
				);
			}

			if($result === 1 or $resultPwd === 1){
				$return = standardMessage(translate("Account updated"), false);
				refreshUserObj();
				$return['js'] = $reloadJs;
			}else{
				$return = standardMessage(translate("No changes were done"), false);
			}
		}
		break;
	case 'updateImg':
		$file = $_FILES['file'];
		$userObj = getUserObject();
		if($file and $userObj){
			//TODO: if other user picture exists, only delete profile pictures
			$oldImageSql = "SELECT * FROM user_file WHERE id_user = ?;";

			$res = executeQuery(
				_BASE_DB_HOOK,
				$oldImageSql,
				[['i' => $userObj['id']]]
			);
			if($res instanceof mysqli_result){
				while(($reader = $res->fetch_assoc()) != null){
					unlink(_ROOT_PATH.$reader['path']);
					executeQuery(
						_BASE_DB_HOOK,
						"DELETE FROM user_file WHERE id_user_file = ?;",
						[['i' => $reader['id_user_file']]]
					);
				}
			}
			$baseFileName = md5($userObj['username'].uniqid());
			$cp = saveFile($file, 'user_files', $baseFileName, false, '', true);
			if($cp == "Success"){
				$info = pathinfo($file['name']);
				$filename = slugify($baseFileName.'.'.$info['extension']);

				$res = executeQuery(
					_BASE_DB_HOOK,
					"INSERT INTO user_file (id_user, path) VALUES (?, ?);",
					[
						['i' => $userObj['id']],
						['s' => 'files/user_files/'.$filename]
					]
				);

				if($res === 1){
					$return = standardMessage(translate("Image updated"), false);
					$return['newImageUrl'] = _URL.'files/user_files/'.$filename;
				}
			}else{
				$return = standardMessage($cp, true);
			}
		}
		break;
}