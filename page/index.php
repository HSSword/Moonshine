<?php
//Generated with the Template Importation Function
$output['head'] .=
	meta(
		""
		, ['charset' => 'utf-8']
	)
	.meta(
		""
		, ['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, user-scalable=no']
	)
;
$output['body'] .=
	div(
		div(
			div(
				section(
					div(
						/*h1(
							getConfig("SITE_TITLE")
							, ['class' => 'logo']
						)
						.*/getHTMLContent("indexBanner")
						.ul(
							li(
								a(
									span(
										"Arrow Down (light)"
										, ['class' => 'label']
									)
									, "#one"
									, "_self"
									, ['class' => 'button primary circle scrolly']
								)
							)
							, ['class' => 'icons']
						)
						, ['class' => 'content']
					)
					, ['id' => 'banner', 'style' => "background: url(".getResourceURL('desk.jpg', 'moonshine/images').")"]
				)
				.section(
					div(
						div(
							getHTMLContent("indexTwo")
							.ul(
								li(
									a(
										span(
											"Arrow Down (light)"
											, ['class' => 'label']
										)
										, "#two"
										, "_self"
										, ['class' => 'button primary circle scrolly']
									)

								)
								, ['class' => 'icons']
							)
							, ['class' => 'content']
						)
						.div(
							imgTag(
								getResourceURL("site-planning.jpg", "moonshine/images")
								, ""
							)
							, ['class' => 'image fit']
						)
						, ['class' => 'box columns center']
					)
					, ['id' => 'one']
				)
				.section(
					div(
						getHTMLContent("indexThree")
						.div(
							getHTMLContent("indexFour")
							.ul(
								li(
									a(
										span(
											"Arrow Down (light)"
											, ['class' => 'label']
										)
										, "#three"
										, "_self"
										, ['class' => 'button primary circle scrolly']
									)

								)
								, ['class' => 'icons']
							)
							, ['class' => 'content']
						)
						, ['class' => 'box center']
					)
					, ['id' => 'two']
				)
				.section(
					div(
						div(
							imgTag(
								getResourceURL("translation.jpg", "moonshine/images")
								, ""
							)
							, ['class' => 'image fit']
						)
						.div(
							getHTMLContent("indexFive")
							.ul(
								li(
									a(
										span(
											"Arrow Down (light)"
											, ['class' => 'label']
										)
										, "#contact"
										, "_self"
										, ['class' => 'button primary circle scrolly']
									)

								)
								, ['class' => 'icons']
							)
							, ['class' => 'content']
						)
						, ['class' => 'box columns center']
					)
					, ['id' => 'three']
				)
				/*.section(
					div(
						div(
							getHTMLContent("indexSix")
							, ['class' => 'content']
						)
						.div(
							a(
								imgTag(
									getResourceURL("pic04.jpg", "moonshine/images")
									, "Untitled"
								)
								,_URL.'resources/moonshine/images/pic04_full.jpg'
								, "_self"
								, ['class' => 'thumbnail']
							)
							.a(
								imgTag(
									getResourceURL("pic05.jpg", "moonshine/images")
									, "Untitled"
								)
								,_URL.'resources/moonshine/images/pic05_full.jpg'
								, "_self"
								, ['class' => 'thumbnail']
							)
							.a(
								imgTag(
									getResourceURL("pic06.jpg", "moonshine/images")
									, "Untitled"
								)
								,_URL.'resources/moonshine/images/pic06_full.jpg'
								, "_self"
								, ['class' => 'thumbnail']
							)
							.a(
								imgTag(
									getResourceURL("pic07.jpg", "moonshine/images")
									, "Untitled"
								)
								,_URL.'resources/moonshine/images/pic07_full.jpg'
								, "_self"
								, ['class' => 'thumbnail']
							)
							.a(
								imgTag(
									getResourceURL("pic08.jpg", "moonshine/images")
									, "Untitled"
								)
								,_URL.'resources/moonshine/images/pic08_full.jpg'
								, "_self"
								, ['class' => 'thumbnail']
							)
							.a(
								imgTag(
									getResourceURL("pic09.jpg", "moonshine/images")
									, "Untitled"
								)
								,_URL.'resources/moonshine/images/pic09_full.jpg'
								, "_self"
								, ['class' => 'thumbnail']
							)
							.a(
								imgTag(
									getResourceURL("pic10.jpg", "moonshine/images")
									, "Untitled"
								)
								,_URL.'resources/moonshine/images/pic10_full.jpg'
								, "_self"
								, ['class' => 'thumbnail']
							)
							, ['class' => 'gallery']
						)
						.div(
							getHTMLContent("indexSeven")
							.ul(
								li(
									a(
										span(
											"Arrow Down (light)"
											, ['class' => 'label']
										)
										, "#contact"
										, "_self"
										, ['class' => 'button primary circle scrolly']
									)

								)
								, ['class' => 'icons']
							)
							, ['class' => 'content']
						)
						, ['class' => 'box center']
					)
					, ['id' => 'four']
				)*/
				.section(
					div(
						div(
							getHTMLContent("indexEight")
							.form(
								div(
									label(
										translate("Name")
										, "name"
									)
									.input(
										"text"
										, "name"
										, ""
										, ['id' => 'name']
									)
									, ['class' => 'field']
								)
								.div(
									label(
										translate("Email")
										, "email"
									)
									.input(
										"email"
										, "email"
										, ""
										, ['id' => 'email']
									)
									, ['class' => 'field']
								)
								.div(
									label(
										translate("Message")
										, "textarea"
									)
									.textarea(
										"", "message"
										, ['id' => 'textarea', 'rows' => '6']
									)
									, ['class' => 'field']
								)
								.div(
									input(
										"submit"
										, ""
										, translate("Send Message")
										, ['class' => 'primary fit']
									)
									, ['class' => 'actions']
								)
								, "post"
								, ""
								, mergeArrays(
									actData('sendSubmission', 'moonshine'),
									jsTrigger('submit'),
									attributes('submissionForm')
								)
							)
							, ['class' => 'content']
						)
						, ['class' => 'box contact center']
					)
					, ['id' => 'contact']
				)
				, ['class' => 'inner']
			)
			, ['id' => 'main']
		)
		, ['id' => 'wrapper']
	);