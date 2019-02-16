<?php if(!defined('APP')) die('...');

	include G_TEMPLATE_PATH.$template_name.'/control/page_sitemap.php';

	header('Content-type: application/xml');
	echo $page;
