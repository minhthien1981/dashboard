<!DOCTYPE html>
<!--
Copyright (c) 2007-2018, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or https://ckeditor.com/sales/license/ckfinder
-->
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
	<link href="<?php echo base_url() ?>public/admin/img/favicon.ico" rel="shortcut icon" type="image/x-icon" />
	<title>CKFinder 3 - File Browser</title>
</head>
<body>
<script src="<?php echo base_url();?>public/documents/ckfinder/ckfinder.js"></script>
<script>
	var config = {};

	// Set your configuration options below.

	// Examples:
	// config.language = 'pl';
	config.connectorPath = '<?php echo base_url().ADMIN;?>ckfinder/connector';

	CKFinder.start(config);
</script>

</body>
</html>

