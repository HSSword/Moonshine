<?php
//This file should probably not be altered

/** Returns the HTML code for a div element */
function div (string $content, array $attributes = []): string {
	return '<div '.parseOptions($attributes).'>'.$content.'</div>';
}

/** Returns the HTML code for a p element */
function p (string $content, array $attributes = []): string {
	return '<p '.parseOptions($attributes).'>'.$content.'</p>';
}

/** Returns the HTML code for an html element */
function html (string $content): string {
	return '<html>'.$content.'</html>';
}

/** Returns the HTML code for an html element */
function head (string $content): string {
	return '<head>'.$content.'</head>';
}

/** Returns the HTML code for a body element */
function body (string $content, array $attributes = []): string {
	return '<body '.parseOptions($attributes).'>'.$content.'</body>';
}

/** Returns the HTML code for a footer element */
function footer (string $content, array $attributes = []): string {
	return '<footer '.parseOptions($attributes).'>'.$content.'</footer>';
}

/** Returns the HTML code for a legend element */
function legend (string $content, array $attributes = []): string {
	return '<legend '.parseOptions($attributes).'>'.$content.'</legend>';
}

/** Returns the HTML code for an h1 element */
function h1 (string $content, array $attributes = []): string {
	return '<h1 '.parseOptions($attributes).'>'.$content.'</h1>';
}

/** Returns the HTML code for an h2 element */
function h2 (string $content, array $attributes = []): string {
	return '<h2 '.parseOptions($attributes).'>'.$content.'</h2>';
}

/** Returns the HTML code for an h3 element */
function h3 (string $content, array $attributes = []): string {
	return '<h3 '.parseOptions($attributes).'>'.$content.'</h3>';
}

/** Returns the HTML code for an h4 element */
function h4 (string $content, array $attributes = []): string {
	return '<h4 '.parseOptions($attributes).'>'.$content.'</h4>';
}

/** Returns the HTML code for an h5 element */
function h5 (string $content, array $attributes = []): string {
	return '<h5 '.parseOptions($attributes).'>'.$content.'</h5>';
}

/** Returns the HTML code for an h6 element */
function h6 (string $content, array $attributes = []): string {
	return '<h6 '.parseOptions($attributes).'>'.$content.'</h6>';
}

/** Returns the HTML code for a label element */
function label (string $content, string $for, array $attributes = []): string {
	return '<label for="'.$for.'"'.parseOptions($attributes).'>'.$content.'</label>';
}

/** Returns the HTML code for an anchor element */
function a (string $content, string $href, string $target = "_self", array $attributes = []): string {
	return '<a href="'.$href.'" target="'.$target.'"'.parseOptions($attributes).'>'.$content.'</a>';
}

/** Returns the HTML code for a form element */
function form (string $content, string $method = "post", string $action = "", array $attributes = []): string {
	if ($action !== "") {
		$action = 'action="'.$action.'"';
	}
	return '<form method="'.$method.'" '.$action.' '.parseOptions($attributes).'>'.$content.'</form>';
}

/** Returns the HTML code for an input element */
function input (string $inputType, string $name, string $value = "", array $attributes = []): string {
	return '<input type="'.$inputType.'" name="'.$name.'" value="'.$value.'"'.parseOptions($attributes).'></input>';
}

/** Returns the HTML code for a button element */
function button (string $content, array $attributes = []): string {
	return '<button '.parseOptions($attributes).'>'.$content.'</button>';
}

/*** Returns the HTML code for a ul element */
function ul (string $content, array $attributes = []): string {
	return '<ul '.parseOptions($attributes).'>'.$content.'</ul>';
}

/**
 * This function should be used in all htmlfactory calls. It generates the array to be passed
 * to the attributes parameters
 * @param string $id The ID of the DOM element. Can be '' for none
 * @param string $class The class attribute. Can be '' for none
 * @param array $otherAttributes The others attribute, in key => value pairs
 * @return array The attribute array
 */
function attributes (string $id = '', string $class = '', array $otherAttributes = []): array {
	$firstArr = [];
	if ($id !== '') $firstArr['id'] = $id;
	if ($class !== '') $firstArr['class'] = $class;

	return mergeArrays($firstArr, $otherAttributes);
}

/**
 * Shorthand for attributes($id, '', $attributes);
 * @param string $id The ID of the DOM element. Can be '' for none
 * @param array $otherAttributes The others attribute, in key => value pairs
 * @return array The attribute array
 */
function attributesId (string $id = '', array $otherAttributes = []): array {
	return attributes($id, '', $otherAttributes);
}

/**
 * Shorthand for attributes('', $class, $attributes);
 * @param string $class The class attribute. Can be '' for none
 * @param array $otherAttributes The others attribute, in key => value pairs
 * @return array The attribute array
 */
function attributesClass (string $class = '', array $otherAttributes = []): array {
	return attributes('', $class, $otherAttributes);
}

/** Returns the HTML code for an ol element */
function ol (string $content, array $attributes = []): string {
	return '<ol '.parseOptions($attributes).'>'.$content.'</ol>';
}

/** Returns the HTML code for a li element */
function li (string $content, array $attributes = []): string {
	return '<li '.parseOptions($attributes).'>'.$content.'</li>';
}

/** Returns the HTML code for a table element */
function table (string $content, array $attributes = []): string {
	return '<table '.parseOptions($attributes).'>'.$content.'</table>';
}

/** Returns the HTML code for a tr element */
function tr (string $content, array $attributes = []): string {
	return '<tr '.parseOptions($attributes).'>'.$content.'</tr>';
}

/** Returns the HTML code for a th element */
function th (string $content, array $attributes = []): string {
	return '<th '.parseOptions($attributes).'>'.$content.'</th>';
}

/** Returns the HTML code for a td element */
function td (string $content, array $attributes = []): string {
	return '<td '.parseOptions($attributes).'>'.$content.'</td>';
}

/** Returns the HTML code for a section element */
function section (string $content, array $attributes = []): string {
	return '<section '.parseOptions($attributes).'>'.$content.'</section>';
}

/** Returns the HTML code for an article element */
function article (string $content, array $attributes = []): string {
	return '<article '.parseOptions($attributes).'>'.$content.'</article>';
}

/** Returns the HTML code for a header element */
function headerTag (string $content, array $attributes = []): string {
	return '<header '.parseOptions($attributes).'>'.$content.'</header>';
}

/** Returns the HTML code for a footer element */
function footerTag (string $content, array $attributes = []): string {
	return '<footer '.parseOptions($attributes).'>'.$content.'</footer>';
}

