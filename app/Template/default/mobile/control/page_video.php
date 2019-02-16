<?php if(!defined('APP')) die('...');

	$site_title			= $array_page_name[205].' - '.$L['pIndex_Company'];
	$content_metatitle 	= $array_page_name[205].', '.$L['pIndex_Company'];
	$content_metadesc 	= $array_page_name[205].', '.$L['pIndex_Company'];
	$content_metatags	= $array_page_name[205].', '.$L['pIndex_Company'];
	$site_canonical		= LINK_VIDEO;
	$content_metaimage	= G_IMGLINK_APP.'logo_sh.png';

	$template = $twig->loadTemplate('page_video.twig');
	$twig->addGlobal('color', 'color-page-video');
	$twig->addGlobal('sayfa_title', $array_page_name[205]);

	$content = $template->render
	(
		array
		(
			'site_canonical' 		=> $site_canonical,
			'content_list' 			=> $_content->content_list_gallery
			(
				$type		= 'none',
				$template	= '4',
				$limit		= $config['mobile_limit_gallery'],
				$page		= $_pg
			),
			'content_list_pages' 	=> $_content->content_list_gallery_pages
			(
				$type		= 'none',
				$template	= '4',
				$limit		= $config['mobile_limit_gallery']
			),
		)
	);
