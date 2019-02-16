<?php if(!defined('APP')) die('...');

	$site_title			= $array_page_name[204].' - '.$L['pIndex_Company'];
	$content_metatitle 	= $array_page_name[204].', '.$L['pIndex_Company'];
	$content_metadesc 	= $array_page_name[204].', '.$L['pIndex_Company'];
	$content_metatags	= $array_page_name[204].', '.$L['pIndex_Company'];
	$site_canonical		= LINK_GALLERY;
	$content_metaimage	= G_IMGLINK_APP.'logo_sh.png';

	$template = $twig->loadTemplate('page_gallery.twig');
	$twig->addGlobal('color', 'color-page-gallery');
	$twig->addGlobal('sayfa_title', $array_page_name[204]);

	$content = $template->render
	(
		array
		(
			'content_benzer_list'	=> $_content->content_list_most_view($limit = 5, $type = 'galeri', $cat = 'none', $json = 0),
			'site_canonical' 		=> $site_canonical,
			'content_list' 			=> $_content->content_list_gallery
			(
				$type		= 'none',
				$ctemplate	= '1',
				$limit		= $config['desktop_limit_gallery'],
				$page		= $_pg
			),
			'content_list_pages'	=> $_content->content_list_gallery_pages
			(
				$type		= 'none',
				$ctemplate	= '1',
				$limit		= $config['desktop_limit_gallery']
			),
		)
	);
