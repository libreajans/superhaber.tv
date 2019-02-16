<?php if(!defined('APP')) die('...');

	$site_canonical		= LINK_FEED;

	$list = $_content->content_list_feed(50);

	$template = $twig->loadTemplate('page_feed.twig');
	$page = $template->render
	(
		array
		(
			'content'			=> $list,
			'last_build_date'	=> pco_format_date(time()),
		)
	);
