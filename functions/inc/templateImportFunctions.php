<?php
//This file should probably not be altered
use PHPHtmlParser\Dom;


function processFolder (string $folderPath, string $templateId) : bool {
	@mkdir(_ROOT_PATH.'resources/'.$templateId.'/');
	foreach (glob($folderPath.'/*') as $filePath){
		if(is_dir($filePath)){
			$fileInfo = pathinfo($filePath);
			//@mkdir($filePath.'/'.$fileInfo['basename']);
			recurse_copy($filePath, _ROOT_PATH.'resources/'.$templateId.'/'.$fileInfo['basename'].'/');
			setFilePermission(_ROOT_PATH.'resources/'.$templateId);
		}else{
			$fileInfo = pathinfo($filePath);
			if($fileInfo['extension'] == "html"){
				if(!is_file(_ROOT_PATH.'page/'.$fileInfo['filename'].'.php')) {
					file_put_contents(_ROOT_PATH.'page/'.$fileInfo['filename'].'.php', htmlToPhpHtmlFactory(cat($filePath), $templateId));
					setFilePermission(_ROOT_PATH.'page/'.$fileInfo['filename'].'.php');
				}
			}else{
				copy($filePath, _ROOT_PATH.'resources/'.$templateId.'/'.$fileInfo['basename']);
				setFilePermission(_ROOT_PATH.'resources/'.$templateId.'/'.$fileInfo['basename']);
			}
		}
	}
	return true;
}

$elementBlacklist =
	[
		'root',
		'html',
		'head',
		'body'
	]
;

$elementAddTag =
	[
		'header',
		'footer',
		'img',
		'link',
		'time',
		'var',
		'wbr',
		'track'
	]
;

$separators =
	[
		'head' => "\$output['head'] .= \r\n",
		'body' => ";\$output['body'] .= \r\n"
	]
;
function htmlToPhpHtmlFactory (string $inputHtml, string $templateId) : string {

	$_SESSION['temp_templateId'] = $templateId;

	$options = new \PHPHtmlParser\Options();
	$options = $options->setWhitespaceTextNode(false)
		->setRemoveScripts(false);
	$dom = new Dom();
	$dom->setOptions(
		$options
			->setWhitespaceTextNode(false)
			->setRemoveScripts(false)
	);
	try{
		$dom->loadStr($inputHtml);
	}catch (Exception $exception){
		return 'ERROR: '.$exception->getMessage().'; '.$exception->getTraceAsString();
	}

	$phpFileContent = "<?php\r\n//Generated with the Template Importation Function\r\n";

	indexDOMElement($dom->root, $phpFileContent, 0, false);

	$phpFileContent .= ';';
	return $phpFileContent;
}

/** Recursive functions to convert an HTML doc's root to a php */
function indexDOMElement (Dom\Node\AbstractNode $node, string &$output, int $indentIndex, bool $appended) : void {
	global $elementBlacklist, $elementAddTag, $separators;


	//Opening tag
	$tag = $node->getTag();
	$tagName = $tag->name();
	$functionName = $tagName;

	if(array_key_exists($tagName, $separators)){
		writeInOutput($output, $separators[$tagName], 0);
		$indentIndex = 0;
	}

	$hasContent =
		(
			method_exists($node, "getChildren") and
			$node->hasChildren() and
			$node->getChildren()[0]->isTextNode() and
			!empty($node->getChildren()[0]->text()) and
			!ctype_space($node->getChildren()[0]->text())
		);

	if(!in_array($tagName, $elementBlacklist)) {
		$dot = ($appended ? '.' : '');
		if ($tagName == "text") {
			if (!empty($node->text()) and !ctype_space($node->text())) {
				writeInOutput($output, $dot.'"'.$node->text()."\"\r\n", $indentIndex);
			}
		} else {
			if (function_exists($tagName.'_customOpeningTag')) {
				$functionName = ($tagName.'_customOpeningTag')();
			} else if (in_array($tagName, $elementAddTag)) {
				$functionName = $functionName.'Tag';
			}
			if (!function_exists($functionName)) die("An error occured: could not find HTML factory function for tag ".$tagName);
			writeInOutput($output, $dot.$functionName."(\r\n", $indentIndex);
		}
	}
	//Process children, if they exist
	if(method_exists($node, "getChildren")) {
		$childCount = $node->countChildren();
		$currentCount = 1;
		/** @var Dom\Node\AbstractNode $child */
		foreach ($node->getChildren() as $child) {
			indexDOMElement($child, $output, $indentIndex + 1, $currentCount > 1);
			$hasContent = true;
			if(
				!$child->isTextNode() or
				(
					$child->isTextNode() and
					!empty($child->text()) and
					!ctype_space($child->text())
				)
			) {
				$currentCount++;
			}
		}
	}

	if(!$node->isTextNode() and !in_array($tagName, $elementBlacklist)) {
		//Closing tag
		if (function_exists($tag->name().'_customClosingTag')) {
			writeInOutput($output,($tag->name().'_customClosingTag')($tag, $indentIndex, $hasContent)."\r\n", $indentIndex);
		} else {
			writeInOutput($output, genericClosingTag($tag, $indentIndex, $hasContent)."\r\n", $indentIndex);
		}
	}
}

