<?php if(!defined('APP')) die('...');

	$type	= myReq('type',1);
	$image	= myReq('image',1);

	$list = $_content->content_list_sitemap($limit = 500, $type);

	$template = $twig->loadTemplate('page_sitemap.twig');
	$page = $template->render
	(
		array
		(
			'type'		=> $type,
			'image'		=> $image,
			'content'	=> $list,
			'page_url'	=> $array_page_url,
			'tarih' 	=> date('Y-m-d',time()).'T'.date('H:i:s',time()).'+03:00',
		)
	);

