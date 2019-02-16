<?php if(!defined('APP')) die('...');

	$icerik = $_page->page_list($_id);


	$site_title			= $icerik[0]['page_title'].' | '. $L['pIndex_Company'];
	$content_metatitle 	= $icerik[0]['page_title'].', '. $L['pIndex_Company'];
	$content_metadesc 	= $icerik[0]['page_title'].', '. $L['pIndex_Company'];
	$content_metatags	= $icerik[0]['page_title'].', '. $L['pIndex_Company'];
	$site_canonical 	= $array_page_url[$_id];
	$content_metaimage	= G_IMGLINK_APP.'logo_sh.png';

	$link_edit 			= LINK_ACP.'&view=page&do=edit&id='.$_id;

	$template = $twig->loadTemplate('page_page.twig');
	$page = $template->render
	(
		array
		(
			'content' 			=> $icerik[0],
			'site_canonical' 	=> $site_canonical,
			'link_edit' 		=> $link_edit,
		)
	);