function writeInOutput (string &$str, string $addition, int $indent) : void {
	$str .= getIndentationStr($indent).$addition;
}

function getIndentationStr (int $indent) : string {
	$returnStr = "";
	for($i = 0; $i < $indent; $i++){
		$returnStr .= "\t";
	}
	return $returnStr;
}

function genericClosingTag (Dom\Tag $tag, int $indentIndex, bool $hasContent = true) : string {
	$closingTag = (!$hasContent ? getIndentationStr(1)."\"\"\r\n".getIndentationStr($indentIndex + 1) : '');

	customClosingTagBase($closingTag, [], $tag, $indentIndex);
	return $closingTag;
}

//Tag overrides
function link_customClosingTag (Dom\Tag $tag, int $indentIndex, bool $hasContent = true) : string {
	$closingTag = '';

	$attrOverrides = ['href', 'rel'];
	customClosingTagBase($closingTag, $attrOverrides, $tag, $indentIndex, false);
	return $closingTag;
}

function script_customClosingTag (Dom\Tag $tag, int $indentIndex, bool $hasContent = true) : string {
	$closingTag = (!$hasContent ? '""' : '');

	$attrOverrides = ['src', 'async', 'defer'];
	customClosingTagBase($closingTag, $attrOverrides, $tag, $indentIndex);
	return $closingTag;
}

function a_customClosingTag (Dom\Tag $tag, int $indentIndex, bool $hasContent = true) : string {
	$closingTag = (!$hasContent ? '""' : '');

	$attrOverrides = ['href', 'target'];
	customClosingTagBase($closingTag, $attrOverrides, $tag, $indentIndex);
	return $closingTag;
}

function label_customClosingTag (Dom\Tag $tag, int $indentIndex, bool $hasContent = true) : string {
	$closingTag = (!$hasContent ? '""' : '');

	$attrOverrides = ['for'];
	customClosingTagBase($closingTag, $attrOverrides, $tag, $indentIndex);
	return $closingTag;
}

function form_customClosingTag (Dom\Tag $tag, int $indentIndex, bool $hasContent = true) : string {
	$closingTag = (!$hasContent ? '""' : '');

	$attrOverrides = ['method', 'action'];
	customClosingTagBase($closingTag, $attrOverrides, $tag, $indentIndex);
	return $closingTag;
}

function textarea_customClosingTag (Dom\Tag $tag, int $indentIndex, bool $hasContent = true) : string {
	$closingTag = (!$hasContent ? '""' : '');

	$attrOverrides = ['name'];
	customClosingTagBase($closingTag, $attrOverrides, $tag, $indentIndex);
	return $closingTag;
}

function iframe_customClosingTag (Dom\Tag $tag, int $indentIndex, bool $hasContent = true) : string {
	$closingTag = (!$hasContent ? '""' : '');

	$attrOverrides = ['src'];
	customClosingTagBase($closingTag, $attrOverrides, $tag, $indentIndex);
	return $closingTag;
}

function video_customClosingTag (Dom\Tag $tag, int $indentIndex, bool $hasContent = true) : string {
	$closingTag = (!$hasContent ? '""' : '');

	$attrOverrides = ['width', 'height', 'autoplay'];
	customClosingTagBase($closingTag, $attrOverrides, $tag, $indentIndex);
	return $closingTag;
}

function source_customClosingTag (Dom\Tag $tag, int $indentIndex, bool $hasContent = true) : string {
	$closingTag = '';

	$attrOverrides = ['src', 'type'];
	customClosingTagBase($closingTag, $attrOverrides, $tag, $indentIndex, false);
	return $closingTag;
}

function audio_customClosingTag (Dom\Tag $tag, int $indentIndex, bool $hasContent = true) : string {
	$closingTag = '';

	$attrOverrides = ['src', 'type'];
	customClosingTagBase($closingTag, $attrOverrides, $tag, $indentIndex, false);
	return $closingTag;
}

function input_customClosingTag (Dom\Tag $tag, int $indentIndex, bool $hasContent = true) : string {
	$closingTag = '';

	$attrOverrides = ['type', 'name', 'value'];
	customClosingTagBase($closingTag, $attrOverrides, $tag, $indentIndex, false);
	return $closingTag;
}

function option_customClosingTag (Dom\Tag $tag, int $indentIndex, bool $hasContent = true) : string {
	$closingTag = (!$hasContent ? '""' : '');

	$attrOverrides = ['value'];
	customClosingTagBase($closingTag, $attrOverrides, $tag, $indentIndex, $hasContent);
	return $closingTag;
}

function select_customClosingTag (Dom\Tag $tag, int $indentIndex, bool $hasContent = true) : string {
	$closingTag = (!$hasContent ? '""' : '');

	$attrOverrides = ['name'];
	customClosingTagBase($closingTag, $attrOverrides, $tag, $indentIndex, $hasContent);
	return $closingTag;
}

function br_customClosingTag (Dom\Tag $tag, int $indentIndex, bool $hasContent = true) : string {
	return '1)';
}