/** Returns the HTML code for an aside element */
function aside (string $content, array $attributes = []): string {
	return '<aside '.parseOptions($attributes).'>'.$content.'</aside>';
}

/** Returns the HTML code for a nav element */
function nav (string $content, array $attributes = []): string {
	return '<nav '.parseOptions($attributes).'>'.$content.'</nav>';
}

/** Returns the HTML code for a span element */
function span (string $content, array $attributes = []): string {
	return '<span '.parseOptions($attributes).'>'.$content.'</span>';
}

/** Returns the HTML code for an img element */
function imgTag (string $src, string $alt = '', array $attributes = []): string {
	return '<img src="'.$src.'" alt="'.$alt.'"'.parseOptions($attributes).'></img>';
}

/** Returns the HTML code for a link element */
function linkTag (string $href, string $rel, array $attributes = []): string {
	return '<link rel="'.$rel.'" href="'.$href.'" '.parseOptions($attributes).'></link>';
}

/** Returns the HTML code for a script element */
function script (string $script, string $src = "", bool $async = false, bool $defer = false, array $attributes = []): string {
	if ($src) $src = 'src="'.$src.'" ';
	$otherOpts = ($async ? 'async ' : '').($defer ? 'defer ' : '');
	return '<script '.$otherOpts.$src.parseOptions($attributes).'>'.$script.'</script>';
}

/** Returns the HTML code for a style element */
function style (string $css, array $attributes = []): string {
	return '<style '.parseOptions($attributes).'>'.$css.'</style>';
}

/** Returns the HTML code for a title element */
function title (string $content): string {
	return '<title>'.$content.'</title>';
}

/** Returns the HTML code for a favicon element */
function favicon (string $iconUrl) {
	$faviconExts = explode('.', $iconUrl);

	$ext = $faviconExts[count($faviconExts) - 1];
	return linkTag($iconUrl, 'icon', ['type' => 'image/'.$ext]);
}

/** Returns the HTML code for an hr element */
function hr (array $attributes = []): string {
	return '<hr '.parseOptions($attributes).'/>';
}

/** Returns the HTML code for a br element */
function br (int $amount = 1): string {
	$html = "";
	for ($i = 0; $i < $amount; $i++) {
		$html .= '<br/>';
	}
	return $html;
}

/** Returns the HTML code for a area element */
function area (string $content, array $attributes = []): string {
	return '<area '.parseOptions($attributes).'>'.$content.'</area>';
}

/** Returns the HTML code for a meta element */
function meta (string $content, array $attributes = []): string {
	return '<meta '.parseOptions($attributes).'>'.$content.'</meta>';
}

/** Returns the HTML code for a base element */
function base (string $content, array $attributes = []): string {
	return '<base '.parseOptions($attributes).'>'.$content.'</base>';
}

/** Returns the HTML code for a param element */
function param (string $content, array $attributes = []): string {
	return '<param '.parseOptions($attributes).'>'.$content.'</param>';
}

/** Returns the HTML code for a source element */
function source (string $src, string $type, array $attributes = []): string {
	return '<source src="'.$src.'" type="'.$type.'" '.parseOptions($attributes).'></source>';
}

/** Returns the HTML code for a col element */
function col (string $content, array $attributes = []): string {
	return '<col '.parseOptions($attributes).'>'.$content.'</col>';
}

/** Returns the HTML code for a keygen element */
function keygen (string $content, array $attributes = []): string {
	return '<keygen '.parseOptions($attributes).'>'.$content.'</keygen>';
}

/** Returns the HTML code for a track element */
function track (string $content, array $attributes = []): string {
	return '<track '.parseOptions($attributes).'>'.$content.'</track>';
}

/** Returns the HTML code for a embed element */
function embed (string $content, array $attributes = []): string {
	return '<embed '.parseOptions($attributes).'>'.$content.'</embed>';
}

/** Returns the HTML code for a wbr element */
function wbr (string $content, array $attributes = []): string {
	return '<wbr '.parseOptions($attributes).'>'.$content.'</wbr>';
}

/** Returns the HTML code for a dd element */
function dd (string $content, array $attributes = []): string {
	return '<dd '.parseOptions($attributes).'>'.$content.'</dd>';
}

/** Returns the HTML code for a tbody element */
function tbody (string $content, array $attributes = []): string {
	return '<tbody '.parseOptions($attributes).'>'.$content.'</tbody>';
}

/** Returns the HTML code for a thead element */
function thead (string $content, array $attributes = []): string {
	return '<thead '.parseOptions($attributes).'>'.$content.'</thead>';
}

/** Returns the HTML code for a tfoot element */
function tfoot (string $content, array $attributes = []): string {
	return '<tfoot '.parseOptions($attributes).'>'.$content.'</tfoot>';
}

/** Returns the HTML code for a dt element */
function dt (string $content, array $attributes = []): string {
	return '<dt '.parseOptions($attributes).'>'.$content.'</dt>';
}

/** Returns the HTML code for a colgroup element */
function colgroup (string $content, array $attributes = []): string {
	return '<colgroup '.parseOptions($attributes).'>'.$content.'</colgroup>';
}

/** Returns the HTML code for a address element */
function address (string $content, array $attributes = []): string {
	return '<address '.parseOptions($attributes).'>'.$content.'</address>';
}

/** Returns the HTML code for a hgroup element */
function hgroup (string $content, array $attributes = []): string {
	return '<hgroup '.parseOptions($attributes).'>'.$content.'</hgroup>';
}

/** Returns the HTML code for a blockquote element */
function blockquote (string $content, array $attributes = []): string {
	return '<blockquote '.parseOptions($attributes).'>'.$content.'</blockquote>';
}

/** Returns the HTML code for a dl element */
function dl (string $content, array $attributes = []): string {
	return '<dl '.parseOptions($attributes).'>'.$content.'</dl>';
}

/** Returns the HTML code for a figcaption element */
function figcaption (string $content, array $attributes = []): string {
	return '<figcaption '.parseOptions($attributes).'>'.$content.'</figcaption>';
}

/** Returns the HTML code for a figure element */
function figure (string $content, array $attributes = []): string {
	return '<figure '.parseOptions($attributes).'>'.$content.'</figure>';
}

/** Returns the HTML code for a main element */
function main (string $content, array $attributes = []): string {
	return '<main '.parseOptions($attributes).'>'.$content.'</main>';
}

