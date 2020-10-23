/**
 * @license Copyright (c) 2003-2020, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	 config.language = _LANG;
	 //config.uiColor = '#AADC6E';

	config.imageUploadURL = _URL + 'ckedit_upload.php';
	config.uploadUrl = '/ckedit_upload.php';
	config.dataParser = function (data) {
		if(data.url){
			return data.url;
		}
		return '';
	};
};
