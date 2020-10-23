<?php
//Generated with the Template Importation Function


if(getUserObject() != null){
	if(getUserObject()['admin']){
		header("Location: "._URL."admin-panel/dashboard");
	}else{
		header("Location: "._URL);
	}
	die();
}
$output['title'] = 'Login';

$output['head'] .=
	linkTag(
		"https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900"
		,"stylesheet"
	)
	.linkTag(
		_URL."resources/5f3dbbc75687d/default.css",
		'stylesheet'
	)
	.linkTag(
		_URL."resources/5f3dbbc75687d/fonts.css"
		,"stylesheet"
	);
$output['body'] .=
	div(
		div(
			div(
				imgTag(
					_URL.(getConfig("SITE_LOGO") ? getConfig("SITE_LOGO") : '')
					,""
					,
					[
						'class' => 'image-full'
					]
				)
				,
				[
					'id' => 'banner'
				]
			)
			.div(
				div(
					h2(
						translate("Login")
					
					)
					.span(
						translate("Enter your login information")
											,
						[
							'class' => 'byline'
						]
					)
					.form(
						input('text', 'username', '',['placeholder' => translate("Username")])
						.br(1)
						.input('password', 'password', '', ['placeholder' => translate("Password")])
						.br(1)
						.label(translate("Remember me"), 'remember')
						.input('checkbox', 'remember', 'yes')
						.br(1)
						.input('submit', 'Login', 'Login')
						,''
						,''
						, mergeArrays(actData('login', 'user'), jsTrigger('submit'), [])
					)
									,
					[
						'class' => 'title'
					]
				)
							,
				[
					'id' => 'welcome'
				]
			)
					,
			[
				'id' => 'main-full'
			]
		)
			,
		[
			'id' => 'page',
			'class' => 'container',
			'style' => 'overflow-y: visible;'
		]
	)
;