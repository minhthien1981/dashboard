/**
 * @license Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */
CKEDITOR.editorConfig = function( config ) {

	// Define changes to default configuration here. For example:
	config.language = 'vi';
	//config.uiColor = '#AADC6E';
	// 
	config.filebrowserBrowseUrl = ckeditor_config.base_url + ckeditor_config.html_path,
	//config.filebrowserImageBrowseUrl = ckeditor_config.base_url + ckeditor_config.html_path + '?type=Images',
    config.filebrowserUploadUrl = ckeditor_config.base_url + ckeditor_config.connector_path + '?command=QuickUpload&type=Files',
    config.filebrowserImageUploadUrl = ckeditor_config.base_url + ckeditor_config.connector_path + '?command=QuickUpload&type=Images'
    config.filebrowserWindowWidth = '1000',
    config.filebrowserWindowHeight = '700',
    config.extraPlugins = 'youtube,video',
    config.font_defaultLabel = 'Arial';
	config.fontSize_defaultLabel = '16px';
};