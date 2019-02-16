<?php if(!defined('APP')) die('...');

	$site_title					= tr_ucwords($_key).' - '.$L['pIndex_Company'];
	$content_metatitle 			= tr_ucwords($_key).', '.$L['pIndex_Company'];
	$content_metadesc 			= tr_ucwords($_key).' dair son gelişmeler superhaber.tv\'da';
	$content_metatags			= $_key.', '.$L['pIndex_Company'];
	$site_canonical				= LINK_TAGS.$_key;
	$content_metaimage			= G_IMGLINK_APP.'logo_sh.png';

	$content_list_etiket		= $_content->content_list_etiket($_pg, $config['mobile_limit_cat'], $_key);
	$content_list_etiket_pages	= $_content->content_list_etiket_pages($config['mobile_limit_cat'], $_key);

	//aranan etiket 0 sonuç döndürmüş ise
	if(count($content_list_etiket) == 0)
	{
		header("HTTP/1.1 404 Not Found");
		header('Location:'.LINK_INDEX);
		exit();
	}

	//3 karakterden kısa bir etiket aranıyorsa sistemi yormamak adına ana sayfaya yönlendiriyoruz
	if(strlen($_key) < 3)
	{
		header("HTTP/1.1 404 Not Found");
		header('Location:'.LINK_INDEX);
		exit();
	}

	$template = $twig->loadTemplate('page_tags.twig');
	$content = $template->render
	(
		array
		(
			'site_canonical' 		=> $site_canonical,
			'content_list'			=> $content_list_etiket,
			'content_list_pages'	=> $content_list_etiket_pages,
		)
	);