/** Returns the HTML code for a pre element */
function pre (string $content, array $attributes = []): string {
	return '<pre '.parseOptions($attributes).'>'.$content.'</pre>';
}

/** Returns the HTML code for a abbr element */
function abbr (string $content, array $attributes = []): string {
	return '<abbr '.parseOptions($attributes).'>'.$content.'</abbr>';
}

/** Returns the HTML code for a b element */
function b (string $content): string {
	return '<u>'.$content.'</u>';
}


/** Returns the HTML code for a u element */
function u (string $content): string {
	return '<u>'.$content.'</u>';
}

/** Returns the HTML code for a bdi element */
function bdi (string $content, array $attributes = []): string {
	return '<bdi '.parseOptions($attributes).'>'.$content.'</bdi>';
}

/** Returns the HTML code for a cite element */
function cite (string $content, array $attributes = []): string {
	return '<cite '.parseOptions($attributes).'>'.$content.'</cite>';
}

/** Returns the HTML code for a code element */
function code (string $content, array $attributes = []): string {
	return '<code '.parseOptions($attributes).'>'.$content.'</code>';
}

/** Returns the HTML code for a del element */
function del (string $content, array $attributes = []): string {
	return '<del '.parseOptions($attributes).'>'.$content.'</del>';
}

/** Returns the HTML code for a dfn element */
function dfn (string $content, array $attributes = []): string {
	return '<dfn '.parseOptions($attributes).'>'.$content.'</dfn>';
}

/** Returns the HTML code for a em element */
function em (string $content, array $attributes = []): string {
	return '<em '.parseOptions($attributes).'>'.$content.'</em>';
}

/** Returns the HTML code for a i element */
function i (string $content): string {
	return '<i>'.$content.'</i>';
}

/** Returns the HTML code for a ins element */
function ins (string $content, array $attributes = []): string {
	return '<ins '.parseOptions($attributes).'>'.$content.'</ins>';
}

/** Returns the HTML code for a kbd element */
function kbd (string $content, array $attributes = []): string {
	return '<kbd '.parseOptions($attributes).'>'.$content.'</kbd>';
}

/** Returns the HTML code for a mark element */
function mark (string $content, array $attributes = []): string {
	return '<mark '.parseOptions($attributes).'>'.$content.'</mark>';
}

/** Returns the HTML code for a q element */
function q (string $content, array $attributes = []): string {
	return '<q '.parseOptions($attributes).'>'.$content.'</q>';
}

/** Returns the HTML code for a rp element */
function rp (string $content, array $attributes = []): string {
	return '<rp '.parseOptions($attributes).'>'.$content.'</rp>';
}

/** Returns the HTML code for a rt element */
function rt (string $content, array $attributes = []): string {
	return '<rt '.parseOptions($attributes).'>'.$content.'</rt>';
}

/** Returns the HTML code for a ruby element */
function ruby (string $content, array $attributes = []): string {
	return '<ruby '.parseOptions($attributes).'>'.$content.'</ruby>';
}

/** Returns the HTML code for a s element */
function s (string $content, array $attributes = []): string {
	return '<s '.parseOptions($attributes).'>'.$content.'</s>';
}

/** Returns the HTML code for a samp element */
function samp (string $content, array $attributes = []): string {
	return '<samp '.parseOptions($attributes).'>'.$content.'</samp>';
}

/** Returns the HTML code for a small element */
function small (string $content, array $attributes = []): string {
	return '<small '.parseOptions($attributes).'>'.$content.'</small>';
}

/** Returns the HTML code for a strong element */
function strong (string $content, array $attributes = []): string {
	return '<strong '.parseOptions($attributes).'>'.$content.'</strong>';
}

/** Returns the HTML code for a sub element */
function sub (string $content, array $attributes = []): string {
	return '<sub '.parseOptions($attributes).'>'.$content.'</sub>';
}

/** Returns the HTML code for a sup element */
function sup (string $content, array $attributes = []): string {
	return '<sup '.parseOptions($attributes).'>'.$content.'</sup>';
}

/** Returns the HTML code for a time element */
function timeTag (string $content, array $attributes = []): string {
	return '<time '.parseOptions($attributes).'>'.$content.'</time>';
}

/** Returns the HTML code for a var element */
function varTag (string $content, array $attributes = []): string {
	return '<var '.parseOptions($attributes).'>'.$content.'</var>';
}

/** Returns the HTML code for a wbr element */
function wbrTag (string $content, array $attributes = []): string {
	return '<wbr '.parseOptions($attributes).'>'.$content.'</wbr>';
}

/** Returns the HTML code for a tag element */
function tag (string $content, array $attributes = []): string {
	return '<tag '.parseOptions($attributes).'>'.$content.'</tag>';
}

/** Returns the HTML code for a data element */
function data (string $content, array $attributes = []): string {
	return '<data '.parseOptions($attributes).'>'.$content.'</data>';
}

/** Returns the HTML code for a datalist element */
function datalist (string $content, array $attributes = []): string {
	return '<datalist '.parseOptions($attributes).'>'.$content.'</datalist>';
}

/** Returns the HTML code for a fieldset element */
function fieldset (string $content, array $attributes = []): string {
	return '<fieldset '.parseOptions($attributes).'>'.$content.'</fieldset>';
}

/** Returns the HTML code for a meter element */
function meter (string $content, array $attributes = []): string {
	return '<meter '.parseOptions($attributes).'>'.$content.'</meter>';
}

/** Returns the HTML code for a optgroup element */
function optgroup (string $content, array $attributes = []): string {
	return '<optgroup '.parseOptions($attributes).'>'.$content.'</optgroup>';
}

/** Returns the HTML code for a select element */
function select (string $content, string $name, array $attributes = []): string {
	return '<select name="'.$name.'" '.parseOptions($attributes).'>'.$content.'</select>';
}

/** Returns the HTML code for a option element */
function option (string $caption, string $value, array $attributes = []): string {
	return '<option value="'.$value.'"'.parseOptions($attributes).'>'.$caption.'</option>';
}

/** Returns the HTML code for a output element */
function output (string $content, array $attributes = []): string {
	return '<output '.parseOptions($attributes).'>'.$content.'</output>';
}

/** Returns the HTML code for a progress element */
function progress (string $content, array $attributes = []): string {
	return '<progress '.parseOptions($attributes).'>'.$content.'</progress>';
}

/** Returns the HTML code for a textarea element */
function textarea (string $content, string $name, array $attributes = []): string {
	return '<textarea name="'.$name.'"'.parseOptions($attributes).'>'.$content.'</textarea>';
}

