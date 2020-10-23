<?php

if($_REQUEST['admin'] !== 'yes') {
	$output['header'] .=
		headerTag(
			a(
				strong(
					getConfig("SITE_TITLE")
				)
				//." by Pixelarity"
				, _URL
				, "_self"
				, ['class' => 'logo']
			)
			.nav(
				a(
					span(
						"Menu"
					)
					, "#menu"
					, "_self"
				)

			)
			, mergeArrays(['id' => 'header'], ($page === 'index' ? ['class' => 'alt'] : []))
		)
		.nav(
			getLanguageSwitcher()
			.ul(
				li(
					a(
						"Home"
						, _URL
						, "_self"
					)
				)
				.li(
					a(
						"HTML5 test"
						, _URL.'elements'
						, "_self"
					)
				)
				, ['class' => 'links']
			)
			, ['id' => 'menu']
		);
}