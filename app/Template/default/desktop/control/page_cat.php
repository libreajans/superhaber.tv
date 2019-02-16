<?php if(!defined('APP')) die('...');

	//id burada cat_id anlamına gelmektedir, content_id ile karıştırılmamalıdır
	$site_title				= $array_cat_seo_title[$_id];
	$content_metatitle 		= $array_cat_seo_title[$_id];
	$content_metadesc 		= $array_cat_seo_desc[$_id];
	$content_metatags		= $array_cat_seo_meta[$_id];
	$site_canonical			= $array_cat_url[$_id];
	$content_metaimage		= G_IMGLINK_APP.'logo_sh.png';

	$template = $twig->loadTemplate('page_cat.twig');
	$twig->addGlobal('color', 'cat-'.format_url($array_cat_name[$_id]));
	$twig->addGlobal('sayfa_title', $array_cat_name[$_id]);
	$content = $template->render
	(
		array
		(
			'content_benzer_list'	=> $_content->content_list_most_view($limit = 3, $type = 'cat', $cat = $_id, $json = 0),
			'site_canonical' 		=> $site_canonical,
			'total_id'				=> $_id,
			'cat_id'				=> $_id,
			'content_list'			=> $_content->content_list_cat($_pg, $config['desktop_limit_cat'], $_id),
			'content_list_pages'	=> $_content->content_list_cat_pages($config['desktop_limit_cat'], $_id),
		)
	);