/** Returns the HTML code for a iframe element */
function iframe (string $content, string $src, array $attributes = []): string {
	return '<iframe src="'.$src.'"'.parseOptions($attributes).'>'.$content.'</iframe>';
}

/** Returns the HTML code for a object element */
function object (string $content, array $attributes = []): string {
	return '<object '.parseOptions($attributes).'>'.$content.'</object>';
}

/** Returns the HTML code for a audio element */
function audio (string $src, string $type, array $attributes = []): string {
	return '<audio src="'.$src.'" type="'.$type.'" '.parseOptions($attributes).'></audio>';
}

/** Returns the HTML code for a map element */
function map (string $content, array $attributes = []): string {
	return '<map '.parseOptions($attributes).'>'.$content.'</map>';
}

/** Returns the HTML code for a track element */
function trackTag (string $content, array $attributes = []): string {
	return '<track '.parseOptions($attributes).'>'.$content.'</track>';
}

/** Returns the HTML code for a video element */
function video (string $content, string $width, string $height, bool $autoplay, array $attributes = []): string {
	return '<video width="'.$width.'" height="'.$height.'" '.($autoplay ? 'autoplay' : '').' '.parseOptions($attributes).'>'.$content.'</video>';
}

/** Returns the HTML code for a details element */
function details (string $content, array $attributes = []): string {
	return '<details '.parseOptions($attributes).'>'.$content.'</details>';
}

/** Returns the HTML code for a dialog element */
function dialog (string $content, array $attributes = []): string {
	return '<dialog '.parseOptions($attributes).'>'.$content.'</dialog>';
}

/** Returns the HTML code for a menu element */
function menu (string $content, array $attributes = []): string {
	return '<menu '.parseOptions($attributes).'>'.$content.'</menu>';
}

/** Returns the HTML code for a summary element */
function summary (string $content, array $attributes = []): string {
	return '<summary '.parseOptions($attributes).'>'.$content.'</summary>';
}

/** Returns the HTML code for a canvas element */
function canvas (string $content, array $attributes = []): string {
	return '<canvas '.parseOptions($attributes).'>'.$content.'</canvas>';
}


/**
 * @param string $name The name of the item
 * @param int $quantity The number of this item
 * @param float $price The price of an individual item
 * @param float $shipping The shipping cost of this item
 * @return array|array[] The cart item array
 */
function paypalCartItem (string $name, int $quantity, float $price, float $shipping): array {
	return
		[
			$name =>
				[
					'price' => $price,
					'qty' => $quantity,
					'shipping' => $shipping
				]
		];
}

/**
 * Gets a Paypal form with a single amount
 * @param string $sendButton The button or element that will submit the form
 * @param string $purchaseLabel a string that will be displayed to the buyer
 * @param float $amount The total amount (excluding tax and shipping)
 * @param float $shippingCost The cost of shipping
 * @param float $taxes The tax cost of the transaction
 * @param string $returnLink The link to follow upon a successful transaction
 * @param string $cancelLink The link to follow upon a canceled transaction
 * @param string $formLogo The URL of the logo that will be displayed to the buyer
 * @param string $callbackLink The link that the paypal servers will contact shortly after a successful purchase
 * @param array $additionalInputs Any additional form input. Ideal for the 'custom' input
 * @param string $currency The currency code. _CURRENCY by default
 * @param array $attributes Any additional attribute to add to the form itself
 * @return string The paypal form
 */
function paypalFormSimple (
	string $sendButton,
	string $purchaseLabel,
	float $amount,
	float $shippingCost,
	float $taxes,
	string $returnLink,
	string $cancelLink,
	string $formLogo = "",
	string $callbackLink = "",
	array $additionalInputs = [],
	string $currency = "",
	array $attributes = []
) {


	$variables = mergeArrays(
		[
			'cmd' => '_xclick',
			'currency_code' => $currency ? $currency : getConfig("CURRENCY"),
			'cancel_return' => $cancelLink,
			'return' => $returnLink,
			'notify_url' => $callbackLink,
			'shipping' => number_format(floatval($shippingCost), 2, '.', ''),
			'item_name' => $purchaseLabel,
			'amount' => number_format(floatval($amount), 2, '.', ''),
			'tax' => number_format(floatval($taxes), 2, '.', ''),
			'image_url' => $formLogo,
			'business' => getConfig("PAYPAL_BUSINESS_EMAIL"),
			'charset' => 'utf-8'
		],
		$additionalInputs
	);

	$inputs = "";
	foreach ($variables as $key => $variable) {
		$inputs .= input('hidden', $key, ['id' => $key, 'value' => $variable]);
	}
	return
		form(
			$inputs.$sendButton,
			'post',
			_PAYPAL_WEBSRC_LINK
			, $attributes
		);
}


/**
 * Gets a paypal form with a cart
 * @param string $sendButton The button or element that will submit the form
 * @param string $returnLink The link to follow upon a successful transaction
 * @param string $cancelLink The link to follow upon a canceled transaction
 * @param float $taxes The tax cost of the transaction
 * @param array $cart The cart, an array of item built with paypalCartItem
 * @param string $callbackLink The link to follow upon a canceled transaction
 * @param string $formLogo The URL of the logo that will be displayed to the buyer
 * @param array $additionalInputs Any additional form input. Ideal for the 'custom' input
 * @param string $currency The currency code. _CURRENCY by default
 * @param array $attributes Any additional attribute to add to the form itself
 * @return string The paypal form
 */
function paypalFormCart (
	string $sendButton,
	string $returnLink,
	string $cancelLink,
	float $taxes,
	array $cart,
	string $callbackLink = "",
	string $formLogo = "",
	array $additionalInputs = [],
	string $currency = "",
	array $attributes = []
) {
	$cartArray = [];
	$index = 1;
	foreach ($cart as $key => $value) {
		if (is_array($value)) {
			$cartArray['item_name_'.$index] = $key;
			$cartArray['amount_'.$index] = number_format(floatval($value['price']), 2, '.', '');
			$cartArray['quantity_'.$index] = $value['qty'];
			$cartArray['shipping_'.$index] = number_format(floatval($value['shipping']), 2, '.', '');
			$index++;
		}
	}


	$variables = mergeArrays(
		[
			'cmd' => '_cart',
			'currency_code' => $currency ? $currency : getConfig("CURRENCY"),
			'cancel_return' => $cancelLink,
			'return' => $returnLink,
			'notify_url' => $callbackLink,
			'image_url' => $formLogo,
			'business' => getConfig("PAYPAL_BUSINESS_EMAIL"),
			'charset' => 'utf-8',
			'upload' => 1,
			'tax_cart' => number_format(floatval($taxes), 2, '.', '')
		],
		$additionalInputs,
		$cartArray
	);

	$inputs = "";
	foreach ($variables as $key => $variable) {
		$inputs .= input('hidden', $key, ['id' => $key, 'value' => $variable]);
	}
	return
		form(
			$inputs.$sendButton,
			'post',
			_PAYPAL_WEBSRC_LINK
			, $attributes
		);
}

