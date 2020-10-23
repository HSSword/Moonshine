<?php

setUserObject(null);

header("Location: "._URL);
if (isset($_COOKIE[getConfig("SITE_TITLE").'-logintoken'])) {
	executeQuery(
		_BASE_DB_HOOK,
		'DELETE FROM user_token WHERE token = ?;',
		[['s' => $_COOKIE[getConfig("SITE_TITLE").'-logintoken']]]
	);
}
setcookie(getConfig("SITE_TITLE").'-logintoken', '', time() - 1, '/');
die();