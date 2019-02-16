<?php if(!defined('APP')) die('...');

	include G_TEMPLATE_PATH.$template_name.'/control/page_cat.php';
	include G_TEMPLATE_PATH.$template_name.'/control/site_header.php';
	include G_TEMPLATE_PATH.$template_name.'/control/site_footer.php';

	echo $header.$content.$footer;