/**
 * Generates a javascript loader, based on project configs
 * @param string $path Full local path of the script
 * @return string The script tag
 */
function getJsLoader (string $path): string {
	return _getLoaderUrl($path, 'js');
}

/**
 * Generates a css loader, based on project configs
 * @param string $path Full local path of the stylesheet
 * @return string The css tag
 */
function getCssLoader (string $path): string {
	return _getLoaderUrl($path, 'css');
}

/**
 * Generates a 'Login with Google' button
 * Please keep in mind that this tag will auto-fire if the user has already connected this way
 * @param array $actData The data built with actData() that will be used as a callback on success. The token will be
 * $request['id_token']. Can be empty if $callbackFunction is provided
 * @param string $callbackFunction The javascript callback function. $actData is ignored if this is provided. Else,
 * a simple $.post function will be generated. Can be ommited if $actData is provided
 * @return string The google button
 */
function getGoogleLogin (array $actData, string $callbackFunction = ''): string {
	$signInHtml = '';
	if (getConfig("GOOGLE_LOGIN_API_KEY")) {
		$signInHtml .=
			meta('', ['name' => 'google-signin-client_id', 'content' => getConfig("GOOGLE_LOGIN_API_KEY")])
			.meta('', ['name' => 'google-signin-scope', 'content' => 'profile email'])
			.script('', 'https://apis.google.com/js/platform.js', true, true);

		$callback = $callbackFunction;
		if ($callbackFunction === '') {
			$callback = strval('func_'.uniqid());
		}
		$signInHtml .=
			div(
				''
				, ['class' => 'g-signin2', 'data-onsuccess' => $callback]
			);
		if ($callbackFunction == '') {
			$signInHtml .=
				script(
					'function '.$callback.' (googleUser) {
						let id_token = googleUser.getAuthResponse().id_token;
						$.post(
							_URL + \'dispatch.php\',
							{a: \''.$actData['a'].'\', path: \''.$actData['path'].'\', token: id_token},
							function (data) {
								if(data.js){
									eval(data.js);
								}
							},
							\'json\'
						);
					}
					'
				);
		}
	}
	return $signInHtml;
}

/**
 * Returns a popup Modal at the center of the screen, blocking the rest
 * @param string $content The content of the modal
 * @param int $width The width, in screen percent
 * @param int $height The height, in screen percent
 * @param string $id The id to give the modal
 * @param bool $hidden Wether or not this modal should be visible right away
 * @return string The modal HTML
 */
function getModalPopup (string $content, int $width, int $height, string $id, bool $hidden = false): string {
	$style =
		'
			left: '.((100 - $width) / 2).'vw;
			top: '.((100 - $height) / 2).'vh;
			width: '.$width.'vw;
		 	height: '.$height.'vh;
		 	position: fixed;
		 	display: '.($hidden ? 'none' : 'block').';
		 	background-color: rgba(200,200,200,1);
		 	border-style: solid;
		 	border-color: '.getConfig("SITE_HIGHLIGHT_COLOR").';
		 	text-align: center;
		';

	//Maybe click on
	return
		div(
			div(
				$content
				,
				[
					'style' => $style
				]
			)
			, attributes($id, 'htmlfactory-modal')
		);
}

/**
 * Create an HTML table for a particular dataset
 * @param string $tableName The name of the table used
 * @param mysqli_result $data The mysqli_result set that will populate the table. Must include all the table columns
 * @param array $attributes HTML attributes to add to the table tag. Can be ommited
 * @param array $permissionsOverride Allows you to override default table permissions
 * @param string $dbHook The database hook to use, if you want to override it
 * @return string The HTML of the table
 */
