<?php if(!defined('APP')) die('...');

	$site_title			= $array_page_name[202].' - '.$L['pIndex_Company'];
	$content_metatitle 	= $array_page_name[202].', '.$L['pIndex_Company'];
	$content_metadesc 	= $array_page_name[202].', '.$L['pIndex_Company'];
	$content_metatags	= $array_page_name[202].', '.$L['pIndex_Company'];
	$site_canonical		= LINK_ARSIV;
	$content_metaimage	= G_IMGLINK_APP.'logo_sh.png';

	$tarih = myReq('tarih', 1);
	if($tarih == '') $day_today = date('Y-m-d');

	//burda tarihi tarif formatında olup olmadığını doğrulamamız lazım ;)
	if($tarih <> '') $day_today = $tarih;

	$template = $twig->loadTemplate('page_archive.twig');
	$twig->addGlobal('color', 'cat-108');
	$twig->addGlobal('sayfa_title', $array_page_name[202]);

	$content = $template->render
	(
		array
		(
			'color'				=> 'cat-108',
			'link_change' 		=> LINK_ARSIV,
			'link_mindate' 		=> '2015-01-01',
			'time_selected' 	=> $day_today,
			'arsiv_list' 		=> $_content->content_list_archive($day_today, $type = 'arsiv'),
		)
	);
