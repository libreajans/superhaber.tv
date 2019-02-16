<?php if(!defined('APP')) die('...');

	//video sayfasını erişilemez kılmak istersek
	//if(RC_InternalVideo <> 1) return false;

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
			'content_benzer_list'	=> $_content->content_list_most_view($limit = 3, $type = 'video', $cat = 'none', $json = 0),
			'site_canonical' 		=> $site_canonical,
			'content_list' 			=> $_content->content_list_gallery
			(
				$type		= 'none',
				$template	= '4',
				$limit		= $config['desktop_limit_gallery'],
				$page		= $_pg
			),
			'content_list_pages' 	=> $_content->content_list_gallery_pages
			(
				$type		= 'none',
				$template	= '4',
				$limit		= $config['desktop_limit_gallery']
			),
		)
	);