function getTableView (string $tableName, mysqli_result $data, array $attributes = [], array $permissionsOverride = [], string $dbHook = _BASE_DB_HOOK): string {
	$preTable = '';
	$tableHeader = '';
	$tableContent = '';

	$descVarName = $tableName.'TableDesc';
	global $$descVarName;
	$descVar = $$descVarName;

	$permissions = (empty($permissionsOverride) ? $descVar['permissions'] : $permissionsOverride);


	if (in_array('insert', $permissions)) {
		$preTable .=
			button(
				'Insert', array_merge(
							['style' => 'float: left;margin-left: 2.5%'],
							patchData('addOrEditTable', ['tableName' => $tableName]),
							jsTrigger('click')
						)
			).br(1);
	}

	if (in_array('search', $permissions)) {
		$tableStructure = executeQuery($dbHook, 'DESCRIBE '.$tableName.';');
		$searchFormData = '';
		foreach ($descVar as $columnName => $columnValue) {
			if ($columnName == 'permissions') continue;
			$reader = $tableStructure->fetch_row();
			if (in_array('hidden', $columnValue)) continue;
			if (in_array('password', $columnValue)) continue;
			if (in_array('nosearch', $columnValue)) continue;
			if ($reader == null) break;

			//Generate input for search, similar to getForm
			$type = substr($reader[1], 0, strpos($reader[1], '('));
			$subStr = substr($reader[1], strlen($type), strlen($reader[1]) - strlen($type));
			$subStr = str_replace('(', '', $subStr);
			$subStr = str_replace(')', '', $subStr);
			if (str_contains($reader[1], 'tinyint')) {
				$searchFormData .=
					select(
						option($columnValue['desc'], '')
						.option("Yes", '1', ($_SESSION['filter'][$tableName][$reader[0]] == 1 and $_SESSION['filter'][$tableName][$reader[0]] != "") ? ['selected' => ''] : [])
						.option("No", '0', ($_SESSION['filter'][$tableName][$reader[0]] == 0 and $_SESSION['filter'][$tableName][$reader[0]] != "") ? ['selected' => ''] : [])
						, $reader[0]
					);
			} else if (str_contains($reader[1], 'enum')) {
				$values = explode(',', $subStr);
				$enumOptions = option($columnValue['desc'], '');
				foreach ($values as $value) {
					$value = str_replace('\'', '', $value);
					$enumOptions .=
						option(ucfirst($value), $value, $_SESSION['filter'][$tableName][$reader[0]] == $value ? ['selected' => ''] : []);
				}
				$searchFormData .=
					select($enumOptions, $reader[0]);
			} else if (str_contains($reader[1], 'int') and $reader[3] == "MUL") {
				$options = option($columnValue['desc'], '');
				$columnToSearch = $columnValue['fk_column'];
				$tableToSearch = $columnValue['fk_table'];
				$values = executeQuery(
					$dbHook,
					'SELECT '.$columnToSearch.',id_'.$tableToSearch.' FROM '.$tableToSearch.';'
				);
				if ($values instanceof mysqli_result) {
					while (($foreignInstance = $values->fetch_row()) != null) {
						$selected = ($foreignInstance[1] == $_SESSION['filter'][$tableName][$reader[0]]);
						$attrs = [];
						if ($selected) {
							$attrs['selected'] = '';
						}
						$options .= option($foreignInstance[0], $foreignInstance[1], $attrs);
					}
					$searchFormData .= select($options, $reader[0]);
				}
			} else if (str_contains($reader[1], 'datetime')) {
				$searchFormData .=
					input('datetime-local', $reader[0], $_SESSION['filter'][$tableName][$reader[0]] ? $_SESSION['filter'][$tableName][$reader[0]] : '', $columnValue['desc']);
			} else if (str_contains($reader[1], 'date')) {
				$searchFormData .=
					input('date', $reader[0], $_SESSION['filter'][$tableName][$reader[0]] ? $_SESSION['filter'][$tableName][$reader[0]] : '', $columnValue['desc']);
			} else {
				$searchFormData .=
					input('text', $reader[0], $_SESSION['filter'][$tableName][$reader[0]] ? $_SESSION['filter'][$tableName][$reader[0]] : '', ['placeholder' => $columnValue['desc']]);
			}
		}
		$searchFormData .= button('Search');
		$searchFormData .= button('Clear', mergeArrays(patchData('getTable', ['search' => 'clear', 'tableName' => $tableName, 'replace' => '#'.$tableName.'-factory-table']), jsTrigger('click')));
		$searchFormData .= input('hidden', 'search', 'yes');
		$preTable .= form(
			$searchFormData, 'post', '', mergeArrays(
			patchData('getTable', ['tableName' => $tableName, 'replace' => '#'.$tableName.'-factory-table', 'hookOverride' => $dbHook]), jsTrigger('submit')
		)
		);
	}

	foreach ($descVar as $columnName => $columnValue) {
		if ($columnName == 'permissions') continue;
		if (in_array('hidden', $columnValue)) continue;
		if (in_array('password', $columnValue)) continue;
		if (in_array('notable', $columnValue)) continue;

		$headerContent = $columnValue['desc'];
		$attrsArray = [];

		if (in_array('sort', $permissions)) {
			$sortState = $_SESSION['orderby'][$tableName];
			$headerOrderAttribute = '';

			if ($sortState) {
				$sortStateColumn = array_keys($sortState)[0];
				if ($sortStateColumn == $columnName) {
					$sortStateOrientation = array_values($_SESSION['orderby'][$tableName])[0];
					if ($sortStateOrientation == 'asc') {
						$headerContent .= " &#9652";
						$headerOrderAttribute = $columnName.',desc';
					} else if ($sortStateOrientation == 'desc') {
						$headerContent .= " &#9662";
						$headerOrderAttribute = 'clear';
					}
				}
			}
			if (empty($headerOrderAttribute)) {
				$headerOrderAttribute = $columnName.',asc';
			}
			$attrsArray = mergeArrays(
				patchData('getTable', ['replace' => '#'.$tableName.'-factory-table', 'tableName' => $tableName, 'hookOverride' => $dbHook, 'orderby' => $headerOrderAttribute]),
				jsTrigger('click'),
				['style' => 'cursor: pointer;']
			);
		}

		$tableHeader .= th(
			($headerContent ? $headerContent : ''),
			$attrsArray
		);
	}
	if (in_array('delete', $permissions)) {
		$tableHeader .= th('');
	}
	if (in_array('clone', $permissions)) {
		$tableHeader .= th('');
	}

	while (($reader = $data->fetch_row()) != null) {
		$cells = '';
		$index = 0;
		foreach ($descVar as $key => $columnDesc) {
			if (
				in_array('hidden', $columnDesc)
				or in_array('password', $columnDesc)
				or in_array('notable', $columnDesc)
			) {
				$index++;
				continue;
			}
			if ($key == 'permissions') continue;
			$updateArgs = [];
			$value = $reader[$index];
			if (in_array('update', $permissions)) {
				$updateArgs = mergeArrays(
					patchData('addOrEditTable', ['i' => $reader[0], 'tableName' => $tableName, 'hookOverride' => $dbHook])
					, jsTrigger('click')
					, ['style' => 'cursor: pointer;']
				);
			}

			if (array_key_exists('fk_table', $columnDesc) and array_key_exists('fk_column', $columnDesc)) {
				$columnToSearch = $columnDesc['fk_column'];
				$tableToSearch = $columnDesc['fk_table'];
				$foreignVal =
					executeQuery(
						$dbHook,
						'SELECT '.$columnToSearch.' FROM '.$tableToSearch.' WHERE id_'.$tableToSearch.' = ?;',
						[['i' => $reader[$index]]]
					);
				if ($foreignVal instanceof mysqli_result) {
					if (($val = $foreignVal->fetch_row()) != null) {
						$value = $val[0];
					}
				}
			}

			if (in_array('bool', $columnDesc)) {
				$value = ($value == '1' ? 'Yes' : 'No');
			}

			if (array_key_exists('replace-'.$reader[$index], $columnDesc)) {
				$value = $columnDesc['replace-'.$reader[$index]];
			}


			$cells .= td(($value != null ? $value : 'Null'), $updateArgs);
			$index++;
		}
		if (in_array('delete', $permissions)) {
			$cells .= td(
				button(
					'Delete', mergeArrays(
					patchData('deleteTable', ['i' => $reader[0], 'tableName' => $tableName, 'hookOverride' => $dbHook])
					, jsTrigger('click')
				)
				)
			);
		}
		if (in_array('clone', $permissions)) {
			$cells .= td(
				button(
					'Clone', mergeArrays(
					patchData('cloneTable', ['i' => $reader[0], 'tableName' => $tableName, 'hookOverride' => $dbHook])
					, jsTrigger('click')
				)
				)
			);
		}
		$tableContent .= tr($cells);
	}

	$table =
		table(tr($tableHeader).$tableContent, $attributes);


	return div($preTable.$table, attributesClass($tableName.'-factory-table factory-table'));
}

