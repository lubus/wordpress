<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php _e('Print order details', 'eshopbox-pip'); ?></title>
	<link href="<?php echo eshopbox_pip_template('uri', 'template.php'); ?>css/eshopbox-pip-print.css" rel=" stylesheet" type="text/css" media="print" />
	<link href="<?php echo eshopbox_pip_template('uri', 'template.php'); ?>css/eshopbox-pip.css" rel=" stylesheet" type="text/css" media="screen,print" />
        <script type="text/javascript" src="<?php echo  plugins_url();?>/eshopbox-pip/js/jquery-1.3.2.min.js" ></script>  
        <script type="text/javascript" src="<?php echo  plugins_url();?>/eshopbox-pip/js/jquery-barcode.js" ></script>  
</head>
<body <?php if ($client != true) echo eshopbox_pip_preview(); ?>> 