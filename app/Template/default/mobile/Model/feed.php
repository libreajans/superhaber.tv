<?php if(!defined('APP')) die('...');

	include G_TEMPLATE_PATH.$template_name.'/control/page_feed.php';

	header('Content-type: application/xml');
	echo $page;