/**
 * Returns the form to add or edit an entry
 * @param string $tableName The name of the table
 * @param array $initialValues The inital values to populate the form. Can be empty
 * @param string $dbHook The database hook to use, if you want to override it
 * @return string The HTML of the form
 */
function getFormView (string $tableName, array $initialValues = [], string $dbHook = _BASE_DB_HOOK): string {
	$fields = executeQuery(
		$dbHook,
		'DESCRIBE '.$tableName.';'
	);

	$formHtml = '';
	if ($fields instanceof mysqli_result) {
		$readerIndex = 0;
		while (($reader = $fields->fetch_row()) != null) {
			$name = $reader[0];
			$descVarName = $tableName.'TableDesc';
			global $$descVarName;
			$descVar = $$descVarName;
			$descVar = $descVar[$name];
			$desc = $descVar['desc'];
			$readonly = [];
			if (in_array('readonly', $descVar)) {
				$readonly = ['disabled' => ''];
			}
			if (!in_array('optional', $descVar)) {
				$readonly['required'] = '';
			}
			if (
				$desc == null
				or in_array('hidden', $descVar)
				or in_array('noform', $descVar)
			) {
				$readerIndex++;
				continue;
			}
			if (strpos($reader[1], '(') !== false) {
				$type = substr($reader[1], 0, strpos($reader[1], '('));
				$subStr = substr($reader[1], strlen($type), strlen($reader[1]) - strlen($type));
				$subStr = str_replace('(', '', $subStr);
				$subStr = str_replace(')', '', $subStr);
			} else {
				$type = $reader[1];
			}
			$initialValue = $initialValues[$readerIndex];

			$indivEntry = '';
			$indivEntry .= label($desc.': ', $name);
			$indivEntry .= br();
			switch ($type) {
				case 'int':
					if ($reader[3] == "MUL") {
						//Foreign key
						$columnToSearch = $descVar['fk_column'];
						$tableToSearch = $descVar['fk_table'];

						$disabled = [];
						if ($reader[2] == 'NO') {
							$disabled = ['disabled' => ''];
						}

						$selectOptions = option($desc, 'null', mergeArrays(['selected' => ''], $disabled));

						$foreignValues = executeQuery(
							$dbHook,
							'SELECT '.$columnToSearch.', id_'.$tableToSearch.' FROM '.$tableToSearch.';'
						);

						if ($foreignValues instanceof mysqli_result) {
							while (($foreignReader = $foreignValues->fetch_row()) != null) {
								$selected = ($initialValue == $foreignReader[1]);
								$attrs = [];
								if ($selected) {
									$attrs['selected'] = '';
								}
								$selectOptions .= option($foreignReader[0], $foreignReader[1], $attrs);
							}
						}

						$indivEntry .= select($selectOptions, $name, $readonly);
					} else {
						//Actual int
						$value = ($initialValue != null ? $initialValue : 0);
						$indivEntry .= input('number', $name, $value, mergeArrays(['step' => '1'], $readonly));
					}
					break;
				case 'float':
					//Float
					$value = ($initialValue != null ? $initialValue : '0.00');
					$indivEntry .= input('number', $name, $value, mergeArrays(['step' => '0.01'], $readonly));
					break;
				case 'varchar':
					//Text
					$max = substr($subStr, 0, strlen($subStr));
					$value = ($initialValue != null ? $initialValue : '');
					$finalType = (in_array('password', $descVar) ? 'password' : 'text');
					$indivEntry .= input($finalType, $name, $value, mergeArrays(['maxlength' => $max], $readonly));
					break;
				case 'tinyint':
					//Bool
					$selectOptions = option($desc, '', ['disabled' => '', 'selected' => '']);
					foreach (['Yes' => '1', 'No' => '0'] as $key => $value) {
						$selected = ($initialValue == $value ? ['selected' => ''] : []);
						$selectOptions .= option($key, $value, $selected);
					}
					$indivEntry .= select($selectOptions, $name, $readonly);
					break;
				case 'longtext':
				case 'mediumtext':
					//Area
					$value = ($initialValue != null ? $initialValue : '');
					if (in_array('cke', $descVar)) {
						$ckeId = uniqid();
						$indivEntry .=
							textarea($value, $ckeId, attributes($ckeId, '', ['class' => 'js-form-ignore']))
							.script(
								"
								ckeditor.$name = CKEDITOR.replace('$ckeId'); 	
							"
							);
					} else {
						$indivEntry .= textarea($value, $name, $readonly);
					}
					break;
				case 'date':
					//Date
					$value = ($initialValue != null ? $initialValue : '');
					$indivEntry .= input('date', $name, $value, $readonly);
					break;
				case 'datetime':
					//Date & time
					$value = ($initialValue != null ? $initialValue : '');
					$indivEntry .= input('datetime-local', $name, $value, $readonly);
					break;
				case 'time':
					//Time
					$value = ($initialValue != null ? $initialValue : '');
					$indivEntry .= input('time', $name, $value, $readonly);
					break;
				case 'enum':
					//Select
					$possibleValues = explode(',', $subStr);
					$disabled = [];
					if ($reader[2] == 'NO') {
						$disabled = ['disabled' => ''];
					}

					$selectOptions = option($desc, 'null', mergeArrays(['selected' => ''], $disabled));
					foreach ($possibleValues as $possibleValue) {
						$possibleValue = str_replace('\'', '', $possibleValue);
						$selected = ($initialValue == $possibleValue);
						$otherAttributes = [];
						if ($selected) $otherAttributes['selected'] = '';
						$selectOptions .= option(ucfirst($possibleValue), $possibleValue, mergeArrays($otherAttributes));
					}
					$indivEntry .= select($selectOptions, $name, $readonly);
					break;
			}
			$formHtml .= div($indivEntry, attributesClass('factory-form-entry'));
			$readerIndex++;
		}
	}

	return div(
		$formHtml.br(1).div('',attributesClass('factory-form-end')),
		attributesClass("$tableName-factory-form factory-form")
	);
}

