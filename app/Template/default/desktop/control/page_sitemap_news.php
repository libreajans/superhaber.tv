<?php if(!defined('APP')) die('...');

	$list		= $_content->content_list_sitemap_news();
	$template	= $twig->loadTemplate('page_sitemap_news.twig');
	$page		= $template->render
	(
		array
		(
			'content'	=> $list,
		)
	);
