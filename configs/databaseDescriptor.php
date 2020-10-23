<?php

/**
 * Table attributes are:
 *	desc, hidden, readonly, html, replace-[value]
 */
$publicTablePermissionsBlacklist = ['clone', 'update', 'insert', 'delete'];
$userTableDesc =
	[
		'permissions' => ['update', 'delete', 'insert', 'search', 'sort'],
		'id_user' =>
		[
			'desc' => 'ID', 'readonly'
		],
		'username' =>
		[
			'desc' => 'Username'
		],
		'fullname' =>
		[
			'desc' => 'Full name'
		],
		'password_hash' =>
		[
			'hidden'
		],
		'password_tmp' =>
		[
			'desc' => 'Password', 'password', 'optional'
		],
		'email' =>
		[
			'desc' => 'Email'
		],
		'admin' =>
		[
			'desc' => 'Is Admin', 'bool'
		],
		'locale' =>
		[
			'desc' => 'Language'
		]
	]
;

$translationTableDesc =
	[
		'permissions' => ['update', 'search', 'sort', 'public'],
		'id_translation' =>
		[
			'desc' => 'ID', 'readonly'
		],
		'original' =>
		[
			'desc' => 'Original', 'readonly'
		],
		'translated' =>
		[
			'desc' => 'Translation'
		],
		'locale' =>
		[
			'desc' => 'Locale', 'readonly'
		]
	]
;

$contentTableDesc =
	[
		'permissions' => ['insert', 'delete', 'update', 'search', 'sort', 'clone', 'public'],
		'id_content' =>
		[
			'desc' => 'ID', 'readonly'
		],
		'name' =>
		[
			'desc' => 'Identifier'
		],
		'html' =>
		[
			'desc' => 'Content', 'cke', 'nosearch', 'notable'
		],
		'locale' =>
		[
			'desc' => 'Locale'
		]
	]
;

$configTableDesc =
	[
		'permissions' => ['insert','delete', 'insert', 'sort', 'search', 'update', 'clone'],
		'id_config' => ['hidden'],
		'software' =>
		[
			'desc' => 'Name'
		],
		'description' =>
		[
			'desc' => 'Description', 'optional'
		],
		'value' =>
		[
			'desc' => 'Value'
		]
	]
;