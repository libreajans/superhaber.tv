<?php if(!defined('APP')) die('...');

	$keyword			= myReq('search', 2);

	$site_title			= 'Arama - '.$L['pIndex_Company'];
	$content_metatitle 	= $L['pIndex_Company'];
	$content_metadesc 	= $L['pIndex_Company'];
	$content_metatags	= $L['pIndex_Company'];
	$site_canonical		= LINK_SEARCH;
	$content_metaimage	= G_IMGLINK_APP.'logo_sh.png';

	if($keyword <> "" )
	{
		$content_list 		= $_content->content_list_search($keyword, $limit = $config['limit_100'], $json = 0);
		$site_canonical		= LINK_SEARCH.'/'.urlencode($keyword);
		$site_title			= strip_tags(htmlspecialchars($keyword)).' - Arama - '.$L['pIndex_Company'];
	}

	$template = $twig->loadTemplate('page_search.twig');
	$content = $template->render
	(
		array
		(
			'keyword' 				=> htmlentities(strip_tags($keyword)),
			'content_list'			=> $content_list,
		)
	);