function img_customClosingTag (Dom\Tag $tag, int $indentIndex, bool $hasContent = true) : string {
	$closingTag = '';

	$attrOverrides = ['src', 'alt'];
	customClosingTagBase($closingTag, $attrOverrides, $tag, $indentIndex, false);
	return $closingTag;
}

function customClosingTagBase (string &$closingTag, array $overrides, Dom\Tag $tag,int $indent, bool $hasPriorParameter = true) : void {
	$processedOverrides = 0;
	if(!empty($overrides)) {

		//WRITING FIRST PARAMETER
		$firstOverrideArgument = $overrides[0];
		$firstOverrideArgumentName = $firstOverrideArgument;
		$urlArgumentNames = ['href', 'src', 'action']; //Will be absolutized

		if (in_array($firstOverrideArgument, $urlArgumentNames)) {
			$firstOverrideArgument = linkToAbsoluteLink(getProcessArgValue($tag, $firstOverrideArgument), $tag);
		} else {
			$firstOverrideArgument = getProcessArgValue($tag, $firstOverrideArgument);
		}
		if (
			!ctype_digit($firstOverrideArgument)
			and !str_contains($firstOverrideArgument,'getResourceURL(')
			and !str_contains($firstOverrideArgument, '_URL')
			and !in_array($firstOverrideArgument, ['true','false'])
		) $firstOverrideArgument = '"'.$firstOverrideArgument.'"';
		$closingTag .=
			getIndentationStr(1).($hasPriorParameter ? ',' : '').$firstOverrideArgument."\r\n";
		if($tag->hasAttribute($firstOverrideArgumentName)) $processedOverrides++;


		//WRITING OTHER PARAMETERS
		for ($i = 1; $i < count($overrides); $i++) {
			$argOverride = $overrides[$i];
			$argOverrideName = $argOverride;
			if (in_array($argOverride, $urlArgumentNames)) {
				$argOverride = linkToAbsoluteLink(getProcessArgValue($tag, $argOverride), $tag);
			} else {
				$argOverride = getProcessArgValue($tag, $argOverride);
			}
			if (
				!ctype_digit($argOverride)
				and !str_contains($argOverride,'getResourceURL(')
				and !str_contains($argOverride, '_URL')
				and !in_array($argOverride, ['true','false'])
			) $argOverride = '"'.$argOverride.'"';
			$closingTag .=
				getIndentationStr($indent + 1).",".$argOverride."\r\n";
			if($tag->hasAttribute($argOverrideName)) $processedOverrides++;
		}
		$openingTagIndex = $indent + 1;
	}else{
		$openingTagIndex = 0;
	}

	if(count($tag->getAttributes()) > $processedOverrides) {
		//WRITING ARRAY
		$closingTag .= getIndentationStr($openingTagIndex).",[";
		foreach ($tag->getAttributes() as $key => $value) {
			if ($value->getValue() == null or strlen($value->getValue()) == 0 or ctype_space($value->getValue())) continue;
			if (in_array($key, $overrides)) continue;
			$closingTag .= "'".$key."' => '".getProcessArgValue($tag, $key)."', ";
		}
		$closingTag = str_removeFromEnd($closingTag, ', ');
		$closingTag .= "]\r\n".getIndentationStr($indent).')';
	}else{
		$closingTag = rtrim($closingTag, "\r\n");
		$closingTag .= "\r\n".getIndentationStr($indent).')';
	}
}

function getProcessArgValue (Dom\Tag $tag, string $argName) : string {
	$boolArgumentNames = ['async', 'defer', 'autoplay']; //Will return either true or false
	$optionalArgumentNames = ['target' => '_self']; //Will default to value

	if(in_array($argName, $boolArgumentNames)){
		return ($tag->hasAttribute($argName) ? 'true' : 'false');
	}
	if(array_key_exists($argName, $optionalArgumentNames)){
		return ($tag->hasAttribute($argName) ? $tag->getAttribute($argName)->getValue() : $optionalArgumentNames[$argName]);
	}

	return ($tag->hasAttribute($argName) ? ($tag->getAttribute($argName)->getValue() != null ? $tag->getAttribute($argName)->getValue() : '') : '');
}

function linkToAbsoluteLink (string $link, Dom\Tag $tag) : string {
	if(!str_startsWith($link, 'http') and !str_startsWith($link, "#")){
		$addTemplateResourcePath = ['img','script','link'];
		if(in_array($tag->name(), $addTemplateResourcePath)){
			$parts = explode('/', $link);
			$partsLen = count($parts);
			$fileName = $parts[$partsLen - 1];
			if($partsLen == 1){
				return 'getResourceURL("'.$fileName.'", "'.$_SESSION['temp_templateId'].'")';
			}else{
				$pathWithoutFile = str_removeFromEnd($link, '/'.$fileName);
				return 'getResourceURL("'.$fileName.'", "'.$_SESSION['temp_templateId'].'/'.$pathWithoutFile.'")';
			}
		}else{
			$link = str_removeFromEnd($link, '.html');
			return "_URL.'$link'";
		}
	}
	return $link;
}