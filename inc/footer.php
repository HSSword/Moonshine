<?php

if($_REQUEST['admin'] !== 'yes') {
	$output['footer'] .=
		section(
			/*ul(
				li(
					a(
						span(
							"Twitter"
							, ['class' => 'label']
						)
						, "#"
						, "_self"
						, ['class' => 'icon brands fa-twitter']
					)

				)
				.li(
					a(
						span(
							"Facebook"
							, ['class' => 'label']
						)
						, "#"
						, "_self"
						, ['class' => 'icon brands fa-facebook-f']
					)

				)
				.li(
					a(
						span(
							"Instagram"
							, ['class' => 'label']
						)
						, "#"
						, "_self"
						, ['class' => 'icon brands fa-instagram']
					)

				)
				, ['class' => 'icons']
			)*/
			p(
				"&copy; ".getConfig("SITE_TITLE").". All rights reserved."

			)
			, ['id' => 'footer']
		);

	$output['footer'] .=
		getJsLoader(
			getResourcePath('main.js', 'moonshine/assets/js')
	);
}else{
	$output['footer'] .=
		script('', getResourceURL('ckeditor.js', 'ckeditor'));
}