/**
 * Generates a Mapbox map
 * @param array $position Associative array. Values are 'lon', 'lat' and 'zoom'
 * @param string $width The width (css attribute) of the map
 * @param string $height The height (css attribute) of the map
 * @param array $markers Array of associative arrays for the marker. Values are:
 * 'lon', 'lat', 'onclick' (in javascript), 'icon' (url to an image. Optional) and 'size' (Optional. Only useful when paired with icon)
 * @param string $style The style of the map. See https://docs.mapbox.com/api/maps/#styles
 * @param array $attributes Additional standard attributes at add the map
 * @return string The map HTML
 */
function getMapboxMap (array $position, string $width, string $height, array $markers = [], string $style = 'streets-v11', array $attributes = []): string {
	$id = uniqid();

	$mapVarId = 'map_'.uniqid();
	$js = '';

	if ($GLOBALS['mapboxSet'] !== true) {
		$js .= 'mapboxgl.accessToken = \''.getConfig("MAPBOX_API_KEY").'\';';
		$GLOBALS['mapboxSet'] = true;
	}
	$js .=
		'
			var '.$mapVarId.' = new mapboxgl.Map({
				container: \''.$id.'\',
				style: \'mapbox://styles/mapbox/'.$style.'\',
				center: ['.$position['lon'].', '.$position['lat'].'],
				zoom: '.$position['zoom'].'
			});
			
		';

	if (!empty($markers)) {
		$js .= 'let tmp_marker = null;';

		foreach ($markers as $marker) {
			if (array_key_exists('icon', $marker)) {
				$iconSize = '50px';
				if (array_key_exists('size', $marker)) $iconSize = $marker['size'];
				$js .=
					'
						tmp_marker = document.createElement(\'div\');
						tmp_marker.className = \'marker\';
						'.(array_key_exists('onclick', $marker) ?
						'tmp_marker.className += \' marker-pointer\';' :
						''
					).'
						'.(array_key_exists('round', $marker) and $marker['round'] == 'yes' ?
							'tmp_marker.className += \' marker-round\'' :
							''
					).'
						tmp_marker.style.backgroundImage = \'url('.$marker['icon'].')\';
						tmp_marker.style.backgroundSize = \'contain\';
						tmp_marker.style.width = \''.$iconSize.'\';
						tmp_marker.style.height = \''.$iconSize.'\';
					';

				if (array_key_exists('onclick', $marker)) {
					$js .=
						'
							$(tmp_marker).click(function (e) {
								'.$marker['onclick'].'
							});
						';
				}

				$js .=
					'
						new mapboxgl.Marker(tmp_marker)
						.setLngLat(['.$marker['lon'].','.$marker['lat'].'])
						.addTo('.$mapVarId.');
					';

			} else {
				$js .=
					'
						tmp_marker = new mapboxgl.Marker()
						.setLngLat(['.$marker['lon'].','.$marker['lat'].'])
						.addTo('.$mapVarId.');
						tmp_marker.getElement().className += \' marker\';
					';
				if (array_key_exists('onclick', $marker)) {
					$js .=
						'
							$(tmp_marker.getElement()).click(function (e) {
								'.$marker['onclick'].'								
							});
							tmp_marker.getElement().className += \' marker-pointer\';
						';
				}
			}
		}
	}

	$style = 'width: '.$width.'; height: '.$height.'; ';
	if (array_key_exists('style', $attributes)) {
		$style .= $attributes['style'];
		unset($attributes['style']);
	}

	return
		div('', attributes($id, '', mergeArrays(['style' => $style], $attributes)))
		.script($js);
}

/**
 * Generates a simple yet flexible Stripe Element
 * @param string $onClickPay The javascript function to be called. Expected signature is (Stripe\Element, Stripe)
 * @param string $type The type of Element. See https://stripe.com/docs/js/elements_object/create_element
 * @param array $attributes The standard attribute to be applied to the containing div
 * @return string The HTML of the element
 */
function getStripeElement (string $onClickPay, string $type = "card", array $attributes = []): string {
	$stripeJs = '';

	if ($GLOBALS['stripeSet'] !== true) {
		$stripeJs .=
			"
				var stripe = Stripe('".getConfig("STRIPE_PK")."');
				var elements = stripe.elements();
				var element = elements.create('$type');
			";
		$GLOBALS['stripeSet'] = true;
	}

	$formId = uniqid();

	$stripeJs .=
		"
			$('#stripe-form-$formId').submit(function (e) {
				$onClickPay(element, stripe);
				e.preventDefault();
			});
			
			element.mount('#card-element-$formId');
			element.on('change', ({error}) => {
				const displayError = $('#card-errors-$formId');
				if (error) {
					displayError.text(error.message);
				} else {
					displayError.text('');
				}
			});
		";

	$stripeDiv =
		div(
			form(
				div(
					label(translate("Credit or debit card"), 'card-element')
					.div('', attributes('card-element-'.$formId))
					.div('', attributes('card-errors-'.$formId))
				)
				.button(translate("Pay"))
				, 'post', '', attributes('stripe-form-'.$formId, 'stripe-payment-form')
			)
			, $attributes
		);
	return $stripeDiv.script($stripeJs);
}

/**
 * Generates a language switcher, with the SEO metadata
 * @param bool $outputMeta Wether or not to include meta tags in the head
 * @param array $attributes Additional attributes of the resulting div
 * @return string The switcher HTML
 */
function getLanguageSwitcher (bool $outputMeta = true, array $attributes = []): string {
	global $output;
	$switcherHTML = '';

	$languages = explode(',', getConfig("SUPPORTED_LOCALES"));
	$actual_link = ((isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] === 'on') ? "https" : "http")."://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$actual_link = preg_replace("/lang=[a-zA-Z_]*/m", '', $actual_link);

	$actual_link = str_removeFromEnd($actual_link, '?');
	if (str_contains($actual_link, '?')) {
		$actual_link .= '&lang=';
	} else {
		$actual_link .= '?lang=';
	}
	foreach ($languages as $language) {
		$current = ($_SESSION['locale'] == $language ? ' active' : '');
		$switcherHTML .= a(
			ucfirst(substr($language, 0, 2)),
			$actual_link.$language,
			'_self',
			attributes('', 'language-link'.$current)
		);
		$switcherHTML .= " | ";
		if($outputMeta and $GLOBALS['languageMetaSet'] !== true){
			$output['head'] .=
				linkTag($actual_link.$language, 'alternate', ['hreflang' => $language]);
		}
	}

	if($outputMeta) $GLOBALS['languageMetaSet'] = true;
	$switcherHTML = str_removeFromEnd($switcherHTML, " | ");

	return div($switcherHTML, $attributes);
}