<?php
//This file should probably not be altered



if(!empty($_FILES)){
	require_once _ROOT_PATH.'functions/inc/templateImportFunctions.php';
	$response = 'An error occured';
	$file = $_FILES['file'];
	if($file){
		$templateId = uniqid();
		if($tmpId = $_REQUEST['templateName']){
			$templateId = $tmpId;
		}
		$zip = new ZipArchive();
		$res = $zip->open($file['tmp_name']);

		$newFolder = _ROOT_PATH.'temp/'.uniqid();
		mkdir($newFolder);

		if($res === true){
			$zip->extractTo($newFolder);
			$zip->close();

			if(processFolder($newFolder, $templateId)) $response = "Template imported!";

			delete_directory($newFolder);
		}
	}
	die("
		$('#results h3').html('$response');
		setTimeout(function () {
			$('#results').empty();
			location.reload();
		}, 2200);
	");
}

// Set the text domain as "messages"
$domain = "messages";
$r = bindtextdomain($domain, _ROOT_PATH."locales");


$r = textdomain($domain);

$frontend =
	'<head>'
	.title("Template Importation")
	.'</head>'
	.script('','https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js')
	.script('','https://cdnjs.cloudflare.com/ajax/libs/plupload/3.1.2/plupload.full.min.js')
	.h1("Template Importation Function")
	.br(3)
	.imgTag(getResourceURL('zip.jpg', 'functions'), 'ZIP', ['width' => '100', 'height' => '100'])
	.br(2)
	.h3("This is the frontend template importation tool!")
	.h4("Here you can import an html, js and css template and integrate it quickly into the framework!")
	.h4("Simply upload a .zip file and look at your newly created php files in the /page folder. This will also populate the")
	.h4("/resources folder. Don't temper with it.")
	.h4("html files must be in the root of the zip file. Every other files and folders will be sent")
	.h4("to the /ressource/[uuid] folder")
	.br(2)
	.p("Template : ")
	.input('text', 'templateName', '', ['placeholder' => "Template internal name"])
	.button("Upload",attributes('template-upload'))
	.br(2)
	.div(h3(''), attributes('results'));

$frontendJs =
	"
		var uploader;
	
		$(document).ready(function (e) {
			uploader = new plupload.Uploader({
				runtime: 'html5',
				browse_button: 'template-upload',
				url: '"._URL."functions/import_template',
				filters: {
					mime_types: [
						{title: 'Zip file', extensions: 'zip'}
					]
				}
			});
			
			uploader.bind('FilesAdded', function (up, files) {
				uploader.setOption('multipart_params', {
					templateName: $('input[name=templateName]').val()
				});
				uploader.start();
				$('#results h3').html('Loading...');
			});
			
			uploader.bind('FileUploaded', function (up, files, result) {
				eval(result.response);
			});
			
			uploader.init();
		});
	"
;

echo $frontend.script($frontendJs);