<?php
require_once 'core/includes.php';

/**
 * This file generates a sitemap XML value. Do not alter.
 * Change the /meta folder content instead
 */


function urlsetXmlTag (string $content, string $xmlns) : string {
	return '<urlset xmlns="'.$xmlns.'">'.$content.'</urlset>';
}

function urlXmlTag (string $content) : string {
	return '<url>'.$content.'</url>';
}

function locXmlTag (string $content) : string {
	return '<loc>'.$content.'</loc>';
}

function lastmodXmlTag (string $content) : string {
	return '<lastmod>'.$content.'</lastmod>';
}

function changefreqXmlTag (string $content) : string {
	return '<changefreq>'.$content.'</changefreq>';
}

function priorityXmlTag (string $content) : string {
	return '<priority>'.$content.'</priority>';
}

$pagesUrlXml = "";
foreach (glob(_ROOT_PATH.'page/*.php') as $page){
	$pageFile = explode('/', $page);
	$pageFileCount = count($pageFile);
	$pageFile = $pageFile[$pageFileCount - 1];
	$pageFile = str_removeFromEnd($pageFile, '.php');

	$lastMod = "";
	$changeFreq = "";
	$priority = "";

	$lastMod = date('Y-m-d', filemtime($page));

	$metaFile = _ROOT_PATH.'meta/'.$pageFile.'.meta';
	if(is_file($metaFile)){
		$metaFh = fopen($metaFile, 'r');
		try{
			while(($line = fgets($metaFh)) !== false){
				//TODO: this part could use some improvements
				if(str_startsWith($line, 'changeFreq')){
					$changeFreq = changefreqXmlTag(trim(explode(':', $line)[1], ' '));
				}else if(str_startsWith($line, 'priority')){
					$priority = priorityXmlTag(trim(explode(':', $line)[1], ' '));
				}
			}
		} finally {
			@fclose($metaFh);
		}
	}


	$pagesUrlXml .=
		urlXmlTag(
			locXmlTag(_URL.$pageFile)
			.lastmodXmlTag($lastMod)
			.$changeFreq
			.$priority
		);
}


echo '<?xml version="1.0" encoding="UTF-8"?>';
echo urlsetXmlTag(
		$pagesUrlXml
	, 'http://www.sitemaps.org/schemas/sitemap/0.9'
);